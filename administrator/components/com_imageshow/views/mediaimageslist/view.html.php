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

class ImageShowViewMediaImagesList extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		JHTML::_('stylesheet', 'popup-imagemanager.css', 'administrator/components/com_imageshow/assets/css/');
		$document =& JFactory::getDocument();
		$document->addScriptDeclaration("var JSNISImageManager = window.parent.JSNISImageManager;");

		$objJSNMediaManager = JSNISFactory::getObj('classes.jsn_is_mediamanager');
		$images 			= $objJSNMediaManager->getImages();
		$folders 			= $objJSNMediaManager->getFolders();
		
		$this->assign('baseURL', $objJSNMediaManager->comMediaBaseURL);
		$this->assignRef('images', $images);
		$this->assignRef('folders', $folders);
		parent::display($tpl);
	}
}