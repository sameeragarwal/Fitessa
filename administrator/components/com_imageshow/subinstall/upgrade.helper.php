<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_httprequest.php');
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_readxmldetails.php');
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_comparefiles.php');

class JSNUpgradeHelper
{
	var $_mainfest		= null;
	var $_url 			= null;
	var $_previousName	= null;
	var $_currentName	= null;
	var $_path			= null;
	
	function JSNUpgradeHelper($manifest)
	{
		$this->setManifest($manifest);
		$this->setPreviousName();
		$this->setCurrentName();
		$this->setPath();
		$this->setURL();
	}
	
	function setManifest($manifest)
	{
		$this->_mainfest = $manifest;
	}
	
	function setURL()
	{
		$this->_url = 'http://media.joomlashine.com/products/extensions/jsn_imageshow/checksum/';
	}
	
	function getPreviousName()
	{
		$objectReadxmlDetail = new JSNISReadXmlDetails();
		$infoXmlDetail 		 = $objectReadxmlDetail->parserXMLDetails();
		return JString::strtolower($infoXmlDetail['realName']);	
	}
	
	function getCurrentName()
	{
		$document 	 =& $this->_mainfest->document;
		$name     	 =& $document->getElementByPath('name');
		return JString::strtolower($name->data());
	}
	
	function setPreviousName()
	{
		$edition = $this->getPreviousEdition();
		$name    = $this->getPreviousName();
		if($edition != '')
		{
			$edition = '_'.$edition;
		}
		$fileName 			 = 'jsn_'.$name.$edition.'_'.$this->getPreviousVersion().'.checksum';
		$this->_previousName = $fileName;
	}
	
	function setCurrentName()
	{
		$edition = $this->getCurrentEdition();
		$name    = $this->getCurrentName();
		if ($edition != '')
		{
			$edition = '_'.$edition;
		}
		$fileName 			 = 'jsn_'.$name.$edition.'_'.$this->getCurrentVersion().'.checksum';
		$this->_currentName  = $fileName;
	}

	function setPath()
	{
		$path 	  	  = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow';
		$this->_path  = $path;
	}
	
	function getPreviousVersion()
	{
		$objectReadxmlDetail = new JSNISReadXmlDetails();
		$infoXmlDetail 		 = $objectReadxmlDetail->parserXMLDetails();
		$session 			 =& JFactory::getSession();
		$session->set('preversion', @$infoXmlDetail['version'], 'jsnimageshow');		
		return @$infoXmlDetail['version'];	
	}
	
	function getCurrentVersion()
	{
		$document 	 =& $this->_mainfest->document;
		$version     =& $document->getElementByPath('version');
		return $version->data();
	}
	
	function getPreviousEdition()
	{
		$objectReadxmlDetail = new JSNISReadXmlDetails();
		$infoXmlDetail 		 = $objectReadxmlDetail->parserXMLDetails();	
		return (isset($infoXmlDetail['edition'])?str_replace(' ', '_', JString::strtolower($infoXmlDetail['edition'])):'');	
	}
	
	function getCurrentEdition()
	{
		$document 	 =& $this->_mainfest->document;
		$edition     =& $document->getElementByPath('edition');
		return str_replace(' ', '_' ,JString::strtolower($edition->data()));
	}
	
	function checkPreviousChecksumFile()
	{
		$path = $this->_path.DS.$this->_previousName;
		
		if (JFile::exists($path))
		{
			return $path;
		}
		
		return false;
	}

	function checkCurrentChecksumFile()
	{
		$path = $this->_path.DS.$this->_currentName;
			
		if (JFile::exists($path))
		{
			return $path;
		}
		
		return false;
	}
	
	function getPreviousFileContent()
	{
		$path = $this->checkPreviousChecksumFile();
		
		if ($path != false)
		{
			return file($path);
		}
		else 
		{
			$url 		= $this->_url.$this->_previousName;
			$objJSNHTTP	= new JSNISHTTPRequest($url);
			$content    = $objJSNHTTP->DownloadToString();
			
			if ($content == false)
			{
				$content = array();
				return $content;
			}	
					
			return explode("\n", $content);
		}
	}
	
	function getCurrentFileContent()
	{
		$path = $this->checkCurrentChecksumFile();
		
		if ($path != false)
		{
			return @file($path);
		}
	}

	function deleteRedundantFile($files)
	{
		$strReplace = array('\\', '/');
		if (count($files))
		{
			foreach ($files as $key => $file)
			{
				$path = JPATH_ROOT.DS.str_replace($strReplace, DS, $key);
				if (JFile::exists($path))
				{
					JFile::delete($path);
				}
				
				$dir = @dirname($path);
				if (is_dir($dir)) 
				{ 
					$fileList = JFolder::files($dir, '.', true);
					
					if(!count($fileList))
					{
						@rmdir($dir);
					}
				}
				
			}
			$this->deletePreviousChecksumFile();
		}
		return true;
	}
	
	function deletePreviousChecksumFile()
	{
		$path = $this->checkPreviousChecksumFile();
		if ($path != false)
		{
			if (JFile::exists($path))
			{
				JFile::delete($path);
			}
			return true;		
		}
		return false;
	}
		
	function executeUpgrade()
	{
		$previousFileContent = $this->getPreviousFileContent();
		$currentFileContent  = $this->getCurrentFileContent();
		$objCompareFiles	 = new JSNISCompareFiles();
		$result 			 = $objCompareFiles->compareFileContent($currentFileContent, $previousFileContent);
		$this->deleteRedundantFile($result);
	}
}