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

class JSNISFactory
{
	function &getInstance($className, $config = null)
	{
		static $arrayInstance = array();
		
		if (!empty($config))
		{
			$arrayInstance[$className] = new $className($config);
		}
		
		if (empty($arrayInstance[$className]))
		{
			$arrayInstance[$className] = new $className();
		}
		
		return $arrayInstance[$className];
	}	
	
	function getObj($string , $specifyClass = null, $config = null, $basePath = 'admin', $ext = '.php')
	{
		$path 		= '';
		$array 		= explode('.', $string);
		$fileName 	= end($array);
		$className  = JSNISFactory::paserFileNameToClass($fileName);
		
		if (count($array) > 0)
		{
			foreach($array as $value)
			{
				if (!empty($value))
				{
					$path .= DS.$value;
				}
			}
			
			if ($basePath == 'admin')
			{
				$path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.$path.$ext;
			}
			elseif ($basePath == 'site')
			{
				$path = JPATH_SITE.DS.'components'.DS.'com_imageshow'.DS.$path.$ext;
			}
			else
			{
				$path = $basePath.DS.$path.$ext;
			}
			
			if (file_exists($path))
			{
				require_once($path);
				
				if (empty($specifyClass))
				{
					$class =& JSNISFactory::getInstance($className, $config);
				}
				else
				{
					$class =& JSNISFactory::getInstance($specifyClass , $config);
				}
				return $class;
			}
			else
			{
				echo $path.' '.JText::_('NOT EXISTS');
			}
		}
	}
	
	function paserFileNameToClass($fileName)
	{
		if(!empty($fileName) && count(explode('_', $fileName)) == 3)
		{
			$arrayNamePart = explode('_', $fileName);
			return strtoupper($arrayNamePart[0].$arrayNamePart[1]).ucfirst($arrayNamePart[2]);
		}
		return false;
	}
	
	function importFile($string , $basePath = 'admin', $ext = '.php')
	{
		$path 		= '';
		$array 		= explode('.', $string);
		$fileName 	= end($array);
		$className  = JSNISFactory::paserFileNameToClass($fileName);
		
		if (count($array) > 0)
		{
			foreach ($array as $value)
			{
				if (!empty($value))
				{
					$path .= DS.$value;
				}
			}
			
			if ($basePath == 'admin')
			{
				$path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.$path.$ext;
			}
			elseif ($basePath == 'site')
			{
				$path = JPATH_SITE.DS.'components'.DS.'com_imageshow'.DS.$path.$ext;
			}
			else
			{
				$path = $basePath.DS.$path.$ext;
			}
			
			if (file_exists($path))
			{
				require_once($path);
			}
			else
			{
				echo $path.' '.JText::_('NOT EXISTS');
			}
		}
	}
}