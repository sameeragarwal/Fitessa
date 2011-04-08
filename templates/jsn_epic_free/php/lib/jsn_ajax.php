<?php
/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2009 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/
defined( '_JEXEC' ) or die( 'Restricted index access' );

class JSNAjax 
{
	var $_template_path_of_base;
	var $_template_name;
	
	function JSNAjax()
	{
		$this->_setTmplInfo();
		require_once($this->_template_path_of_base.DS.'php'.DS.'lib'.DS.'jsn_utils.php');
	}
	
	function _setTmplInfo()
	{		
		$template_name 					= explode(DS, str_replace(array('\php\lib', '/php/lib'), '', dirname(__FILE__)));
		$template_name 					= $template_name [count( $template_name ) - 1];		
		$path_base 						= str_replace(DS."templates".DS.$template_name.DS.'php'.DS.'lib', "", dirname(__FILE__));
		$this->_template_name       	= $template_name;
		$this->_template_path_of_base 	= $path_base . DS . 'templates' .  DS . $template_name;				
	}
			
	function checkCacheFolder() 
	{		
		$obj_utils		=& JSNUtils::getInstance();
		$cache_folder   = JRequest::getVar('cache_folder');
		$isDir 			= is_dir($cache_folder);
		$isWritable 	= is_writable($cache_folder);
		echo $obj_utils->encodeJSON(array('isDir' => $isDir, 'isWritable' => $isWritable));		
	}
	
	function checkVersion()
	{
		$template_name	  		= $this->_template_name;
		$template_name_explode	= explode('_', $template_name);
		$template_name			= @$template_name_explode[0].'-'.@$template_name_explode[1];
		$jsn_product_info		= 'http://www.joomlashine.com/joomla-templates/'.$template_name.'-version-check.html';
		$obj_utils		  		=& JSNUtils::getInstance();
		$obj_http_request 		= new JSNHTTPRequests($jsn_product_info, null, null, 'get');
		$result    		  		= $obj_http_request->sendRequest();
		if($result == false)
		{
			echo $obj_utils->encodeJSON(array('connection' => false, 'version' => ''));
		}
		else 
		{
			$stringExplode = explode("\n", $result);
			echo $obj_utils->encodeJSON(array('connection' => true, 'version' => @$stringExplode[2]));
		}
	}
}