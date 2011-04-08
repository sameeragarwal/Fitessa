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
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.file');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_upgradedbutil.php');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_showcasetheme.php');
class JSNISRestore
{
	var $path;
	var $file;
	var $compress;
	var $_document;
	var $_manifestInfo;
	var $_db;
	var $_fileRestore;
	function JSNISRestore($config = array('path'=> '', 'file'=>'', 'compress'=>'')) 
	{
		if (count($config) > 0)
		{
			$this->path 	= $config['path'];
			$this->file 	= $config['file'];
			$this->compress = $config['compress'];
		}
		
		$this->_db	=& JFactory::getDBO();
		$this->_setManifestInfo();
		
	}

	function &getInstance()
	{
		static $instanceRestore;
		if ($instanceRestore == null)
		{
			$instanceRestore = new JSNISRestore();
		}
		return $instanceRestore;
	}
	
	function _loadRestoreFile()
	{
		$fileStoreName	 	 = "jsn_".JString::strtolower(@$this->_manifestInfo['realName']).'_backup_db.xml';					
		$filepath	 		 = JPATH_ROOT.DS.'tmp'.DS.$fileStoreName;
		$this->_fileRestore  = $filepath;
		$parser				 =& JFactory::getXMLParser('Simple');
		$loadFile			 = $parser->loadFile($filepath);
		
		if (!$loadFile)
		{
			$fileStoreName	 	 = "jsn_".JString::strtolower(@$this->_manifestInfo['realName']).'_pro_backup_db.xml';	
			$filepath	 		 = JPATH_ROOT.DS.'tmp'.DS.$fileStoreName;
			$this->_fileRestore  = $filepath;
			if($parser->loadFile($filepath))
			{
				return $parser;
			}
			return false;
		}
		
		return $parser;
	}
	
	function _setDocument()
	{
	
		$loadFile = $this->_loadRestoreFile();
		
		if (!$loadFile)
		{
			$this->_document = null;		
		}
		
		$this->_document =& $loadFile->document;		
	}
	
	function _setManifestInfo()
	{
		$objJSNXML 				= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$this->_manifestInfo 	= $objJSNXML->parserXMLDetails();
	}
	
	function importFile()
	{
		$getFileUpload		= $this->upload();

		switch ($this->compress)
		{
			case 1:
			case 2:
				$extractdir 	= JPath::clean(dirname($getFileUpload));
				$archivename 	= JPath::clean($getFileUpload);
				$result 		= JArchive::extract($archivename, $extractdir);			
				break;
			case 0:
				break;
		}
	
		if (!$result) 
		{
			return false;
		} 
		else
		{
			$this->_setDocument();
			$attributeDocument  	= $this->_document->attributes();
			$versionRestore 		= (float) $attributeDocument['version'];
			$versionCheck			= (float) '2.3.0';
			$versionCheckMigrate	= (float) '3.0.0';
			if($versionRestore < $versionCheck) 
			{
				return 'outdated';
			}
			
			if($versionRestore < $versionCheckMigrate)
			{
				$queries [] = $this->_migrateShowcase();
			}
			else 
			{
				$queries []	= $this->_restoreShowcase();
				$queries []	= $this->_restoreShowcaseTheme();
			}
			$queries [] = $this->_restoreParameter();
			$queries []	= $this->_restoreShowlist();
			$queries []	= $this->_restoreConfiguration();

			$this->executeQuery($queries); 
			$arrayFileDelte = array($archivename, $this->_fileRestore);
			JFile::delete($arrayFileDelte);
			return true;		
		}
	}
	
	function executeQuery($datas)
	{
		if (count($datas))
		{
			foreach ($datas as $data)
			{
				if (count($data))
				{
					foreach ($data as $value)
					{
						$this->_db->setQuery($value);
						$this->_db->query();
					}
				}
			}
		}
	}
	
	function upload()
	{
		global $mainframe;
		$file = $this->file;
		
		$err  = null;
		if (isset($file['name'])) 
		{
			$filepath = JPath::clean($this->path.DS.$file['name']);						 			
			if (!JFile::upload($file['tmp_name'], $filepath)) 
			{
				header('HTTP/1.0 400 Bad Request');
				die('Error. Unable to upload file');
				return false;
			}
			else 
			{			
				return $filepath;
			}
		}
	}
		
	
	function restore($config)
	{
		$this->JSNISRestore($config);
		$result = $this->importFile();
		return $result;
	}
	
