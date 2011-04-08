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
jimport('joomla.filesystem.file');
jimport('joomla.utilities.simplexml');

class JSNISBackup
{
	var $_xml 		= null;
	var $_db  		= null;
	var $_xmlString = '';
	
	function &getInstance()
	{
		static $instanceJSNISBackup;
		
		if ($instanceJSNISBackup == null)
		{
			$instanceJSNISBackup = new JSNISBackup();
		}
			
		return $instanceJSNISBackup;
	}
	
	function JSNISBackup()
	{
		$this->_xml = new JSimpleXMLElement('database');
		$this->_db  =& JFactory::getDBO();
	}
	
	function _getTableData($table)
	{
		$tableInfo 	 = $this->_db->getTableFields($table, false);
		$countField  = count($tableInfo[$table]);
		$fields		 = array();
		if(count($countField))
		{
			foreach ($tableInfo[$table] as $key =>$value)
			{
				$fields [] = $value->Field;		
			}
			$query = 'SELECT ' . implode(',', $fields) . ' FROM ' .$table; 
			$this->_db->setQuery($query);
			return $this->_db->loadAssocList();						
		}
		return array();
	}
	
	function _getTableSchema($tables)
	{
		$tableInfo = array();
		foreach ($tables as $value)
		{
			$tableInfo []	 = $this->_db->getTableFields($value, false);
		}
		return $tableInfo;
	}
	
	function _renderTableStructure($tables)
	{
		$tableSchema 	 = $this->_getTableSchema($tables);
		$rootTables 	 =& $this->_xml->addChild('tables');
		
		foreach ($tableSchema as $key => $value)
		{
			$rootTable  =& $rootTables->addChild('table');
			
			foreach ($value as $keyTable => $tables)
			{
				$rootTable->addAttribute('name', $keyTable);
				foreach ($tables as $keyField => $fileds)
				{
					$rootField  =& $rootTable->addChild('field');
					$rootField->addAttribute('name', $fileds->Field);
					$rootField->addAttribute('type', $fileds->Type);
					$rootField->addAttribute('defaul_value', $fileds->Default);
					if($fileds->Key == 'PRI')
					{
						$rootField->addAttribute('primary_key', 'yes');
					}					
				}
			}
		}
	}
	
	function _renderTableData($tables, $inclusiveFields = array())
	{
		$countInclusiveFields = count($inclusiveFields);
		foreach ($tables as $tagName => $table)
		{
			$tableInfo 	 = $this->_db->getTableFields($table, false);
			$countField  = count($tableInfo[$table]);
			$fields		 = array();
			if(count($countField))
			{
				foreach ($tableInfo[$table] as $value)
				{
					if($countInclusiveFields)
					{
						$addElement 	 = array('showcase_id');
						$inclusiveFields = array_merge($inclusiveFields, $addElement);
						
						if(in_array($value->Field, $inclusiveFields))
						{
							$fields [] = $value->Field;	
						}	
					}
					else 
					{
						$fields [] = $value->Field;	
					}
				}
				$query  = 'SELECT ' . implode(',', $fields) . ' FROM ' .$table; 
				$this->_db->setQuery($query);
				$datas  = $this->_db->loadAssocList();
			
				if(count($datas))
				{
					$root = $this->_xml->addChild($tagName.'s');
					foreach ($datas as $data)
					{
						$subroot =& $root->addChild($tagName);
						reset($fields);					
	
						foreach ($fields as $fieldValue)
						{
							$subroot->addAttribute($fieldValue, $data[$fieldValue]);
						}					
					}
				}
				else 
				{
					return false;
				}
									
			}
			else 
			{
				return false;
			}
		}
	}
	
	function renderXMLData($tables, $inclusiveFields = array())
	{
		$objConfig 		   =& JFactory::getConfig();
		$databaseName	   = $objConfig->getValue('config.db');
		$this->_xmlString  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$this->_xmlString .= '<!--'.'-'."\n" .'JSN ImageShow Backup File' . "\n" .
                  '-'."\n" .
                  '- Database: ' . $databaseName . "\n" .
                  '- Database Server: ' . $databaseName . "\n" .
                  '-'."\n" .
                  '- Backup Date: ' . date("F j, Y, g:i a") . "\n\n".
                  '-->';		
		$this->_renderTableStructure($tables);
		$this->_renderTableData($tables, $inclusiveFields);
		$this->_xmlString .= $this->_xml->toString();
	}
	
	function writeXMLDataFile($fileName, $tables, $inclusiveFields = array())
	{
		$checkRecordShowcase = $this->checkRecordShowcase();
		if($checkRecordShowcase)
		{
			$this->renderXMLData($tables, $inclusiveFields);
			if (!JFile::write(JPATH_ROOT.DS.'tmp'.DS.$fileName, $this->_xmlString))
			{
				return false;
			}
		}
		return true;		
	}
	
	function checkRecordShowcase()
	{
		$db 	=& JFactory::getDBO();
		$query 	= 'SELECT COUNT(showcase_id) FROM #__imageshow_showcase';
		$db->setQuery($query);
		$result    =  $db->loadRow();
		if(count($result))
		{
			if($result[0] != 0)
			{
				return true;
			}
		}
		return false;		
	}
}