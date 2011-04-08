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
class JSNISJSON {
	function &getInstance()
	{
		static $instanceJSON;
		if ($instanceJSON == null)
		{
			$instanceJSON = new JSNISJSON();
		}
		return $instanceJSON;
	}
	// encode data to json data
	function encode($dataObj)
	{
		if (!function_exists('json_encode')) 
		{
			if(!class_exists('Services_JSON'))
			{
				include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'libraries'.DS.'json.php');
			}
		    $json = new Services_JSON;
		    return $json->encode($dataObj);
		}
		else
		{
			return json_encode($dataObj);
		}
	}
	
	// decode json data
	function decode($dataObj)
	{
		if (!function_exists('json_decode')) 
		{
			if(!class_exists('Services_JSON'))
			{
				include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'libraries'.DS.'json.php');
			}
		    $json = new Services_JSON;
		    return $json->decode($dataObj);
		}
		else
		{
			return json_decode($dataObj);
		}
	}
}