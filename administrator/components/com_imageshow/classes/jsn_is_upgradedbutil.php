<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_readxmldetails.php');
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_js_backup.php');
class JSNJSUpgradeDBAction
{
	function _getFields($table = '')
	{
		$result	= array();
		$db		=& JFactory::getDBO();		
		$query	= 'SHOW FIELDS FROM '.$db->nameQuote($table);	
		$db->setQuery($query);
		$fields	= $db->loadObjectList();
		if(count($fields))
		{	
			foreach ($fields as $field)
			{
				$result[$field->Field] = preg_replace('/[(0-9)]/' , '' , $field->Type);
			}	
		}
		return $result;
	}

	function _getTables()
	{
		$result	= array();
		$db		=& JFactory::getDBO();
		return  $db->getTableList();
	}
	
	function isExistTableColumn($tableName, $columnName)
	{
		$fields	= $this->_getFields($tableName);				
		if (array_key_exists($columnName, $fields))
		{
			return true;
		}		
		return false;
	}
	
	function isExistTable($tableName)
	{
		$tables 	= $this->_getTables();
		$config 	=& JFactory::getConfig();
		$dbprefix   = $config->getValue('config.dbprefix');	
		$tableName  = str_replace('#__', $dbprefix, $tableName);
		
		if(@in_array($tableName, $tables))
		{
			return 	true;	
		}
		
		return false;
	}
}

class JSNISUpgradeDBUtil
{
	var $_fileName 		  = 'db_schema_upgrade.xml';
	var $_mainfest		  = null;
	var $_previousVersion = null;
	var $_currentVersion  = null;
	var $_versionIndexes  = array();
	var $_currentTables   = array();
	var $_upgradeDBAction = null;
	var $_items			  = array();
	 
	function JSNISUpgradeDBUtil($manifest)
	{
		$this->setManifest($manifest);
		$this->setObjUpgradeDBAction();
		$this->setPreviousVersion();
		$this->setCurrentVersion();
		$this->parserXMLContent();
	}
	
	function setObjUpgradeDBAction()
	{
		$this->_upgradeDBAction = new JSNJSUpgradeDBAction();
	}
	
	function setManifest($manifest)
	{
		$this->_mainfest = $manifest;
	}
	
	function setPreviousVersion()
	{
		$objectReadxmlDetail    = new JSNISReadXmlDetails();
		$infoXmlDetail 		    = $objectReadxmlDetail->parserXMLDetails();
		$this->_previousVersion	= @$infoXmlDetail['version'];	
	}
	
	function setCurrentVersion()
	{
		$document 	 			=& $this->_mainfest->document;
		$version     			=& $document->getElementByPath('version');
		$this->_currentVersion	= $version->data();
	}
	
	function getIndexes($key)
	{
		return $this->_versionIndexes[$key];
	}
		
	function setVersionIndex($key, $value)
	{
		$this->_versionIndexes[$key] = $value;
	}
	
	function setCurrentTable($key, $value)
	{
		$this->_currentTables[$key] = $value;
	}
	
	function extractArray($data, $begin, $end)
	{
		$newData = array();
		for($i = $begin; $i <= $end; $i++)
		{
			$newData [] = $data[$i];
		}
		return $newData;
	}
	
	function extractVersionRange()
	{
		$items 		   = $this->_items;
		$beignIndex    = $this->getIndexes($this->_previousVersion);
		$endIndex      = $this->getIndexes($this->_currentVersion);
		$items 		   = $this->extractArray($items, $beignIndex, $endIndex);
		return $items;
	}
	
