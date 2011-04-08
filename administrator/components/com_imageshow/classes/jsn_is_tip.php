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
class JSNISTip
{
	function &getInstance()
	{
		static $instanceTip;	
		
		if ($instanceTip == null)
		{
			$instanceTip = new JSNISTip();
		}	
		return $instanceTip;
	}

	function parseFileTips($path)
	{
		$parser =& JFactory::getXMLParser('Simple');
		$arrayContent = array();
		
		if ($parser->loadFile($path))
		{
			$document 	=& $parser->document;
			$tips 		= & $document->tip;
			
			if (count($tips))
			{
				for ($i = 0; $i < count($tips); $i++ )
				{
					$tip 			=& $tips[$i];
					$title 			=& $tip->getElementByPath('title') ;
					$content 		=& $tip->getElementByPath('content') ;
					$arrayContent[] = array('title'=>$title->data(), 'content'=>$content->data());
				}
				return $arrayContent;
			}
			return false;
		}
		return false;
	}
	
	function getRandomContentFileTips($folderName)
	{
		$arrayLanguage 	= array("vi-vn", "en-gb", "pl-pl");
		$realFolder 	= array('vi-VN'=>'vi-vn', 'en-GB'=>'en-gb', 'pl-PL'=>'pl-pl');
		
		if (in_array($folderName, $arrayLanguage))
		{
			$path = realpath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tips'.DS.array_search($folderName, $realFolder).DS.$folderName.'.xml');
		}
		else
		{
			$path = realpath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tips'.DS.'en-GB'.DS.'en-gb.xml');
		}
	
		$contentFile = $this->parseFileTips($path);
		
		if ($contentFile != false)
		{
			$totalContentItems = count($contentFile);
			$randomNumber = rand(0, $totalContentItems - 1); 
			return $contentFile[$randomNumber];
		}
		return false;
	}
	
	function getAllContentTips($folderName)
	{		
		$arrayLanguage 	= array("vi-vn", "en-gb", "pl-pl");
		$realFolder 	= array('vi-VN'=>'vi-vn', 'en-GB'=>'en-gb', 'pl-PL'=>'pl-pl');
				
		if (in_array($folderName, $arrayLanguage))
		{
			$path = realpath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tips'.DS.array_search($folderName, $realFolder).DS.$folderName.'.xml');
		}
		else
		{
			$path = realpath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tips'.DS.'en-GB'.DS.'en-gb.xml');
		}
		
		$contentFile = $this->parseFileTips($path);
		
		if ($contentFile != false)
		{
			return $contentFile;
		}
		
		return false;				
	}
	
	function getImageShowRSS()
	{
		$rssUrl 	= 'http://feeds.feedburner.com/joomlashine';
		$options 	= array();	
		$rssitems   = 1;	
		$options['rssUrl'] 	= $rssUrl;
		$rssDoc 			=& JFactory::getXMLparser('RSS', $options);
		
		if ($rssDoc != false)
		{
			$items = $rssDoc->get_items();
			$items = array_slice($items, 0, $rssitems);
			return $items;
		}
		return false;
	}
}