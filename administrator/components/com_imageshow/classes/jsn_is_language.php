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
jimport('joomla.filesystem.folder');
class JSNISLanguage 
{
	var $_lang = array();
	
	function JSNISLanguage()
	{
		$this->setLang();
	}
	
	function &getInstance()
	{
		static $instanceLang;
		if ($instancelang == null)
		{
			$instanceLang = new JSNISLanguage();
		}
		return $instanceLang;
	}
	
	function setLang()
	{			
		$objectReadxmlDetail 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();	
		$this->_lang 			= $infoXmlDetail['langs'];
	}	
	
	function getFolder($base)
	{
		$folders 		= JFolder::folders($base, '.', false, true);
		$arrayFolder 	= array();
		
		foreach ($folders as $folder)
		{
			if (basename($folder) != 'pdf_fonts')
			{
				$arrayFolder[basename($folder)] = basename($folder);
			}
		}
		return $arrayFolder;
	}
	
	function _getFolderFO()
	{
		$filepath 		= JPATH_ROOT.DS.'language';
		$arrayFolders 	= $this->getFolder($filepath);
		$arrayMerge		= array_merge($arrayFolders, $this->_lang);
		$newArray		= array();
		
		foreach ($arrayMerge as $key => $value)
		{
			$newVal = $this->_checkAllInsLangs($value, 'site');
			$newArray [$key] = $newVal;
		}
		
		ksort($newArray);
		return $newArray;		
	}
	
	function _getFolderBO()
	{
		$filepath 		= JPATH_ROOT.DS.'administrator'.DS.'language';
		$arrayFolders 	= $this->getFolder($filepath);
		$arrayMerge		= array_merge($arrayFolders, $this->_lang);
		$newArray		= array();
		
		foreach ($arrayMerge as $key => $value)
		{
			$newVal = $this->_checkAllInsLangs($value, 'admin');
			$newArray [$key] = $newVal;
		}
		
		ksort($newArray);
		return $newArray;
	}
	function megerArrayFolder()
	{		
		$arrayMergeFO 	= array();
		$arrayMerge 	= array();
		
		$arrayFO = $this->_getFolderFO();
		$arrayBO = $this->_getFolderBO();
		
		foreach ($arrayFO as $key=>$value)
		{
			$arrayMerge[$key]['site'] = $value;
		}
		
		foreach ($arrayBO as $key=>$value)
		{
			$arrayMerge[$key]['admin'] = $value;
		}
		
		ksort($arrayMerge);
		return $arrayMerge;
	}
	
	function _arrayDiffKey()
	{
		$arrs = func_get_args();
		$result = array_shift($arrs);
		
		foreach ($arrs as $array) 
		{
			foreach ($result as $key => $v) 
			{
				if (array_key_exists($key, $array)) 
				{
					unset($result[$key]);
				}
			}
		}
		return $result;
	}

	function _checkFolderExist($folder, $position)
	{
		$filepathBO = JPATH_ROOT.DS.'administrator'.DS.'language'.DS.$folder;
		$filepathFO = JPATH_ROOT.DS.'language'.DS.$folder;
		
		if ($position == 'admin')
		{
			if (!JFolder::exists($filepathBO))
			{
				return false;
			}			
		}
		
		if ($position == 'site')
		{
			if (!JFolder::exists($filepathFO))
			{
				return false;
			}			
		}
		return true;
	}
	
	function _checkFolderPermission($folder, $position)
	{
		$filepathBO = JPATH_ROOT.DS.'administrator'.DS.'language'.DS.$folder;
		$filepathFO = JPATH_ROOT.DS.'language'.DS.$folder;
		
		if ($position == 'admin')
		{
			if(is_writable($filepathBO))
			{
				return true;
			}			
		}
		
		if ($position == 'site')
		{
			if (is_writable($filepathFO))
			{
				return true;
			}			
		}
		return false;				
	}
	
	function _checkFileExist($name, $position)
	{
		$filepathBO = JPATH_ROOT.DS.'administrator'.DS.'language'.DS.$name;
		$filepathFO = JPATH_ROOT.DS.'language'.DS.$name;
		
		if ($position == 'admin')
		{
			$plgContent = $filepathBO.DS.$name.'.plg_content_imageshow.ini';
			$plgSystem  = $filepathBO.DS.$name.'.plg_system_imageshow.ini';			
			$com 		= $filepathBO.DS.$name.'.com_imageshow.ini';
			$comMenu 	= $filepathBO.DS.$name.'.com_imageshow.menu.ini';
			
			if (JFile::exists($plgContent) || JFile::exists($plgSystem) || JFile::exists($com) || JFile::exists($comMenu))
			{
				return true;
			}			
		}
		
		if ($position == 'site')
		{
			$plgContent = $filepathFO.DS.$name.'.plg_content_imageshow.ini';
			$mod 		= $filepathFO.DS.$name.'.mod_imageshow.ini';
			$com 		= $filepathFO.DS.$name.'.com_imageshow.ini';
			
			if (JFile::exists($plgContent) || JFile::exists($mod) || JFile::exists($com))
			{
				return true;
			}
		}
		return false;
	}
	
