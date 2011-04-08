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
jimport( 'joomla.application.component.controller' );

class ImageShowControllerAjax extends JController 
{	
	function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	function display() 
	{				
		JRequest::setVar('layout', 'default');
		JRequest::setVar('view', 'ajax');
		parent::display();	
	}

	function checkVersion()
	{
		$jsnProductInfo = 'http://www.joomlashine.com/joomla-extensions/jsn-imageshow-version-check.html';
		$objJSNHTTP   	= JSNISFactory::getObj('classes.jsn_is_httprequest', null, $jsnProductInfo);
		$objJSNJSON     = JSNISFactory::getObj('classes.jsn_is_json'); 
		
		$result    = $objJSNHTTP->DownloadToString();
		
		if ($result == false)
		{
			echo $objJSNJSON->encode(array('connection' => false, 'version' => ''));
			exit();
		}
		else 
		{
			$stringExplode = explode("\n", $result);
			echo $objJSNJSON->encode(array('connection' => true, 'version' => @$stringExplode[2]));
			exit();
		}		
	}
}
?>