	function parserXMLContent()
	{
		$versionsArray			= array();
		$filePath 				= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.$this->_fileName;
		$parser					=& JFactory::getXMLParser('Simple');
		$resultLoadFile     	= $parser->loadFile($filePath);

		if($resultLoadFile == false)
		{
			return false;
		}
		
		$document	=& $parser->document;					
		
		if($document->getElementByPath('version') != false)
		{
			$versions =& $document->version;
			if (count($versions))
			{
				for($i = 0, $count = count($versions); $i < $count; $i++)
				{
					$tablesArray		= array();
					$version    		= $versions[$i];
					$versionAttributes  = $version->attributes();
					$objVersion			= new stdClass();
					$objVersion->number = $versionAttributes['number'];
					$this->setVersionIndex($versionAttributes['number'], $i);
					
					if($version->getElementByPath('tables') != false)
					{
						$tables 	 =& $version->tables;
						$tableParent =& $tables[0]->table;
						
						if(count($tableParent))
						{
							for ($j = 0, $count1 = count($tableParent); $j < $count1; $j++)
							{
								$fieldsArray 		= array();
								$table 				= $tableParent[$j];
								$tableAttributes 	= $table->attributes();
								
								$objTable 			= new stdClass();
								$objTable->id	    = $tableAttributes['id'];
								$objTable->status   = $tableAttributes['status'];
								$objTable->name	    = $tableAttributes['name'];
								
								$fields				=& $table->field;
								if(count($fields))
								{
									for ($z = 0, $count2 = count($fields); $z < $count2; $z++)
									{
										$field						= $fields[$z];
										$fieldAttributes 			= $field->attributes();
										
										$objField					= new stdClass();
										$objField->id				= $fieldAttributes['id'];
										$objField->status			= $fieldAttributes['status'];
										$objField->name				= (isset($fieldAttributes['name'])?$fieldAttributes['name']:'');
										$objField->type				= (isset($fieldAttributes['type'])?$fieldAttributes['type']:'');
										$objField->primary_key		= (isset($fieldAttributes['primary_key'])?$fieldAttributes['primary_key']:'');
										$objField->default_value	= (isset($fieldAttributes['default_value'])?$fieldAttributes['default_value']:'');
										$objField->not_null			= (isset($fieldAttributes['not_null'])?$fieldAttributes['not_null']:'yes');
										$fieldsArray []				= $objField;
									}
								}
								$objTable->fields	= $fieldsArray;
								$tablesArray []		= $objTable;
							}
							
						}
			
					}
					$objVersion->tables 	= $tablesArray;
					$versionsArray []		= $objVersion;
				}
			}
		}
		
		$this->_items = $versionsArray;
		
	}
	
	function getTableStatus($status)
	{
		$tableResult   = array();
		$fieldIDs	   = array();
		$items         = $this->_items;

		for ($i = 1, $count = count($items); $i < $count; $i++)
		{
			$data     	   = $items[$i];
			$tables        = $data->tables;
			if (count($tables))
			{
				for ($j = 0, $count1 = count($tables); $j < $count1; $j++)
				{
					$table    	= $tables[$j];
					$tableName  = $table->name;
					$result		= array();
					if (JString::strtolower($table->status) == JString::strtolower($status))
					{
						$fields      = $table->fields;
						if (count($fields) && JString::strtolower($table->status) == 'added')
						{
							for ($z = 0, $count2 = count($fields); $z < $count2; $z++)
							{
								$obj 		  	  	= new stdClass();
								$field   	  	  	= $fields[$z];
								$obj->id      	  	= $field->id;
								$obj->status  	  	= $field->status;
								$obj->name    	  	= $field->name;
								$obj->type    	  	= $field->type;
								$obj->primary_key 	= (isset($field->primary_key)?$field->primary_key:'');
								$obj->default_value	= (isset($field->default_value)?$field->default_value:'');
								$obj->not_null	  	= (isset($field->not_null)?$field->not_null:'yes');
								$result []    	  	= $obj;
							}
							$tableResult [$tableName] = $result;						
						}
						else 
						{
							$tableResult [] = $tableName;
						}
					
					}
				}
			}
		}

		return $tableResult;		
	}
	
	function searchField($tableID, $fieldID)
	{
		$result	= array();
		$items  = $this->_items;
	
		for ($i = 0, $count = count($items); $i < $count; $i++)
		{	
			$data     = $items[$i];	
			$tables   = $data->tables;
			$version  = $data->number;
			
			if (count($tables))
			{
				for ($j = 0, $count1 = count($tables); $j < $count1; $j++)
				{
					$table = $tables[$j];
					if ($table->id == $tableID)
					{
						$fields = $table->fields;
						if (count($fields))
						{
							for ($z = 0, $count2 = count($fields); $z < $count2; $z++)
							{
								$obj = new stdClass();
								$field = $fields[$z];
								
								if($field->id == $fieldID)
								{
									$obj->index   = $i;
									$obj->id      = $field->id;
									$obj->version = $version;
									$obj->status  = $field->status;
									$obj->name    = $field->name;
									$obj->type    = $field->type;
									$obj->not_null= $field->not_null;
									$result []    = $obj;
									break;
								}
							}
						}
						break;
					}
				}
			}
		}
		return $result;	
	}
	
