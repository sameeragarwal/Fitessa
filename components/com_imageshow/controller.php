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
class ImageShowController extends JController {

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask( 'add',  'display' );
		$this->registerTask( 'edit', 'display' );
		$this->registerTask( 'apply', 'save' );
	}
	
	function display( ) 
	{		
		switch($this->getTask())
		{
				
			default:			
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'view', 'show' );	
				JRequest::setVar( 'model', 'show' );				
		}
		
		parent::display();	
	}
	
}
?>