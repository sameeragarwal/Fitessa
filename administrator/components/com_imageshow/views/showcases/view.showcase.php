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
class ImageShowViewShowCases extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;	
		
		$document 	=& JFactory::getDocument();
		$document->setMimeEncoding( 'application/json' );

		$showcaseID  	= JRequest::getVar('showcase_id');
		$objJSNShowcase = JSNISFactory::getObj('classes.jsn_is_showcase');
		 
		if ($showcaseID > 0)
		{
			$showcaseData = $objJSNShowcase->getShowcaseByID($showcaseID);
		}
		else// default 
		{
			$theme 			= JRequest::getVar('theme');
			$showcaseTable  = JTable::getInstance('showcase', 'Table');
			$showcaseTable->load(0);
			$showcaseTable->theme_name = $theme;
			$showcaseData 	= $showcaseTable;	
		}
		
		$objJSNUtils	= JSNISFactory::getObj('classes.jsn_is_utils');
		$URL   			= dirname($objJSNUtils->overrideURL()).'/';
		
		$objJSNJSON		= JSNISFactory::getObj('classes.jsn_is_json');
		$dataObj 		= $objJSNShowcase->getShowcase2JSON($showcaseData, $URL);
		
		echo $objJSNJSON->encode($dataObj);
		
		jexit();
	}
}
