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

class ImageShowControllerFlex extends JController {

	function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	function display( ) 
	{		
		$layout = JRequest::getVar('layout');
		JRequest::setVar( 'layout', $layout);
		JRequest::setVar( 'view', 'flex' );
		parent::display();	
	}
}