	function getFieldChanged($items)
	{		
		$tableResult   = array();
		$fieldIDs	   = array();
		for ($i = 1, $count = count($items); $i < $count; $i++)
		{
			$data     	   = $items[$i];
			$version       = $data->number;
			$tables        = $data->tables;
			if (count($tables))
			{
				for ($j = 0, $count1 = count($tables); $j < $count1; $j++)
				{					
					
					$table         = $tables[$j];
					$tableName     = $table->name;
					$tableID       = $table->id;
					$tableStatus   = $table->status;
					
					if (JString::strtolower($tableStatus) == 'changed')
					{
						$fields      = $table->fields;
						
						if (count($fields))
						{
							for ($z = 0, $count2 = count($fields); $z < $count2; $z++)
							{
								$field   	 = $fields[$z];
								$fieldID 	 = $field->id;
								$fieldStatus = $field->status;
								
								if (JString::strtolower($fieldStatus) == 'changed' && !in_array($fieldID, $fieldIDs))
								{
									$tableResult [$tableName][] = $this->searchField($tableID, $fieldID);
									$fieldIDs []    			= $fieldID;
								}
							}
						}
					}
				}
				$fieldIDs = array_unique($fieldIDs);
			}
		}
		
		return $tableResult;
	}
	
	function getFieldAdded($items)
	{
		
		$tableResult   = array();
		for ($i = 1, $count = count($items); $i < $count; $i++)
		{
			$data     	   = $items[$i];
			$version       = $data->number;
			$tables        = $data->tables;
			if (count($tables))
			{
				for ($j = 0, $count1 = count($tables); $j < $count1; $j++)
				{					
					$table         = $tables[$j];
					$tableName     = $table->name;
					$tableID       = $table->id;
					$tableStatus   = $table->status;
					
					if (JString::strtolower($tableStatus) == 'changed')
					{
						$fields      = $table->fields;
						
						if (count($fields))
						{
							for ($z = 0, $count2 = count($fields); $z < $count2; $z++)
							{
								
								$field   	 = $fields[$z];
								$fieldID 	 = $field->id;
								$fieldStatus = $field->status;
								$obj 		 = new stdClass();
								if (JString::strtolower($fieldStatus) == 'added')
								{
									
									$obj->index   				= $i;
									$obj->id     				= $field->id;
									$obj->version 				= $version;
									$obj->status  				= $field->status;
									$obj->name    				= $field->name;
									$obj->type    				= $field->type;
									$obj->default_value			= (isset($field->default_value)?$field->default_value:'');
									$obj->not_null				= (isset($field->not_null)?$field->not_null:'');
									$tableResult [$tableName][] = $obj;
								}
							}
						}
					}
				}
			}
		}
		
		return $tableResult;							
	}
	
	function getFieldRemoved($items)
	{
		$tableResult   = array();
		for ($i = 1, $count = count($items); $i < $count; $i++)
		{
			$data     	   = $items[$i];
			$version       = $data->number;
			$tables        = $data->tables;
			if (count($tables))
			{
				for ($j = 0, $count1 = count($tables); $j < $count1; $j++)
				{					
					$table         = $tables[$j];
					$tableName     = $table->name;
					$tableID       = $table->id;
					$tableStatus   = $table->status;
					
					if (JString::strtolower($tableStatus) == 'changed')
					{
						$fields      = $table->fields;
						
						if (count($fields))
						{
							for ($z = 0, $count2 = count($fields); $z < $count2; $z++)
							{
								
								$field   	 = $fields[$z];
								$fieldID 	 = $field->id;
								$fieldStatus = $field->status;
								$obj 		 = new stdClass();
								if (JString::strtolower($fieldStatus) == 'removed')
								{
									$obj->index   				= $i;
									$obj->id      				= $field->id;
									$obj->version = $version;
									$obj->status  = $field->status;
									$obj->name    = $field->name;
									$tableResult [$tableName][] = $obj;
								}
							}
						}
					}
				}
			}
		}
		
		return $tableResult;							
	}
		
