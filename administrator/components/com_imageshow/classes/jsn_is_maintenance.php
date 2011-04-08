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
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_upgradedbutil.php');
require_once( JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_showcasetheme.php');
class JSNISMaintenance
{
	var $_xml 			= null;
	var $_db  			= null;
	var $_xmlString 	= '';
	var $_header		= '';
	var $_tagRoot  		= '';
	var $_objectManifest = null;
	
	function JSNISMaintenance($tag)
	{
		$this->_setTagRoot($tag);
		$this->_setObjManifest();
		$this->_setHeader();
		$this->_xml = new JSimpleXMLElement($this->_tagRoot);
		$this->_setAttributteTagRoot();
		$this->_db  =& JFactory::getDBO();
	}

	function _setTagRoot($tag)
	{
		$this->_tagRoot = $tag;
	}
	
	function _setObjManifest()
	{
		$this->_objectManifest 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
	}
	
	function _setAttributteTagRoot()
	{
		$objConfig 		   	=& JFactory::getConfig();
		$database	   		= $objConfig->getValue('config.db');
		$manifestInfo 		= $this->_objectManifest->parserXMLDetails();
		$this->_xml->addAttribute('name', $database);
		$this->_xml->addAttribute('version', @$manifestInfo['version']);
	}
	
	function _setHeader()
	{
		$objConfig 		   	=& JFactory::getConfig();
		$database	   		= $objConfig->getValue('config.db');
		$this->_header  	= '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$this->_header 		.= '<!--'.'-'."\n" .'JSN ImageShow Backup File' . "\n" .
			                  '-'."\n" .
			                  '- Database: ' . $database . "\n" .
			                  '- Database Server: ' . $database . "\n" .
			                  '-'."\n" .
			                  '- Backup Date: ' . date("F j, Y, g:i a") . "\n\n".
			                  '-->';
	}
	
	function _renderTableData($tables, $root = true)
	{
		foreach ($tables as $tagName => $table)
		{
			$tableInfo 	 = $this->_db->getTableFields($table, false);
			$countField  = count($tableInfo[$table]);
			$fields		 = array();
			if(count($countField))
			{
				foreach ($tableInfo[$table] as $value)
				{
					$fields [] = $value->Field;	
				}
				$query  = 'SELECT ' . implode(',', $fields) . ' FROM ' .$table; 
				$this->_db->setQuery($query);
				$datas  = $this->_db->loadAssocList();
			
				if(count($datas))
				{
					if ($root)
					{
						$root = $this->_xml->addChild($tagName.'s');
					}
					else 
					{
						$root = $this->_xml;
					}
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
	
	function renderXMLData($showlist, $showcase)
	{
		$this->_xmlString  = $this->_header;		
		
		if ($showlist)
		{
			$this->_renderShowListData();
		}
		
		if ($showcase)
		{
			$this->_renderShowcaseData();
		}

		$this->_renderParameterData();
		$this->_renderThemeData();
		$this->_renderConfigurationData();
		$this->_xmlString .= $this->_xml->toString();
		return $this->_xmlString;
	}

	function _renderTableThemeData($tables)
	{
		$rootTheme		=  $this->_xml->addChild('themes');
		foreach ($tables as $tagName => $table)
		{
			$tableInfo 	 = $this->_db->getTableFields($table, false);
			$countField  = count($tableInfo[$table]);
			$fields		 = array();
			if(count($countField))
			{
				foreach ($tableInfo[$table] as $value)
				{
					$fields [] = $value->Field;	
				}
				$query  = 'SELECT ' . implode(',', $fields) . ' FROM ' .$table; 
				$this->_db->setQuery($query);
				$datas  = $this->_db->loadAssocList();
			
				if(count($datas))
				{
					$root = $rootTheme->addChild($tagName.'s');
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
			}
		}
	}

	function _renderThemeData()
	{
		$objJSNISShowcaseTheme  =& JSNISShowcaseTheme::getInstance();
		$themes 				= $objJSNISShowcaseTheme->listThemes(false);
		$themeTables 			= array();
		if (count($themes))
		{			
			foreach ($themes as $theme)
			{
				$name  = '#__imageshow_'.JString::str_ireplace('theme', 'theme_', $theme['element']);
				if ($this->_checkTableExist($name))
				{
					$themeTables[$theme['element']] = $name;
				}
			}
		}

		if (count($themeTables))
		{
			$this->_renderTableThemeData($themeTables);
		}
	}
	
	function _renderShowcaseData()
	{
		$table = array('showcase'=> '#__imageshow_showcase');
		$this->_renderTableData($table);
	}

	function _renderConfigurationData()
	{
		$table = array('configuration'=> '#__imageshow_configuration');
		$this->_renderTableData($table);
	}
	
	function _renderParameterData()
	{
		$table = array('parameter'=> '#__imageshow_parameters');
		$this->_renderTableData($table, false);
	}
	
	function _renderShowListData()
	{
		$tableInfo 	 = $this->_db->getTableFields('#__imageshow_showlist', false);
		$countField  = count($tableInfo['#__imageshow_showlist']);
		$fields		 = array();
		$showListID	 = 0;
		if(count($countField))
		{
			foreach ($tableInfo['#__imageshow_showlist'] as $value)
			{
				$fields [] = $value->Field;	
			}
			$query  = 'SELECT ' . implode(',', $fields) . ' FROM #__imageshow_showlist'; 
			
			$this->_db->setQuery($query);
			$datas  = $this->_db->loadAssocList();
		
			if(count($datas))
			{
				$root = $this->_xml->addChild('showlists');
				foreach ($datas as $data)
				{
					$subroot =& $root->addChild('showlist');
					reset($fields);					
					foreach ($fields as $fieldValue)
					{
						$subroot->addAttribute($fieldValue, $data[$fieldValue]);
						if ($fieldValue = 'showlist_id')
						{
							$showListID = $data[$fieldValue];
						}
					}
					$this->_renderImageData($showListID, $subroot);				
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
	
	function _renderImageData($showlistID, $root)
	{
		$tableInfo 	 = $this->_db->getTableFields('#__imageshow_images', false);
		$countField  = count($tableInfo['#__imageshow_images']);
		$fields		 = array();
		if(count($countField))
		{
			foreach ($tableInfo['#__imageshow_images'] as $value)
			{
				$fields [] = $value->Field;	
			}
			$query  = 'SELECT ' . implode(',', $fields) . ' FROM #__imageshow_images WHERE showlist_id = '. (int) $showlistID; 
			$this->_db->setQuery($query);
			$datas  = $this->_db->loadAssocList();
		
			if(count($datas))
			{
				foreach ($datas as $data)
				{
					$subroot =& $root->addChild('image');
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
	
	function _checkTableExist($table)
	{
		$objUpgradeDBAction = new JSNJSUpgradeDBAction();
		return $objUpgradeDBAction->isExistTable($table);
	}		
}