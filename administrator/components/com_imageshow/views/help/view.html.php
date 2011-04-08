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

class ImageShowViewHelp extends JView
{
	function display($tpl = null) 
	{
		global $mainframe, $option;
		JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
		JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');
		parent::display($tpl);
	}
}