	function processData()
	{
		$queries			= array();
		
		$items   			= $this->extractVersionRange();
		$preDataAdd    		= $this->getFieldAdded($items);
		$preDataChange 		= $this->getFieldChanged($items);
		$preDataRemove 		= $this->getFieldRemoved($items);
		
		$dataChange			= $this->processDataChanged($preDataChange);		
		$queriesChange 		= $this->buildQueriesChange($dataChange);

		$tableRemoved   	= $this->getTableStatus('removed');
		$queriesTableRemove	= $this->buildQueriesTableRemove($tableRemoved);
				
		$tableAdded   	    = $this->getTableStatus('added');
		$queriesTableAdd	= $this->buildQueriesTableAdd($tableAdded, $dataChange, $tableRemoved);
		
		if(count($queriesTableAdd))
		{
			if(isset($queriesTableAdd['create']) && count($queriesTableAdd['create']))
			{
				$queries = array_merge($queries, $queriesTableAdd['create']);
			}
		}
		$dataAdd 			= $this->processDataAdded($preDataAdd);
		$queriesAdd 		= $this->buildQueriesAdd($dataAdd);

		if(count($queriesAdd))
		{
			$queries = array_merge($queries, $queriesAdd);
		}

		if(count($queriesChange))
		{
			$queries = array_merge($queries, $queriesChange);
		}

		$dataRemove			= $this->processDataRemoved($preDataRemove);
		$queriesRemove 		= $this->buildQueriesRemove($dataRemove);
		
		if(count($queriesRemove))
		{
			$queries = array_merge($queries, $queriesRemove);
		}	
		
		if(count($queriesTableAdd))
		{
			if(isset($queriesTableAdd['insert']) && count($queriesTableAdd['insert']))
			{
				$queries = array_merge($queries, $queriesTableAdd['insert']);
			}

			if(isset($queriesTableAdd['alter']) && count($queriesTableAdd['alter']))
			{
				$queries = array_merge($queries, $queriesTableAdd['alter']);
			}					
		}
				
		if(count($queriesTableRemove))
		{
			$queries = array_merge($queries, $queriesTableRemove);
		}		
		
		return $queries;
	}
	
	function executeUpgradeDB()
	{
		$db		=& JFactory::getDBO();
		$data 	= $this->processData();
		if (count($data))
		{
			foreach ($data as $value)
			{
				if ($value != '') 
				{
					$db->setQuery($value);
					$db->query();
				}
			}
		}
		return true;
	}
	
	function buildQueriesTableAdd($data, $dataChange, $tableRemoved)
	{
		$queries = array();

		if(count($data))
		{
			foreach ($data as $key => $value)
			{
				$flagSearchTable  = false;
				
				if(in_array($key, $tableRemoved) || $this->_upgradeDBAction->isExistTable($key)) continue;
				
				$tableName   	  = $key;
				$sourceDropField  = '';
				$sourceField 	  = '';
				$destinationField = '';
				$sourceTableName  = '';
				$queryDrop        = '';
				$queryInsert      = '';
				$query 		 	  = 'CREATE TABLE `'.$tableName.'` ('; 
					
				for($i = 0, $count = count($value); $i < $count; $i++)
				{
					$item   = $value[$i];
					if($item->not_null == 'yes')
					{
						$notNull = 'NULL';
					}
					else 
					{
						$notNull = 'NOT NULL';
					}

					if(JString::strtolower($item->primary_key) == 'yes')
					{
						$query 		.= '`'.$item->name.'` '.$item->type.' '.$notNull.' auto_increment,';
						$primaryKey  = $item->name;
						if(JString::strtolower($item->status) == 'changed')
						{
							$destinationField 	.= '`'.$item->name.'`, ';
						}
					}
					else 
					{
						if($item->default_value != '' && JString::strtolower($item->default_value) == 'null')
						{
							
							$defaultValue = ' '.$notNull.' default '.$item->default_value;
						}
						else 
						{
							$defaultValue = ' '.$notNull.' default '.'\''.$item->default_value.'\'';		
						}
						
						$query 				.= '`'.$item->name.'` '.$item->type.$defaultValue.',';
						if(JString::strtolower($item->status) == 'changed')
						{
							$destinationField 	.= '`'.$item->name.'`, ';
						}
					}
									
					if(JString::strtolower($item->status) == 'changed')
					{
						$resultSearch 		= $this->searchFieldChanged($dataChange, $item->id);
						$resultSearchAll 	= $this->searchAllFields($item->id, $tableName);
						if($resultSearch != false)
						{
							$sourceField 		.= '`'.$resultSearch.'`, ';
							$sourceDropField 	.= 'DROP `'.$resultSearch.'`, ';
						}
						elseif ($resultSearchAll != false)
						{
							$sourceField 		.= '`'.$resultSearchAll.'`, ';
							$sourceDropField 	.= 'DROP `'.$resultSearchAll.'`, ';
							
						}
					}
					
					if($flagSearchTable == false && JString::strtolower($item->status) == 'changed')
					{
						$sourceTableName = $this->searchTableByFieldID($item->id, $tableName);
						$flagSearchTable = true; 
					}
				}
				
				if($sourceTableName != '')
				{
					$queryDrop 	      = 'ALTER TABLE `'.$sourceTableName.'` '.substr($sourceDropField, 0, -2); 
				}
				
				$query 			 .= ' PRIMARY KEY (`'.$primaryKey.'`)';
				$query 			 .= ') TYPE=MyISAM CHARACTER SET `utf8`';
				if($sourceTableName != '')
				{
					$queryInsert 	  = 'INSERT `'.$tableName.'` ('; 
					$queryInsert 	 .= substr($destinationField, 0, -2); 
					$queryInsert 	 .= ') '; 
					$queryInsert 	 .= 'SELECT '.substr($sourceField, 0, -2).' FROM `'.$sourceTableName.'`';
				}
				$queries ['create'] [] = $query;
				$queries ['insert'] [] = $queryInsert;
				$queries ['alter'] []  = $queryDrop;
			}
		}
		return $queries;
	}

