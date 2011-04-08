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
		global $mainframe, $option, $Itemid;	
		jimport('joomla.utilities.simplexml');
		$showlistID 		= JRequest::getInt('showlist_id', 0);

		if ($showlistID == 0)
		{
			$menu 				=& JSite::getMenu();
			$item 				= $menu->getActive();
			$params 			=& $menu->getParams($item->id);
			$showlistID 		= $params->get('showlist_id', 0);
		}
		
		$objUtils			= JSNISFactory::getObj('classes.jsn_is_utils');
		$URL				= $objUtils->overrideURL();
		$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
		$showlistInfo 		= $objJSNShowlist->getShowListByID($showlistID, true);
		
		if (count($showlistInfo) <=0)
		{
			header("HTTP/1.0 404 Not Found");
			exit();
		}
		
		$objJSNShowlist->insertHitsShowlist($showlistID);
		
		$objJSNJSON = JSNISFactory::getObj('classes.jsn_is_json');
		$dataObj 	= $objJSNShowlist->getShowlist2JSON($URL, $showlistID);
		
		echo $objJSNJSON->encode($dataObj);
		
		jexit();
	}
}
