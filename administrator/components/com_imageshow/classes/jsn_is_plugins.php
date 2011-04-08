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

class JSNISPlugins
{
	var $_pluginType 	= 'jsnimageshow';
	var $_pluginPrefix 	= 'showcasetheme';
	var $_installFolder	= 'install';
	var $_installFile	= 'install.plugin.sql';
	
	function &getInstance()
	{
		static $instanceJSNPlugins;
		if ($instanceJSNPlugins == null)
		{
			$instanceJSNPlugins = new JSNISPlugins();
		}
		return $instanceJSNPlugins;
	}	
	
//	function listJSNPlugins($enabled = true)
//	{
//		$db 	 	=& JFactory::getDBO();
//		$published	= '';
//		
//		$query 	= 'SELECT *
//				   FROM #__plugins 
//				   WHERE 
//				   		 (name LIKE "Theme%" AND folder = \'jsnimageshow\')
//				   		 OR ( folder = \'system\' AND element = \'imageshow\')
//				   		 OR ( folder = \'content\' AND element = \'imageshow\')';
//		
//		$db->setQuery($query);
//		return $db->loadObjectList();
//	}
	
	function getXmlFile($row)
	{
		$baseDir = JPATH_ROOT.DS.'plugins';

		$xmlfile = $baseDir.DS.$row->folder.DS.$row->element.".xml";
		
		$result = new stdClass();
		
		if(file_exists($xmlfile)) 
		{
			if($data = JApplicationHelper::parseXMLInstallFile($xmlfile))
			{
				foreach($data as $key => $value)
				{
					$result->$key = $value;
				}
			}
		}
		return $result;
	}
}