	function buildQueriesTableRemove($data)
	{
		$queries = array();
		if(count($data))
		{
			foreach ($data as $value)
			{
				if($this->_upgradeDBAction->isExistTable($value))
				{
					$queries [] = 'DROP TABLE `'.$value.'`';
				}
			}
		}
		
		return $queries;
	}
		
	function processDataChanged($data)
	{
		$tableResult = array();
		if(count($data))
		{
			foreach ($data as $key => $tables)
			{
				$tableName = $key;
				if(count($tables))
				{
					foreach ($tables as $fields)
					{
						$tmpArray = array();
						if(count($fields))
						{
							for ($i = 0, $count = count($fields); $i < $count; $i++)
							{
								$field = $fields[$i];
								if($field->version == $this->_previousVersion)
								{
									$originalFieldName = $field->name;
									break;
								}
								elseif ($this->_upgradeDBAction->isExistTableColumn($tableName, $field->name))
								{
									$originalFieldName = $field->name;
									break;
								}
								
							}
							
							$lastElement = end($fields);
							$tmpArray [$originalFieldName] = array('type' => $lastElement->type, 'change' => $lastElement->name, 'id' => $lastElement->id, 'not_null' => $lastElement->not_null); 
							$tableResult [$tableName][]   	   = $tmpArray;
						}
					}
				}
			}
		}
		
		return $tableResult;
	}
	
	function processDataAdded($data)
	{
		$tableResult = array();
		if(count($data))
		{
			foreach ($data as $key => $fields)
			{
				$tmpArray 	= array();
				$tableName 	= $key;
				if(count($fields))
				{
					for ($i = 0, $count = count($fields); $i < $count; $i++)
					{
						$field 						= $fields[$i];
						$tmpArray [$field->name] 	= array('type' => $field->type, 'default' => $field->default_value, 'id' => $field->id, 'not_null' => $field->not_null); 
					}
					$tableResult [$tableName] = $tmpArray;
				}
			}
		}
		return $tableResult;
	}

	function processDataRemoved($data)
	{
		$previousVersion = (float) $this->_previousVersion;
		$currentVersion  = (float) '3.0.0';
		$inclusiveFields = array();
		$tableResult 	 = array();
		if(count($data))
		{
			foreach ($data as $key => $fields)
			{
				$tmpArray 		= array();
				$tableName 		= $key;
				if(count($fields))
				{
					for ($i = 0, $count = count($fields); $i < $count; $i++)
					{
						$field	= $fields[$i];
						if (($previousVersion < $currentVersion) && $tableName == '#__imageshow_showcase')
						{
							$inclusiveFields [] = $field->name;
						}
						$tmpArray [$field->name] 	= array('id' => $field->id); 
					}
					
					$tableResult [$tableName] = $tmpArray;
				}
				
				if(($previousVersion < $currentVersion) && count($inclusiveFields) && $tableName == '#__imageshow_showcase')
				{
					$objISBackup =& JSNISBackup::getInstance();
					$arrayTable  = array('showcase'=>'#__imageshow_showcase');
					$objISBackup->writeXMLDataFile('jsn_is_showcase_backup.xml', $arrayTable, $inclusiveFields);
				}				
			}
		}
		
		return $tableResult;
	}

