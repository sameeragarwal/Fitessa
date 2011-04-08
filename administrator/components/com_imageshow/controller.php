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
		$this->registerTask('plugin',  'display');
	}
	
	function display( ) 
	{		
		switch($this->getTask())
		{
			case 'plugin':
				JRequest::setVar('layout', 'plugin');
				JRequest::setVar('view', 'cpanel');
				JRequest::setVar('model', 'cpanel');						
			break;
			case 'alltip':
				JRequest::setVar('layout', 'all_tip');
				JRequest::setVar('view', 'cpanel');
				JRequest::setVar('model', 'cpanel');						
			break;				
			default:			
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'view', 'cpanel' );
				JRequest::setVar( 'model', 'cpanel' );				
		}
		
		parent::display();	
	}
	
	function sampledata(){
		$sampleData	= JRequest::getInt( 'sample_data' );
		$menuType	= JRequest::getString( 'menutype' );
		$keepAllData	= JRequest::getInt( 'keep_all_data' );
		$installMessage	= JRequest::getInt( 'install_message' );
		
		$model 	= $this->getModel('cpanel');
		$msg = '';	
		if($keepAllData == 1){
			$model->clearData();
		}
		if($installMessage == 1){
			$objJSNInstMessage 		= JSNISFactory::getObj('classes.jsn_is_installermessage');
			$objJSNInstMessage->installMessage();
		}		
		if ($sampleData == 1) {
			$model->populateDatabase();
			if($menuType != ''){
				$model->insertMenuSample($menuType);
			}
			$msg  = JText::_('INSTALL SAMPLE DATA SUCCESSFULLY');
		}		
		$link = 'index.php?option=com_imageshow';
		
		$this->setRedirect($link, $msg);
	}
}
?>