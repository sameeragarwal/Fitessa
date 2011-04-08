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
class ImageShowControllerImages extends JController 
{
	function __construct($config = array())
	{
		parent::__construct($config);		
	}
	
	function display( ) 
	{		
		$document 		= &JFactory::getDocument();
		$viewType		= $document->getType();
		$viewName 		= JRequest::getCmd('view', 'images');
		$view 			= &$this->getView( $viewName, $viewType);
							
		$view->setLayout('default');
		$view->display();
	}
	
	function close()
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$link = 'index.php?option=com_imageshow';

		$mainframe->redirect($link);
	}
}
?>