	function buildQueriesAdd($data)
	{
		$queries = array();
		
		if (count($data))
		{
			foreach ($data as $key => $fields)
			{			
				$table = $key;
				
				foreach ($fields as $subKey => $field)
				{
					if($field['not_null'] == 'yes')
					{
						$notNull = 'NULL';
					}
					else 
					{
						$notNull = 'NOT NULL';
					}
					
					if (!$this->_upgradeDBAction->isExistTableColumn($table, $subKey))
					{
						$query = 'ALTER TABLE `'.$table.'` ADD `'.$subKey.'` '.$field['type'].' '.$notNull.' DEFAULT';
						if($field['default'] != '' && JString::strtolower($field['default']) == 'null')
						{
							$queries []	= $query.' '.$field['default'];	
						}
						else 
						{
							$queries []	= $query.' \''.$field['default'].'\'';		
						}
					}					
				}			
			}	
		}
		return $queries;	
	}
	
	function buildQueriesRemove($data)
	{
		$queries = array();
		
		if (count($data))
		{
			foreach ($data as $key => $fields)
			{			
				$table = $key;
				
				foreach ($fields as $subKey => $field)
				{
					if ($this->_upgradeDBAction->isExistTableColumn($table, $subKey))
					{	
						$queries []	= 'ALTER TABLE `'.$table.'` DROP `'.$subKey.'`';
					}					
				}			
			}	
		}

		return $queries; 	
	}
	
	function buildQueriesChange($data)
	{
		$queries = array();
		if(count($data))
		{
			foreach ($data as $k1 => $fields)
			{
				$table = $k1;
				
				foreach ($fields as $k1 => $field)
				{
					foreach ($field as $k2 => $value)
					{
						if($value['not_null'] == 'yes')
						{
							$notNull = 'NULL';
						}
						else 
						{
							$notNull = 'NOT NULL';
						}
						if ($this->_upgradeDBAction->isExistTableColumn($table, $k2))
						{	
							$queries []	= 'ALTER TABLE `'.$table.'` CHANGE `'.$k2.'` `'.$value['change'].'` '.$value['type'].' '.$notNull;	
						}	
					}				
				}					
			}
		}
		return $queries;
	}
	
	function searchFieldChanged($data, $fieldID)
	{
		if(count($data))
		{
			foreach ($data as $k1 => $items)
			{
				$table = $k1;
				
				foreach ($items as $k1 => $item)
				{
					foreach ($item as $k2 => $value)
					{
						if($value['id'] == $fieldID)
						{
							return $value['change'];
						}	
					}				
				}					
			}
		}
		return false;
	}
	
	function searchAllFields($fieldID, $tableName = '')
	{
		$items  = $this->_items;	
		for ($i = 0, $count = count($items); $i < $count; $i++)
		{	
			$data     = $items[$i];	
			$tables   = $data->tables;
			
			if (count($tables))
			{
				for ($j = 0, $count1 = count($tables); $j < $count1; $j++)
				{
					$table  = $tables[$j];
					$fields = $table->fields;
					$name  	= $table->name;
					if (count($fields))
					{
						for ($z = 0, $count2 = count($fields); $z < $count2; $z++)
						{
							$obj = new stdClass();
							$field = $fields[$z];
							
							if($field->id == $fieldID && $name != $tableName)
							{
								return $field->name;
							}
						}
					}
				}
			}
		}
		return false;	
	}
	
	function searchTableByFieldID($fieldID, $tableName)
	{
		$items     = $this->_items;	
		$name 	   = '';
		for ($i = 0, $count = count($items); $i < $count; $i++)
		{	
			$data     = $items[$i];	
			$tables   = $data->tables;
			
			if (count($tables))
			{
				for ($j = 0, $count1 = count($tables); $j < $count1; $j++)
				{
					$table      = $tables[$j];
					$fields     = $table->fields;
					$name  		= $table->name;

					if (count($fields))
					{
						for ($z = 0, $count2 = count($fields); $z < $count2; $z++)
						{
							$field = $fields[$z];

							if($field->id == $fieldID && $name != $tableName)
							{
								return $name;
							}
						}
					}
				}
			}
		}
		
		return $name;	
	}		
}
?>