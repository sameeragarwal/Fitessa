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
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_installermessage.php');
class ImageShowControllerInstaller extends JController {

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('installcore', 'display');
		$this->registerTask('installtheme', 'display');
		$this->registerTask('installsuccessfully', 'display');
	}
	
	function display() 
	{					
		JRequest::setVar('hidemainmenu', 1);
		switch ($this->getTask())
		{
			case 'installtheme':	
			JRequest::setVar('layout', 'installtheme');
			break;
			case 'installsuccessfully':	
			JRequest::setVar('layout', 'installsuccessfully');
			break;
			case 'installcore':
				JRequest::setVar('layout', 'installcore');
			break;			
			default:
			JRequest::setVar('layout', 'default');	
			break;
		}
			
		JRequest::setVar('view', 'installer');
		JRequest::setVar('model', 'installer');	
		parent::display();
	}
	
	function doInstall()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$post   = JRequest::get('post');
		$model	= &$this->getModel('installer');
		
		if ($model->install()) 
		{
			$this->executeMigration();
			$link   = 'index.php?option=com_imageshow&controller=installer&task=installsuccessfully&status=installed';	
		}
		else 
		{
			$link   = 'index.php?option=com_imageshow&controller=installer&task=installtheme';
		}
		$this->setRedirect($link);
	}

	function executeMigration()
	{
		$session 		=& JFactory::getSession();
		$preVersion     = (float) $session->get('preversion', null, 'jsnimageshow');
		$version        = (float) '3.0.0';
		$model = $this->getModel('installer');
		
		$checkFileExist 	= $model->checkBackupFile('jsn_is_showcase_backup.xml');

		if ($checkFileExist)
		{
			$checkTableExist 	= $model->checkTableExist('#__imageshow_theme_classic');
			if ($checkTableExist)
			{
				if ($preVersion < $version)
				{
					$model->executeRestoreShowcaseThemeClassicData();
					$model->removeFile(JPATH_ROOT.DS.'tmp'.DS.'jsn_is_showcase_backup.xml');
				}
			}
			else 
			{
				$installResult = $model->installShowcaseThemeClassic();
				if ($installResult)
				{
					$model->executeRestoreShowcaseThemeClassicData();
					$model->removeFile(JPATH_ROOT.DS.'tmp'.DS.'jsn_is_showcase_backup.xml');
				}
			}
		}
		return true;
	}
	
	function forward()
	{
		$this->setRedirect('index.php?option=com_imageshow&controller=installer&task=install');
	}
	
	function finish()
	{
		$link = 'index.php?option=com_imageshow';
		$objJSNInstMessage 		= new JSNISInstallerMessage();
		$objJSNInstMessage->installMessage();
		$this->setRedirect($link);
	}
}
?>