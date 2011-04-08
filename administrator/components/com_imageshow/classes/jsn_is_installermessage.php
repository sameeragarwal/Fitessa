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
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_readxmldetails.php');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
class JSNISInstallerMessage
{
	function JSNInstallerMessage(){}
	function installMessage()
	{
		$db 					=& JFactory::getDBO();
		$lang 					=& JFactory::getLanguage();
		$currentlang   			= $lang->getTag(); 		
		$objectReadxmlDetail 	= new JSNISReadXmlDetails();
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();
		$langSupport 			= $infoXmlDetail['langs'];
		$registry				= new JRegistry();
		$newStrings				= array();
		$path 					= null;
		$realLang				= null;
		$queries				= array();
		
		if (array_key_exists($currentlang, $langSupport))
		{
			$path 		= JLanguage::getLanguagePath( JPATH_BASE, $currentlang);
			$realLang	= $currentlang;
		}
		else
		{
			$filepath 		= JPATH_ROOT.DS.'administrator'.DS.'language';
			$foldersLang 	= $this->getFolder($filepath);		
			foreach ($foldersLang as $value)
			{
				if (in_array($value, $langSupport) == true)
				{
					$path 		= JLanguage::getLanguagePath( JPATH_BASE, $value);
					$realLang	= $value;
					break;
				}
			}
		}
		$filename	= $path.DS.$realLang.'.com_imageshow.ini';
		$content 	= @file_get_contents( $filename );
		
		if ($content)
		{	
			$registry->loadINI($content);
			$newStrings	= $registry->toArray();
			
			if (count($newStrings))
			{
				if (count($infoXmlDetail['menu']))
				{
					$queries [] = 'TRUNCATE TABLE `#__imageshow_messages`';
					foreach ($infoXmlDetail['menu'] as $value)
					{
						$index = 1;
						while (isset($newStrings['MESSAGE '.$value.' '.$index.' PRIMARY']))
						{
							$queries [] = 'INSERT INTO `#__imageshow_messages` (`msg_id`,`msg_screen`,`published`,`ordering`) VALUES (NULL, "'.$value.'", 1, '.$index.')';
							$index ++;
						}
					}
				}
			}
			
			if (count($queries))
			{
				foreach ($queries as $query)
				{
					$query = trim($query);

					if ($query != '')
					{				
						$db->setQuery($query);
						$db->query();								
					}			
				}
			}
		}
		return true;				
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
		ksort($arrayFolder);
		return $arrayFolder;
	}	
}