	function _checkLangSupport($name)
	{
		if (array_key_exists($name, $this->_lang))
		{
			return true;
		}
		
		return false;
	}
	
	function _checkAllInsLangs($value, $postion)
	{
		$checkFolderExist 		= $this->_checkFolderExist($value, $postion);
		$checkFolderPermission 	= $this->_checkFolderPermission($value, $postion);
		$checkFileExist			= $this->_checkFileExist($value, $postion);
		
		if ($checkFolderExist == false)
		{
			$newVal = 1; // folder not exist
		}
		elseif ($checkFolderPermission == false)
		{
			$newVal = 2; // not writable
		}
		elseif ($checkFileExist == true)
		{
			$newVal = 3; // existed
		}
		elseif ($this->_checkLangSupport($value) == false)
		{
			$newVal = 4; // not support;
		}
		else 
		{
			$newVal = 5; // file not exist;
		}
		
		return $newVal;	
	}
	
	function installationFolderLangBO($arrayFolder)
	{
		foreach ($arrayFolder as $key => $value)
		{
			$this->_copyComLang('admin', $value);
			//$this->_copyModuleLang('admin', $value);
			$this->_copyPluginLang('admin', $value);
		}
		return true;
	}
	
	function installationFolderLangFO($arrayFolder)
	{	
		foreach ($arrayFolder as $key => $value)
		{
			$this->_copyComLang('site', $value);
			$this->_copyModuleLang('site', $value);
			$this->_copyPluginLang('site', $value);
		}
		
		return true;
	}
	function _copyPluginLang($position, $lang)
	{
		$filepath 	= JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_imageshow'.DS.'languages';
		
		if ($position == 'admin')
		{			
			$filepathSys 	= $filepath.DS.'admin'.DS.$lang.'.plg_system_imageshow.ini';
			$destSys 		= JPATH_ROOT.DS.'administrator'.DS.'language'.DS.$lang.DS.$lang.'.plg_system_imageshow.ini';
			$dest 			= JPATH_ROOT.DS.'administrator'.DS.'language'.DS.$lang.DS.$lang.'.plg_content_imageshow.ini';			
			$filepath 		.= DS.'admin'.DS.$lang.'.plg_content_imageshow.ini';			
			JFile::copy($filepath, $dest);
			JFile::copy($filepathSys, $destSys);
		}
		else
		{
			$dest = JPATH_ROOT.DS.'language'.DS.$lang.DS.$lang.'.plg_content_imageshow.ini';					
			$filepath .= DS.'site'.DS.$lang.'.plg_content_imageshow.ini';			
			JFile::copy($filepath, $dest);		
		}
	}
	
	function _copyModuleLang($position, $lang)
	{
		$filepath 	= JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_imageshow'.DS.'languages';
		
		if ($position == 'admin'){
		}
		else
		{
			$dest 		= JPATH_ROOT.DS.'language'.DS.$lang.DS.$lang.'.mod_imageshow.ini';			
			$filepath .= DS.'site'.DS.$lang.'.mod_imageshow.ini';			
			JFile::copy($filepath, $dest);			
		}		
	}
	
	function _copyComLang($position, $lang)
	{
		$filepath 	= JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_imageshow'.DS.'languages';
		
		if ($position == 'admin')
		{
			$destMenu 		= JPATH_ROOT.DS.'administrator'.DS.'language'.DS.$lang.DS.$lang.'.com_imageshow.menu.ini';
			$filepathMenu 	= $filepath.DS.'admin'.DS.$lang.'.com_imageshow.menu.ini';
			$dest 			= JPATH_ROOT.DS.'administrator'.DS.'language'.DS.$lang.DS.$lang.'.com_imageshow.ini';	
			$filepath 		.= DS.'admin'.DS.$lang.'.com_imageshow.ini';
			JFile::copy($filepath, $dest);
			JFile::copy($filepathMenu, $destMenu);		
		}
		else
		{
			$dest = JPATH_ROOT.DS.'language'.DS.$lang.DS.$lang.'.com_imageshow.ini';			
			$filepath .= DS.'site'.DS.$lang.'.com_imageshow.ini';			
			JFile::copy($filepath, $dest);		
		}		
	}
	
	function loadLanguageFlex()
	{
		$objLanguage = JFactory::getLanguage();
		$language    = $objLanguage->getTag();
		$filepath 	 = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_imageshow'.DS.'languages'.DS.'admin'.DS.$language.'.flex.ini';	

		$data = array();
		
		if (false === $fhandle = fopen($filepath, 'r')) 
		{
			JError::raiseWarning(21, 'JFile::read: '.JText::_('Unable to open file') . ": '$filepath'");
			return false;
		}
		
		clearstatcache();
		$fsize = filesize($filepath);
		
		if ($fhandle) 
		{
		    while (($line = fgets($fhandle, $fsize)) !== false) 
		    {
		    	$arrayString = explode('=', $line);
		    	
		    	if (count($arrayString) > 0 && !empty($arrayString[0]) && !empty($arrayString[1]) )
		    	{
		    		$data[trim($arrayString[0])] = trim($arrayString[1]);
		    	}
		    }
		    
		    fclose($fhandle);   
		}
		
		return $data;
	}
	
}