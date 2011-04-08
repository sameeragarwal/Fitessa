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

class JSNISUtils
{
	var $_db 					= null;
	var $_hashStringStandard 	= 'YVF8G9czaqNJoWrQPrdHq3VWza329zqU';
	var $_hashStringUnlimited 	= 'GcZmAzw74WsiYOmhtIIY4OFn6TfXBp9R';

	function JSNISUtils()
	{
		if ($this->_db == null)
		{
			$this->_db = &JFactory::getDBO();
		}
	}

	function &getInstance()
	{
		static $instanceUtils;
		if ($instanceUtils == null)
		{
			$instanceUtils = new JSNISUtils();
		}
		return $instanceUtils;
	}

	function getParametersConfig()
	{
		$query = 'SELECT * FROM #__imageshow_parameters';
		$this->_db->setQuery($query);

		return $this->_db->LoadObject();
	}

	function overrideURL()
	{
		$config = $this->getParametersConfig();

		if (!is_null($config) && $config->root_url == 2)
		{
			return JURI::base();
		}
		else
		{
			$pathURL 			= array();
			$uri				=& JURI::getInstance();
			$pathURL['prefix'] 	= $uri->toString( array('scheme', 'host', 'port'));

			if (strpos(php_sapi_name(), 'cgi') !== false && !ini_get('cgi.fix_pathinfo') && !empty($_SERVER['REQUEST_URI']))
			{
				$pathURL['path'] =  rtrim(dirname(str_replace(array('"', '<', '>', "'"), '', $_SERVER["PHP_SELF"])), '/\\');
			}
			else
			{
				$pathURL['path'] =  rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
			}

			return $pathURL['prefix'].$pathURL['path'].'/';
		}
	}

	function checkSupportLang()
	{
		$objectReadxmlDetail 	= new JSNISReadXmlDetails();
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();
		$supportLang			= $infoXmlDetail['langs'];
		$objLanguage 			= JFactory::getLanguage();
		$language           	= $objLanguage->getTag();

		if (@in_array($language, $supportLang))
		{
    		return true;
		}
		return false;
	}

