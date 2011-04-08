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
jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');
jimport('joomla.installer.helper');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_installer.php');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_upgradedbutil.php');
class ImageShowModelInstaller extends JModel 
{
	function __construct()
	{
		parent::__construct();
	}
	
	function executeQuery($queies)
	{		
		if (count($queies))
		{
			foreach ($queies as $value)
			{
				$this->_db->setQuery($value);
				if (!$this->_db->query())
				{
					return false;
				}
			}
		}
		return true;
	}
		
	function install()
	{
		global $mainframe;
		switch(JRequest::getWord('installtype'))
		{
			case 'upload':
				$package = $this->_getPackageFromUpload();
				break;
			default:
				$this->setState('message', 'No Install Type Found');
				return false;
				break;
		}


		if (!$package) 
		{
			$this->setState('message', 'Unable to find install package');
			return false;
		}

		$installer =& JSNISInstaller::getInstance();

		if (!$installer->install($package['dir'])) 
		{
			//$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Error'));
			$result = false;
		} 
		else
		{
			//$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Success'));
			$msg = JText::_('Theme package successfully installed');
			$result = true;
		}

		$mainframe->enqueueMessage(@$msg);
		$this->setState('name', $installer->get('name'));
		$this->setState('result', $result);
		$this->setState('message', $installer->message);
		$this->setState('extension.message', $installer->get('extension.message'));

		if (!is_file($package['packagefile'])) 
		{
			$config =& JFactory::getConfig();
			$package['packagefile'] = $config->getValue('config.tmp_path').DS.$package['packagefile'];
		}
		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
		return $result;
	}

	function _getPackageFromUpload()
	{
		$userfile = JRequest::getVar('install_package', null, 'files', 'array');
		if (!(bool) ini_get('file_uploads')) 
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLFILE'));
			return false;
		}
		if (!extension_loaded('zlib')) 
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLZLIB'));
			return false;
		}
		if (!is_array($userfile)) 
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('No file selected'));
			return false;
		}

		if ($userfile['error'] || $userfile['size'] < 1)
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLUPLOADERROR'));
			return false;
		}

		$config 	=& JFactory::getConfig();
		$tmpDEST 	= $config->getValue('config.tmp_path').DS.$userfile['name'];
		$tmpSRC		= $userfile['tmp_name'];

		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmpSRC, $tmpDEST);

		$package = JInstallerHelper::unpack($tmpDEST);

		return $package;
	}

	function checkThemePlugin()
	{
		$query = 'SELECT COUNT(id) FROM #__plugins WHERE folder="jsnimageshow" AND name LIKE "Theme%"';
    	$this->_db->setQuery($query);
		$result    =  $this->_db->loadRow();
		if(count($result))
		{
		    if($result[0] != 0)
    		{
    		    return true;
    		}
        }
        return false;		
	}
	
	function checkBackupFile($fileName)
	{	
		if (JFile::exists(JPATH_ROOT.DS.'tmp'.DS.$fileName))
		{
			return true;
		}
		return false;
	}
	
	function checkTableExist($tableName)
	{
		$objUpgradeDBAction = new JSNJSUpgradeDBAction();
		return $objUpgradeDBAction->isExistTable($tableName);
	}
	
	function installShowcaseThemeClassic()
	{
		$pathInstallFile = (JPATH_PLUGINS.DS.'jsnimageshow'.DS.'themeclassic'.DS.'install');
		
		if(JFolder::exists($pathInstallFile))
		{
			if (JFile::exists($pathInstallFile.DS.'install.plugin.sql')) 
			{
				$buffer = file_get_contents($pathInstallFile.DS.'install.plugin.sql');
				if ($buffer !== false) 
				{
					jimport('joomla.installer.helper');
					$queries = JInstallerHelper::splitSql($buffer);
					if (count($queries)) 
					{
						foreach ($queries as $query)
						{
							$query = trim($query);
							if ($query != '' && $query{0} != '#') 
							{
								$this->_db->setQuery($query);
								if (!$this->_db->query()) 
								{
									return false;
								}
							}
						}
						return true;
					}
				}
			}
		}
		return false;		
	}
	
	function executeRestoreShowcaseThemeClassicData()
	{
		$database		=& JFactory::getDBO();
		$filePath 		= JPATH_ROOT.DS.'tmp'.DS.'jsn_is_showcase_backup.xml';	
		$parser 		=& JFactory::getXMLParser('Simple');
		$loadFile 		= $parser->loadFile($filePath);	
		$document 		=& $parser->document;	
		$showcases		= array();
		$inserts		= array();
		$updates		= array();
		$index			= 1;
		$arrayMapping 	= array();

		if (!$loadFile)
		{
			return false;
		}
		
		$checkShowCase =& $document->getElementByPath('showcases');
		if ($checkShowCase != false)	
		{
			$showcaseRoot =& $document->showcases;				
			if ($showcaseRoot != null)
			{
				$showcase = @$showcaseRoot[0]->showcase;
				if (count($showcase))
				{
					for ($i = 0; $i < count($showcase); $i++)
					{
						$rows 			= $showcase[$i];				
						$attributes 	= $rows->attributes();
						$showcases [] 	= $attributes;
						
					}
					if (count($showcases))
					{		
						foreach ($showcases as $showcase)
						{
							$fieldsShowCase 		= '';
							$fieldsShowCaseValue 	= '';						
							foreach ($showcase as $key => $value)
							{
								if($key == 'showcase_id') 
								{
									$arrayMapping [$value] 	= $index;
									$key 					= 'theme_id';
									$value 					= $index;
								}
					
								$fieldsShowCase 	 .= $key.',';
								$fieldsShowCaseValue .= $database->quote($value).',';
							}			
							$inserts [] = 'INSERT #__imageshow_theme_classic ('.substr($fieldsShowCase, 0, -1).') VALUES ('.substr($fieldsShowCaseValue, 0, -1).')';
							$index++;
						}		
					}				
				}								
			}
		}
		
		if (count($arrayMapping))
		{
			foreach($arrayMapping as $key => $value)
			{
				$updates [] = 'UPDATE #__imageshow_showcase SET theme_name="themeclassic", theme_id='.$database->quote($value).' WHERE showcase_id='.$database->quote($key);
			}
		}
		
		$queries = array_merge($inserts, $updates);
		
		if (count($queries))
		{
			$result = $this->executeQuery($queries);
			return $result;	
		}
		return false;	
	}
	
	
	function removeFile($path)
	{
		if (JFile::exists($path))
		{
			JFile::delete($path);
		}		
	}

}
