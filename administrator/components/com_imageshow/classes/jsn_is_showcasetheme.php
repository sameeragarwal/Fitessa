<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 3.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISShowcaseTheme
{
	var $_db 			= null;
	var $_pluginType 	= 'jsnimageshow';
	var $_pluginPrefix 	= 'theme';
	var $_installFolder	= 'install';
	var $_installFile	= 'install.mysql.sql';
	
	function JSNISShowcaseTheme()
	{
		if ($this->_db == null)
		{
			$this->_db =& JFactory::getDBO();
		}
	}
	
	function &getInstance()
	{
		static $instanceShowcaseTheme;
		if ($instanceShowcaseTheme == null)
		{
			$instanceShowcaseTheme = new JSNISShowcaseTheme();
		}
		return $instanceShowcaseTheme;
	}	
	
	function installPluginTableByThemeName($themeName)
	{
		jimport('joomla.filesystem.file');
		$sqlFile = JPATH_PLUGINS.DS.$this->_pluginType.DS.$themeName.DS.$this->_installFolder.DS.$this->_installFile;
		
		if (JFile::exists($sqlFile))
		{
			$buffer = file_get_contents($sqlFile);
			
			if ($buffer === false)
			{
				return false;
			}

			jimport('joomla.installer.helper');
			$queries = JInstallerHelper::splitSql($buffer);
			
			if (count($queries) == 0)
			{
				JError::raiseWarning(100, $sqlFile . JText::_(' not exits'));
				return 0;
			}

			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$this->_db->setQuery($query);
					
					if (!$this->_db->query()) 
					{
						JError::raiseWarning(100, 'JInstaller::install: '.JText::_('SQL Error')." ".$this->_db->stderr(true));
						return false;
					}
				}
			}
			
			return true;
		}
		else
		{
			JError::raiseWarning(100, $sqlFile . JText::_(' not exits'));
			return false;
		}	
	}
	
	function checkThemeTableInstallByThemeName($themeName)
	{
		if (!empty($themeName))
		{
			$themeName = str_replace($this->_pluginPrefix, '', trim(strtolower($themeName)));
		}
		
		$query 	= 'SHOW TABLES LIKE "'.$this->_db->getPrefix().'imageshow_theme_'.$themeName.'"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		
		if (!empty($result))
		{
			return true;
		}
		
		return false;
	}
	
	function checkThemePluginInstallByThemeName($themeName)
	{
		$query 	= 'SELECT id, name, element 
				   FROM #__plugins 
				   WHERE folder = \''.$this->_pluginType.'\' 
				   AND element='.$this->_db->quote($themeName);
		
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		
		if (!empty($result))
		{
			return true;
		}
		return false;
	}
	
	function listThemes($enabled = true)
	{
		$published	= '';
		
		if ($enabled)
		{
			$published = 'AND published = 1 ';
		}
		
		$query 	= 'SELECT id, name, element, published 
				   FROM #__plugins 
				   WHERE name LIKE "Theme%" '.$published.' 
				   AND folder = \''.$this->_pluginType.'\'';
		
		$this->_db->setQuery($query);
		return (array) $this->_db->loadAssocList();
	}
	
	function deleteThemeByPluginID($pluginID)
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		
		$pluginTable 	=& JTable::getInstance('plugin', 'JTable');
		
		if ($pluginTable->load((int) $pluginID))
		{
			$tableName = str_replace('theme', '', $pluginTable->element);
			
			$query = 'DELETE FROM #__imageshow_showcase 
					  WHERE theme_name = '.$this->_db->quote(trim(strtolower($pluginTable->element)));
			
			$this->_db->setQuery($query);
			$this->_db->query();
			
			$query = 'DROP TABLE IF EXISTS #__imageshow_theme_'.$tableName;
			$this->_db->setQuery($query);
			$this->_db->query();
			
			$pathPlugin 		= JPATH_PLUGINS.DS.$pluginTable->folder.DS.$pluginTable->element.'.php';
			$pathPluginXml 		= JPATH_PLUGINS.DS.$pluginTable->folder.DS.$pluginTable->element.'.xml';
			$pathPluginFolder 	= JPATH_PLUGINS.DS.$pluginTable->folder.DS.$pluginTable->element;
			
			$xml =& JFactory::getXMLParser('Simple');

			if (!$xml->loadFile($pathPluginXml)) {
				JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.JText::_('Could not load manifest file'));
			}

			if ($root =& $xml->document)
			{
				jimport('joomla.installer.installer');
				$installControl =& JInstaller::getInstance();
				$installControl->removeFiles($root->getElementByPath('languages'), 1);	
			}
			
			if (JFile::exists($pathPlugin))
			{
				JFile::delete($pathPlugin);
			}
			
			if (JFile::exists($pathPluginXml))
			{
				JFile::delete($pathPluginXml);
			}
			
			if (JFolder::exists($pathPluginFolder))
			{
				JFolder::delete($pathPluginFolder);
			}
			
			if(!$pluginTable->delete((int) $pluginID))
			{
				return false;
			}
		} 
		return true;
	}
	
	function enableThemeByThemeName($themeName, $themeType = 'jsnimageshow')
	{
		$query 	= 'UPDATE #__plugins 
				   SET published = 1 
				   WHERE folder ='.$this->_db->quote(trim(strtolower($themeType))).' 
				   AND element = '.$this->_db->quote(trim(strtolower($themeName)));
		
		$this->_db->setQuery($query);
		
		if ($this->_db->query())
		{
			return true;
		}
		
		return false;
	}

	function getThemeByID($tblName, $themeID)
	{
		$query 	= 'SELECT * FROM #__imageshow_'.$tblName.' WHERE theme_id = '. (int) $themeID;
		$this->_db->setQuery($query);
		return $this->_db->loadObject(); 
	}	
	
	function getShowcaseThemeByShowcaseID($showcaseID, $URL)
	{
		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
		$showcaseTable =& JTable::getInstance('showcase', 'Table');
		$showcaseTable->load((int) $showcaseID);
		
		if ($this->importModelByThemeName($showcaseTable->theme_name) == false)
		{
			return false;
		}
		
		$modelShowcaseTheme =& JModel::getInstance($showcaseTable->theme_name);
		
		if ($modelShowcaseTheme == false)
		{
			return false;
		}
		
		$data = $modelShowcaseTheme->_prepareDataJSON($showcaseID, $URL);
		
		return (object) $data;
	}
	
	function importTableByThemeName($themeName)
	{
		if (!empty($themeName))
		{
			$pathTableShowcaseTheme = JPATH_PLUGINS.DS.$this->_pluginType.DS.trim(strtolower($themeName)).DS.'tables';
			JTable::addIncludePath($pathTableShowcaseTheme);
			return true;
		}
		return false;
	}
	
	function importModelByThemeName($themeName)
	{
		if (!empty($themeName))
		{
			$pathModelShowcaseTheme = JPATH_PLUGINS.DS.$this->_pluginType.DS.trim(strtolower($themeName)).DS.'models';
			JModel::addIncludePath($pathModelShowcaseTheme);
			return true;
		}
		return false;
	}
	
	function getDefaultThemeByThemeName($themeName, $URL)
	{
		$showcaseID = 0; // default
		$this->importModelByThemeName($themeName);
		
		$modelShowcaseTheme =& JModel::getInstance($themeName);
		
		if ($modelShowcaseTheme == false)
		{
			return false;
		}
		
		$data = $modelShowcaseTheme->_prepareDataJSON($showcaseID, $URL);
		
		return (object) $data;
	}
	
	function loadThemeByName($themeName)
	{
		$resultCheckThemeTable = $this->checkThemeTableInstallByThemeName($themeName);		
		if (!$resultCheckThemeTable)
		{
			$resultInstallThemeTable = $this->installPluginTableByThemeName($themeName);	
		}
		JPluginHelper::importPlugin($this->_pluginType, $themeName);
		$dispatcher =& JDispatcher::getInstance();
		$arg 		= array($themeName);
		$plugins 	= $dispatcher->trigger('onLoadJSNShowcaseTheme', $arg);
		
		foreach ($plugins as $plugin)
		{
			if (gettype($plugin) == 'string')
			{
				echo $plugin;
			}	
		}
		
	}
	
	function checkThemeExist($themeName)
	{
		$themes 	= $this->listThemes(false);
		$countTheme	= count($themes);
		if ($countTheme)
		{
			foreach ($themes as $theme)
			{
				if($theme['element'] == $themeName)
				{
					return true;
				}
			}
		}
		return false;
	}
	
	function enableAllTheme()
	{
		$query 	= 'UPDATE #__plugins 
				   SET published = 1 
				   WHERE folder ='.$this->_db->quote($this->_pluginType).' AND name LIKE "Theme%"';
		$this->_db->setQuery($query);
		
		if ($this->_db->query())
		{
			return true;
		}
		
		return false;
	}	
}