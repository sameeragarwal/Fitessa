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
class JSNISReadXmlDetails
{
	var $_fileDescriptName 	= 'com_imageshow.xml';
	
	function &getInstance()
	{
		static $instanceReadXML;
		if ($instanceReadXML == null)
		{
			$instanceReadXML = new JSNISReadXmlDetails();
		}
		return $instanceReadXML;
	}
	
	function parserXMLDetails()
	{
		$arrayResult 			= array();
		$arraylang 				= array();
		$temp 					= null;
		
		$fileDescription 			= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.$this->_fileDescriptName;
		$parserDescription 			= JFactory::getXMLParser('Simple');
		$resultLoadFileDescription  = $parserDescription->loadFile($fileDescription);
		$documentDescription 		=& $parserDescription->document;
		$nodeRealName				=& $documentDescription->getElementByPath('name');
		$nodVersion 			    =& $documentDescription->getElementByPath('version');
		$nodAuthor 			   		=& $documentDescription->getElementByPath('author');
		$nodDate 			        =& $documentDescription->getElementByPath('creationdate');
		$nodLicense			        =& $documentDescription->getElementByPath('license');
		$nodCopyright			    =& $documentDescription->getElementByPath('copyright');
		$nodWebsite 			    =& $documentDescription->getElementByPath('authorurl');
		$languages 			    	=& $documentDescription->getElementByPath('languages');
		$administration				=& $documentDescription->getElementByPath('administration');
		$nodEdition 				=& $documentDescription->getElementByPath('edition');
		$nodHashString				=& $documentDescription->getElementByPath('hashstring');
		
		if ($administration != false)
		{
			$submenu =& $administration->getElementByPath('submenu');
			if ($submenu != false)
			{
				$child = $submenu->children();
				
				if (count($child) > 0)
				{
					$arrayKey = array();
					foreach ($child as $value)
					{
						$keyValue = JString::strtoupper($value->data());
						$arrayKey [] = $keyValue;
					}
					$arrayResult['menu'] = $arrayKey;
				}
			}
		}
		
		if ($nodAuthor != false && $nodVersion != false && $nodDate!= false && $nodLicense!= false && $nodCopyright!= false && $nodWebsite!= false && $nodeRealName != false)
		{
			$arrayResult['realName'] 	= $nodeRealName->data();
			$arrayResult['version'] 	= $nodVersion->data();
			$arrayResult['author'] 		= $nodAuthor->data();
			$arrayResult['date'] 		= $nodDate->data();
			$arrayResult['license'] 	= $nodLicense->data();
			$arrayResult['copyright'] 	= $nodCopyright->data();
			$arrayResult['website'] 	= $nodWebsite->data();
			$arrayResult['edition'] 	= (($nodEdition!= false) ? $nodEdition->data() : '');
			$arrayResult['hashString']  = (($nodHashString != false ) ? $nodHashString->data() : '');
			if ($languages!=false && count($languages->children()))
			{
				foreach ($languages->children() as $value)
				{
					if ($temp != $value->attributes('tag'))
					{
						$tag 				= $value->attributes('tag');
						$arraylang [$tag] 	= $tag;
						$temp 				= $tag;
					}
				}
			}
			$arrayResult['langs'] = $arraylang;			
		}
		return $arrayResult;
	}
	
	function raiseError($error)
	{
		JError::raiseWarning(100,JText::_($error));
	}
	
