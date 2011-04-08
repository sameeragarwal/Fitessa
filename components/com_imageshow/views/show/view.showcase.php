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
jimport( 'joomla.application.component.view');
class ImageShowViewShow extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;	
		jimport('joomla.utilities.simplexml');
		$showCaseID = JRequest::getInt('showcase_id', 0);
		
		if ($showCaseID == 0)
		{
			$menu 			=& JSite::getMenu();
			$item 			= $menu->getActive();
			$params 		=& $menu->getParams($item->id);
			$showcase_id 	= $params->get('showcase_id', 0);
		}
		else
		{
			$showcase_id = $showCaseID;
		}
	
		$objUtils		= JSNISFactory::getObj('classes.jsn_is_utils');
		$URL   			= $objUtils->overrideURL();
		$objJSNShowcase	= JSNISFactory::getObj('classes.jsn_is_showcase');
		$row 			= $objJSNShowcase->getShowCaseByID($showcase_id);
		
		if(count($row) <=0){
			header("HTTP/1.0 404 Not Found");
			exit();
		}
		
		$objJSNJSON	= JSNISFactory::getObj('classes.jsn_is_json');
		$dataObj 	= $objJSNShowcase->getShowcase2JSON($row, $URL);
		
		echo $objJSNJSON->encode($dataObj);
		
		jexit();	
	}
}