	function _restoreShowcase()
	{
		$queries 		= array();
		$checkShowCase 	=& $this->_document->getElementByPath('showcases');
		
		if(!$checkShowCase) return $queries;
	
		$showcaseRoot =& $this->_document->showcases;				
		if ($showcaseRoot != null)
		{
			$showcase = @$showcaseRoot[0]->showcase;
			if (count($showcase))
			{
				for ($i = 0; $i < count($showcase); $i++)
				{
					$rows 			= $showcase[$i];				
					$attributes [] 	= $rows->attributes();					
				}
				if (count($attributes))
				{		
					$queries [] = 'TRUNCATE #__imageshow_showcase';	
					$queries [] = 'ALTER TABLE #__imageshow_showcase AUTO_INCREMENT = 1';
					foreach ($attributes as $attribute)
					{
						$fields 		= '';
						$fieldsValue 	= '';						
						foreach ($attribute as $key => $value)
						{
							$fields 	 .= $key.',';
							$fieldsValue .= $this->_db->quote($value).',';
						}			
						$queries [] = 'INSERT #__imageshow_showcase ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
					}		
				}				
			}				
		}
				
		return $queries;
	}
	
	function _restoreConfiguration()
	{
		$queries 				= array();
		$checkConfiguration 	=& $this->_document->getElementByPath('configurations');
		
		if(!$checkConfiguration) return $queries;

		$configurationRoot =& $this->_document->configurations;				
		if ($configurationRoot != null)
		{
			$configuration = @$configurationRoot[0]->configuration;
			
			if (count($configuration))
			{
				for ($i = 0; $i < count($configuration); $i++)
				{
					$rows 				= $configuration[$i];				
					$attributes [] 		= $rows->attributes();
				}
				if (count($attributes))
				{		
					$queries [] = 'TRUNCATE #__imageshow_configuration';	
					$queries [] = 'ALTER TABLE #__imageshow_configuration AUTO_INCREMENT = 1';
					foreach ($attributes as $attribute)
					{
						$fields			= '';
						$fieldsValue 	= '';						
						foreach ($attribute as $key => $value)
						{
							$fields 	 .= $key.',';
							$fieldsValue .= $this->_db->quote($value).',';
						}			
						$queries [] = 'INSERT #__imageshow_configuration ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
					}		
				}				
			}				
		}
			
		return $queries;
	}
	
	function _restoreParameter()
	{
		$queries 		= array();
		$checkParameter =& $this->_document->getElementByPath('parameter');
		
		if(!$checkParameter) return $queries;
		
		$parameter =& $this->_document->parameter;
		if ($parameter != null)
		{
			$attributes [] = $parameter[0]->attributes();
			if(count($attributes))
			{
				$queries [] = 'TRUNCATE #__imageshow_parameters';	
				$queries [] = 'ALTER TABLE #__imageshow_parameters AUTO_INCREMENT = 1';
				foreach ($attributes as $attribute)
				{
					$fields			= '';
					$fieldsValue 	= '';
					
					foreach($attribute as $key => $value)
					{
						$fieldsValue 	.= $this->_db->quote($value).',';
						$fields 		.= $key.',';
					}
					
					$queries [] = 'INSERT #__imageshow_parameters ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
				}						
			}
		}
		return $queries;	
	}
	
	function _restoreShowlist()
	{
		$queries 	 	= array();
		$checkShowList 	=& $this->_document->getElementByPath('showlists');
		
		if(!$checkShowList) return $queries;	

		$showlists =& $this->_document->showlists;	
		$showlist  = @$showlists[0]->showlist;
		if (count($showlist))
		{
			for ($i = 0; $i < count($showlist); $i ++ )
			{
				$rows 		= $showlist[$i];				
				$attributes [] = $rows->attributes();
				$images =& $rows->image;					
				if(count($images) > 0)
				{
					for ($y = 0 ; $y < count($images); $y++)
					{
						$image 				= $images[$y];	
						$attributesImage [] = $image->attributes();
					}
				}														
			}
			
			if(count($attributes))
			{	
				$queries [] = 'TRUNCATE #__imageshow_showlist';	
				$queries [] = 'ALTER TABLE #__imageshow_showlist AUTO_INCREMENT = 1';
				foreach ($attributes as $attribute)
				{
					$fields 		= '';
					$fieldsValue 	= '';						
					foreach($attribute as $key => $value)
					{					
						$fields 		.= $key.',';
						$fieldsValue 	.= $this->_db->quote($value).',';
					}
					
					$queries [] = 'INSERT #__imageshow_showlist ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
				}
			}

			if(count($attributesImage))
			{
				$queries [] = 'TRUNCATE #__imageshow_images';	
				$queries [] = 'ALTER TABLE #__imageshow_images AUTO_INCREMENT = 1';
				foreach ($attributesImage as $attributeImage)
				{
					$fields 		= '';
					$fieldsValue 	= '';
					foreach($attributeImage as $key => $value)
					{
						if($key == 'synchronize')
						{
							$key  = 'custom_data';
						}

						$fields 		.= $key.',';
						$fieldsValue 	.= $this->_db->quote($value).',';
					}
					
					$queries [] = 'INSERT #__imageshow_images ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
				}	
			}			
		}
		
		return $queries;		
	}
	