	/*
	 * Paser xml file in package was downloaded
	 * $path path to xml file 
	 * 
	 */
	function parserExtXmlDetailsSampleData($path)
	{
		$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
		$hashString = $objJSNUtils->checkHashString();
		$config =& new JConfig();
		$dbprefix = $config->dbprefix;
		
		$db = &JFactory::getDBO();
		
		$query = "SHOW TABLES LIKE '".$dbprefix."imageshow_theme%'";
		$db->setQuery($query);
		$tableNameArray = $db->loadResultArray();		

		$xml 	=& JFactory::getXMLParser('Simple');
		$path 	= JPath::clean($path);	
		
		if (!$xml->loadFile($path)) 
		{
			$this->raiseError('Not found installation file in sample data package');
			return false;
		}
		
		$arrayObj = array();
		
		$document =& $xml->document;
		$obj = new stdClass();
		$attributes = &$document->attributes();
		
		if ($attributes)
		{
			if($attributes['name'] != 'imageshow' || $attributes['author'] != 'joomlashine' || $attributes['description'] != 'JSN ImageShow' )
			{
				$this->raiseError('XML STRUCTURE WAS EDITED');
				return false;
			}
			
			$obj->name 			= trim(strtolower($attributes['name']));
			$obj->version 		= (isset($attributes['version']) ? $attributes['version'] : '');
			$obj->author 		= (isset($attributes['author']) ? $attributes['author'] : '');
			$obj->description 	= (isset($attributes['description']) ? $attributes['description'] : '');
		}
		else
		{
			$this->raiseError('Incorrect file xml sample data package');
			return false;
		}	
				
		$arrayTruncate = array();
		$arrayInstall  = array();
		
		
		$tableTruncateExisted = 0;
		$tableInstallExisted  = 0;
					
		foreach ($document->children() as $task)
		{
			if ($task->_name != 'task')
			{
				$this->raiseError('XML STRUCTURE WAS EDITED');
				return false;
			}
			
			$attributesTask = $task->attributes();
			
			if (isset($attributesTask ["name"]) && $attributesTask ["name"] == 'dbtruncate')
			{ 
				foreach ($task->children() as $tables)
				{
					
					if ($tables->_name != 'tables')
					{
						$this->raiseError('XML STRUCTURE WAS EDITED');
						return false;
					}
					
					foreach ($tables->children() as $table)
					{
						
						if ($table->_name != 'table')
						{
							$this->raiseError('XML STRUCTURE WAS EDITED');
							return false;
						}
						
						$tableName = str_replace("#__",$dbprefix,$table->_attributes['name']);
						
						if (in_array($tableName, $tableNameArray)) 
						{
						   	$tableTruncateExisted ++ ;						    	
						}
						
						foreach ($table->children() as $parameters)
						{
							
							if ($parameters->_name != 'parameters')
							{
								$this->raiseError('XML STRUCTURE WAS EDITED');
								return false;
							}
							
							foreach ($parameters->children() as $parameter)
							{
								
								if ($parameter->_name != 'parameter')
								{
									$this->raiseError('XML STRUCTURE WAS EDITED');
									return false;
								}
								
								$arrayTruncate[] = trim($parameter->data());
							}
						}
					}
				}
			}			
		}
		
		if (isset($attributesTask ["name"]) && $attributesTask ["name"] == 'dbinstall')
		{ 
			foreach ($task->children() as $tables)
			{
				
				if ($tables->_name != 'tables')
				{
					$this->raiseError('XML STRUCTURE WAS EDITED');
					return false;
				}
				
				foreach($tables->children() as $table)
				{
					if ($table->_name != 'table')
					{
						$this->raiseError('XML STRUCTURE WAS EDITED');
						return false;
					}
					
					$tableName = str_replace("#__",$dbprefix,$table->_attributes['name']);
					
					if (in_array($tableName, $tableNameArray)) 
					{
					   	$tableInstallExisted ++ ;						    	
					}
					
					foreach($table->children() as $parameters)
					{
						if($parameters->_name != 'parameters')
						{
							$this->raiseError('XML STRUCTURE WAS EDITED');
							return false;
						}
						$countRecord = 0;
						foreach($parameters->children() as $parameter)
						{
							if($parameter->_name != 'parameter')
							{
								$this->raiseError('XML STRUCTURE WAS EDITED');
								return false;
							}
							
							if($countRecord == 2 && $hashString == false)// allow only two record
							{
								break;
							}
							
							$arrayInstall[] = trim($parameter->data());
							$countRecord++;
						}
					}
				}
			}
		}
		
		$obj->truncate = $arrayTruncate;
		$obj->install = $arrayInstall;
		
		$arrayObj [$attributes['name']] = $obj;	
		
		if (($tableInstallExisted==0)||($tableTruncateExisted==0)) 
		{
			$this->raiseError('XML STRUCTURE WAS EDITED');
			return false;						
		}	
		
		return $arrayObj;		
	}
}
?>