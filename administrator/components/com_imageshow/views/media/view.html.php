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

class ImageShowViewMedia extends JView
{
	function display($tpl = null) 	
	{
		global $mainframe;	

		JHTML::_('script'    , 'jsn_is_imagemanager.js', 'administrator/components/com_imageshow/assets/js/');
		JHTML::_('stylesheet', 'popup-imagemanager.css', 'administrator/components/com_imageshow/assets/css/');	
		JHTML::_('stylesheet', 'system.css', 'templates/system/css/');
		
		$objJSNMediaManager = JSNISFactory::getObj('classes.jsn_is_mediamanager');
		$objJSNMediaManager->setMediaBasePath();
		$state 				= $objJSNMediaManager->getStateFolder();
		$folderList			= $objJSNMediaManager->getFolderList();
		
		$this->assignRef('session', JFactory::getSession());
		$this->assignRef('state', $state);
		$this->assignRef('folderList', $folderList);		
		parent::display($tpl);
	}	
}