	function _getShowcaseTheme()
	{
		$objJSNISShowcaseTheme  =& JSNISShowcaseTheme::getInstance();
		$themes 				= $objJSNISShowcaseTheme->listThemes(false);
		$results		 		= array();
		if (count($themes))
		{			
			foreach ($themes as $theme)
			{
				$results [] = $theme['element'];
			}
		}

		return $results;
	}
	
	function _restoreShowcaseTheme()
	{
		$queries		= array();
		$checkThemes 	=& $this->_document->getElementByPath('themes');
		
		if(!$checkThemes) return $queries;	
			
		$themesRoot	   =& $this->_document->themes;		
		$themes 		= $this->_getShowcaseTheme();
		
		if (count($themes))
		{
			foreach ($themes as $theme)
			{
				$themeName	= JString::str_ireplace('theme', 'theme_', $theme);
				$queries [] = 'TRUNCATE #__imageshow_'.$themeName;	
				$queries [] = 'ALTER TABLE #__imageshow_'.$themeName.' AUTO_INCREMENT = 1';
				
				$attributes = array();

				$check 	=& $themesRoot[0]->getElementByPath($theme.'s');
				if ($check != false)	
				{
					$root =& $themesRoot[0]->{$theme.'s'};				
					if ($root != null)
					{
						$subRoot = @$root[0]->{$theme};
						
						if (count($subRoot))
						{
							for ($i = 0; $i < count($subRoot); $i++)
							{
								$rows 				= $subRoot[$i];				
								$attributes [] 		= $rows->attributes();
							}
							if (count($attributes))
							{		
								foreach ($attributes as $attribute)
								{
									$fields			= '';
									$fieldsValue 	= '';						
									foreach ($attribute as $key => $value)
									{
										$fields 	 .= $key.',';
										$fieldsValue .= $this->_db->quote($value).',';
									}			
									$queries [] = 'INSERT #__imageshow_'.$themeName.' ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
								}		
							}				
						}				
					}
				}
			
			}
		}
		return $queries;
	}
	
	function _getTableFields($table)
	{
		$fields			= array();
		$tableInfo 		= $this->_db->getTableFields($table, true);
		$countFields 	= count($tableInfo[$table]);
		if($countFields > 0)
		{
			foreach ($tableInfo[$table] as $key =>$value)
			{			
				$fields [] = $key;		
			}
		}
		return $fields;
	}
	
	function _migrateShowcase()
	{
		$index				= 1;
		$fieldsComparer	    = $this->_getTableFields('#__imageshow_showcase');
		$queries 			= array();
		$checkShowCase 		=& $this->_document->getElementByPath('showcases');
		$checkTableTheme 	= $this->_checkTableExist('#__imageshow_theme_classic');
		if ($checkShowCase != false)	
		{
			$showcaseRoot =& $this->_document->showcases;				
			if ($showcaseRoot != null)
			{
				$showcase = @$showcaseRoot[0]->showcase;
				if (count($showcase))
				{
					for ($i = 0; $i < count($showcase); $i++)
					{
						$rows 			= $showcase[$i];				
						$attributes [] 	= $rows->attributes();					
					}
					if (count($attributes))
					{		
						$queries [] = 'TRUNCATE #__imageshow_showcase';	
						$queries [] = 'ALTER TABLE #__imageshow_showcase AUTO_INCREMENT = 1';
						if ($checkTableTheme)
						{
							$queries [] = 'TRUNCATE #__imageshow_theme_classic';	
							$queries [] = 'ALTER TABLE #__imageshow_theme_classic AUTO_INCREMENT = 1';
						}					
						foreach ($attributes as $attribute)
						{
							$fields 			= 'theme_id,theme_name,';
							$fieldsValue 		= "'".$index."','themeclassic',";	
							$fieldsTheme		= '';
							$fieldsThemeValue	= '';				
							foreach ($attribute as $key => $value)
							{
						
								if (in_array($key, $fieldsComparer))
								{
									if($key == 'slideshow_show_timer')
									{
										$key  = 'slideshow_show_status';
									}
									
									$fields 	 .= $key.',';
									$fieldsValue .= $this->_db->quote($value).',';
								}
								else 
								{
									if($key == 'slideshow_show_timer')
									{
										$key  = 'slideshow_show_status';
									}
									
									$fieldsTheme	 	.= $key.',';
									$fieldsThemeValue 	.= $this->_db->quote($value).',';									
								}
							}
							 			
							$queries [] = 'INSERT #__imageshow_showcase ('.substr($fields, 0, -1).') VALUES ('.substr($fieldsValue, 0, -1).')';
							 
							if ($checkTableTheme)
							{
								$queries [] = 'INSERT #__imageshow_theme_classic ('.substr($fieldsTheme, 0, -1).') VALUES ('.substr($fieldsThemeValue, 0, -1).')';
							}
							
							$index++;
						}		
					}				
				}				
			}
		}
		return $queries;
	}

	function _checkTableExist($table)
	{
		$objUpgradeDBAction = new JSNJSUpgradeDBAction();
		return $objUpgradeDBAction->isExistTable($table);
	}	
}
