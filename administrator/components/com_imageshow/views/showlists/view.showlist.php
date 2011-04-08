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
class ImageShowViewShowLists extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $Itemid;	
		jimport('joomla.utilities.simplexml');
		
		$objJSNUtils		= JSNISFactory::getObj('classes.jsn_is_utils');
		$URL				= dirname($objJSNUtils->overrideURL()).'/';
		$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
		$objJSNJSON 		= JSNISFactory::getObj('classes.jsn_is_json');

		$showlistID 		= JRequest::getVar('showlist_id');		
		$dataObj 			= $objJSNShowlist->getShowlist2JSON($URL, $showlistID);
		
		echo $objJSNJSON->encode($dataObj);
		jexit();
	}
}