	function getAlterContent()
	{
		$script = "\n<script type='text/javascript'>\n";
		$script .= "window.addEvent('domready', function(){
						JSNISImageShow.alternativeContent();
					});";
		$script .= "\n</script>\n";
		return $script;
	}

	function checkMootoolVersion()
	{
	    $query = 'SELECT * FROM #__plugins WHERE element = "mtupgrade" AND folder = "system" AND published = 1';
	    $this->_db->setQuery($query);
	    $result = $this->_db->loadRow();

	    if ($result != null)
	    {
	        return '1.2';
	    }
	    return '1.1';
	}

	/*
	 *  encode url with special character
	 *
	 */
	function encodeUrl($url, $replaceSpace = false)
	{
		$encodeStatus = $this->encodeStatus($url);

		if ($encodeStatus == false)
		{
			$url = rawurlencode($url);
		}

		$url = str_replace('%3B', ";", $url);
	    $url = str_replace('%2F', "/", $url);
	    $url = str_replace('%3F', "?", $url);
	    $url = str_replace('%3A', ":", $url);
	    $url = str_replace('%40', "@", $url);
	    $url = str_replace('%26', "&", $url);
	    $url = str_replace('%3D', "=", $url);
	    $url = str_replace('%2B', '+', $url);
	    $url = str_replace('%24', "$", $url);
	    $url = str_replace('%2C', ",", $url);
	    $url = str_replace('%23', "#", $url);
	    $url = str_replace('%2D', "-", $url);
	    $url = str_replace('%5F', "_", $url);
	    $url = str_replace('%2E', ".", $url);
	    $url = str_replace('%21', "!", $url);
	    $url = str_replace('%7E', "~", $url);
	    $url = str_replace('%2A', "*", $url);
	    $url = str_replace('%27', "'", $url);
	    $url = str_replace('%22', "\"", $url);
	    $url = str_replace('%28', "(", $url);
	    $url = str_replace('%29', ")", $url);

	    if ($replaceSpace == true)
	    {
	    	$url = str_replace('%20', " ", $url);
	    }
	    return $url;
	}

	/*
	 * encode array url
	 *
	 */
	function encodeArrayUrl($urls, $replaceSpace = false)
	{
		$arrayUrl =  array();
		foreach ($urls as $key => $value )
		{
			$url = $this->encodeUrl($value, $replaceSpace);
			$arrayUrl[$key] = $url;
		}

		return $arrayUrl;
	}

	//decode url that was encoded by encodeUrl()
	function decodeUrl($url)
	{
		$url = rawurldecode($url);
		return $url;
	}

	// check string was encoded
	function encodeStatus($string)
	{
		$regexp  = "/%+[A-F0-9]{2}/";
		if (preg_match($regexp,$string))
		{
			return true;
		}
		return false;
	}

	function getIDComponent()
	{
		$query 	= 'SELECT c.id FROM #__components c WHERE c.option="com_imageshow" AND c.parent = 0';
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}

	function insertMenuSample($menuType)
	{
		$comID 	= $this->getIDComponent();
		$query 	= "INSERT INTO
						`#__menu`
						(`id`, `menutype`, `name`, `alias`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`, `lft`, `rgt`, `home`)
				   VALUES
				  		(NULL, '".$menuType."', 'JSN ImageShow', 'imageshow', 'index.php?option=com_imageshow&view=show', 'component', '1', '0', '".$comID['id']."', '0', '0', '0', '0000-00-00 00:00:00', '0', '0', '0', '0', 'showlist_id=1\nshowcase_id=1', '0', '0', '0')";
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	function checkComInstalled($comName)
	{
		$query 	= "SELECT * FROM #__components WHERE STRCMP(`option`, '".$comName."') = 0";
		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();

		if (!empty($result))
		{
			return true;
		}
		return false;
	}

	function checkIntallModule()
	{
		$query 	= 'SELECT COUNT(*) FROM #__modules WHERE module="mod_imageshow"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();

		if ($result[0] > 0)
		{
			return true;
		}
		return false;
	}

	function checkIntallPluginContent()
	{
		$query 	= 'SELECT COUNT(*) FROM #__plugins WHERE element="imageshow" AND folder="content"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();

		if ($result[0] > 0)
		{
			return true;
		}
		return false;
	}

	function checkIntallPluginSystem()
	{
		$query 	= 'SELECT COUNT(*) FROM #__plugins WHERE element="imageshow" AND folder="system"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();

		if ($result[0] > 0)
		{
			return true;
		}
		return false;
	}

	function getPluginContentInfo()
	{
		$query 	= 'SELECT id FROM #__plugins WHERE element="imageshow" AND folder="content"';
		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();
		return $result;
	}

	function clearData()
	{
		$queries [] = 'TRUNCATE TABLE `#__imageshow_configuration`';
		$queries [] = 'TRUNCATE TABLE `#__imageshow_showlist`';
		$queries [] = 'TRUNCATE TABLE `#__imageshow_showcase`';
		$queries [] = 'TRUNCATE TABLE `#__imageshow_images`';

		foreach ($queries as $query)
		{
			$query = trim($query);
			if ($query != '')
			{
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}
		return true;
	}

	function getTotalProfile()
	{
		$query 	= 'SELECT COUNT(*) FROM #__imageshow_configuration WHERE source_type <> 1';
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function drawXMLTreeByPath($path, $syncAlbum = array())
	{
		$dir = @opendir($path);
		$xml = '';
		while (false !== ($file = readdir($dir)))
		{
			if (is_dir($path.DS.$file) && $file != '.' && $file != '..')
        	{
        		$folderLevel = str_replace(JPATH_ROOT.DS, '', $path.DS.$file);
        		$folderLevel = str_replace(DS, '/', $folderLevel);
        		$syncStatus  = (in_array($folderLevel, $syncAlbum)) ? ' state=\'checked\' ' : ' state=\'unchecked\' ';

        		$xml .= "<node label='". $file ."' data='". $folderLevel ."' ". $syncStatus .">\n";
        		$xml .= JSNISUtils::drawXMLTreeByPath($path.DS.$file, $syncAlbum);
        	}
	    }
	    $xml .= "</node>\n";

	    return $xml;
	}

	function getImageInPath($path = null)
	{
		if ($path == null) return false;

		$dir 		= @opendir($path);
		$arrayImage = array();

		while (false !== ($file = readdir($dir)))
		{
			if (is_file($path.DS.$file))
			{
				$fileInfo = pathinfo($path.DS.$file);

				if (ereg('(png|jpg|jpeg|gif)',strtolower($fileInfo['extension'])))
				{
					$arrayImage[] = str_replace(DS, '/', $path.DS.$file);
				}
			}
        }

      	return $arrayImage;
    }

	function checkValueArray($arrayList, $index)
	{
		if (!array_key_exists($index, $arrayList))
		{
   			return false;
		}

		if ($arrayList[$index] != '')
		{
			return $arrayList[$index];
		}
		else
		{
			$index = $index - 1;
			return $this->checkValueArray($arrayList,$index);
		}
	}

	function wordLimiter($str, $limit = 100, $end_char = '&#8230;')
	{
		if (trim($str) == '')
			return $str;
		preg_match('/\s*(?:\S*\s*){'. (int) $limit .'}/', $str, $matches);
		if (strlen($matches[0]) == strlen($str))
			$end_char = '';
		return rtrim($matches[0]).$end_char;
	}

	function checkTmpFolderWritable()
	{
		$foldername = 'tmp';
		$folderpath = JPATH_ROOT.DS.$foldername;

		if (is_writable($folderpath) == false)
		{
			JError::raiseWarning(100, JText::sprintf('Folder "%s" is Unwritable. Please set Writable permission (CHMOD 777) for it before performing maintenance operations', DS.$foldername));
		}
		return true;
	}

	function renderMenuComboBox($ID, $elementText, $elementName, $parameters = '')
	{
		$this->_db 	=& JFactory::getDBO();
		$query  = 'SELECT menutype AS value, title AS text FROM #__menu_types';
		$this->_db->setQuery($query);
		$data 	= $this->_db->loadObjectList();
		array_unshift($data, JHTML::_('select.option', '', '- '.JText::_($elementText).' -', 'value', 'text'));
		return JHTML::_('select.genericlist', $data, $elementName, $parameters, 'value', 'text', $ID);
	}

	function randSTR($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
	{
	    $charsLength 	= (strlen($chars) - 1);
	    $string 		= $chars{rand(0, $charsLength)};

	    for ($i = 1; $i < $length; $i = strlen($string))
	    {
	        $r = $chars{rand(0, $charsLength)};
	        if ($r != $string{$i - 1}) $string .=  $r;
	    }

	    return $string;
	}

	function checkHashString()
	{
		$objJSNXML 		= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXMLDetail 	= $objJSNXML->parserXMLDetails();

		if (!isset($infoXMLDetail))
		{
			return false;
		}

		$hashString = $infoXMLDetail['hashString'];

		if ($hashString == '')
		{
			return false;
		}

		if ($hashString == $this->_hashStringStandard || $hashString == $this->_hashStringUnlimited)
		{
			return true;
		}
		return false;
	}

	function callJSNButtonMenu()
	{
		jimport('joomla.html.toolbar');
		$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers';
		$toolbar = & JToolBar::getInstance('toolbar');
		$toolbar->addButtonPath($path);
		$toolbar->appendButton('JSNMenuButton');
	}
}
?>