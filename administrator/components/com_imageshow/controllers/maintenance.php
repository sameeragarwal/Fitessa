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

class ImageShowControllerMaintenance extends JController 
{
	function __construct()
	{
		parent::__construct();
	}
	
	function display( ) 
	{
		
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'view'  , 'maintenance' );
		JRequest::setVar( 'model'  , 'maintenance' );	
		parent::display();
	}
	
	function backup() 
	{
		global $option;
		$model 								= $this->getModel( 'maintenance' );
		$filename							= JRequest::getVar('filename');
		$showLists							= JRequest::getInt('showlists');
		$showCases							= JRequest::getInt('showcases');
		$timestamp							= JRequest::getInt('timestamp');
	
		if ($showLists == 1)
		{
			$showLists = true;
		}
		else
		{
			$showLists = false;
		}
		
		if ($showCases == 1)
		{
			$showCases = true;
		}
		else
		{
			$showCases = false;
		}
		
		if ($timestamp == 1)
		{
			$timestamp = true;
		}
		else
		{
			$timestamp = false;
		}
		
		$result 	= $model->backup($showLists, $showCases, $timestamp, $filename);
		$link 		= 'index.php?option=com_imageshow&controller=maintenance';
		
		if ($result == false)
		{
			$msg = JText::_('YOU MUST SELECT AT LEAST ONE TYPE TO BACKUP');
			$this->setRedirect($link,$msg);
		}
	}
	
	function restore() 
	{
		global $objectLog;
		$user			= & JFactory::getUser();
		$userID			= $user->get ('id');
		$file       	= JRequest::getVar( 'filedata', '', 'files', 'array' );
		$extensionFile 	= substr($file['name'], strrpos($file['name'],'.')+1 );
		
		if ($extensionFile == 'zip')
		{
			$compressType 	= 1; 
			$filepath 		= JPATH_ROOT.DS.'tmp';
			
			$config['path'] 		= $filepath;
			$config['file'] 		= $file;
			$config['compress'] 	= $compressType;
			
			$objJSNRestore 	= JSNISFactory::getObj('classes.jsn_is_restore'); 
			$result 		= $objJSNRestore->restore($config);			
			$link 			= 'index.php?option=com_imageshow&controller=maintenance';

			if ($result === true)
			{
				$objectLog->addLog($userID, JRequest::getURI(), $file['name'],'maintenance','restore');
				$msg 		= JText::_('RESTORE SUCCESSFULL');
				$this->setRedirect($link,$msg);						
			}
			elseif ($result == 'outdated')
			{
				$msg 		= JText::_('ERROR IMAGESHOW VERSION RESTORE');
				$this->setRedirect($link,$msg);						
			}
			else
			{
				$msg 		= JText::_('RESTORE UNSUCCESSFULL');
				$this->setRedirect($link,$msg);
			}				
		}
		else
		{
			$msg = JText::_('FORMAT FILE RESTORE INCORRECT');
			$link = 'index.php?option=com_imageshow&controller=maintenance';
			$this->setRedirect($link,$msg);
		}
	}
	
	function cancel() 
	{
		$link = 'index.php?option=com_imageshow';
		$this->setRedirect($link);
	}
	
	function reInstallLang()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$array_BO		= JRequest::getVar( 'lang_array_BO', array(), 'post', 'array' );
		$array_FO		= JRequest::getVar( 'lang_array_FO', array(), 'post', 'array' );
		$objJSNLang 	= JSNISFactory::getObj('classes.jsn_is_language');
		$msg			= JText::_('YOU MUST SELECT AT LEAST ONE LANGUAGE TO INSTALL');

		if (count($array_BO) > 0)
		{
			$msg = JText::_('THE LANGUAGE HAS BEEN SUCCESSFULLY INSTALLED');
			$objJSNLang->installationFolderLangBO($array_BO);
		}
		
		if (count($array_FO) > 0)
		{
			$msg = JText::_('THE LANGUAGE HAS BEEN SUCCESSFULLY INSTALLED');
			$objJSNLang->installationFolderLangFO($array_FO);
		}
		
		$link 	= 'index.php?option=com_imageshow&controller=maintenance&type=inslangs';
		$this->setRedirect($link, $msg);
	}
	
	function saveMessage()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$status		= JRequest::getVar( 'status', array(), 'post', 'array' );
		$screen		= JRequest::getString('msg_screen');	
		$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');
		$objJSNMsg->setMessagesStatus($status, $screen);
		$link 	= 'index.php?option=com_imageshow&controller=maintenance&type=msgs';
		
		$this->setRedirect($link);
	}
	
	function refreshMessage()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
		$objJSNMsg->refreshMessage();
		$link 	= 'index.php?option=com_imageshow&controller=maintenance&type=msgs';
		$this->setRedirect($link);
	}
	
	function setStatusMsg()
	{
		JRequest::checkToken('get') or jexit( 'Invalid Token' );
		$msgID 		= JRequest::getInt('msg_id');
		$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');
		$objJSNMsg->setSeparateMessage($msgID);
	}
	
	function removeProfile()
	{		
		global $mainframe, $objectLog;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$user		= & JFactory::getUser();
		$userID		= $user->get ('id');
		$profileID 	= JRequest::getInt('configuration_id');
		$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');
		
		if (!$objJSNProfile->deleteProfile($profileID)) 
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		else
		{
			$objectLog->addLog($userID, JRequest::getURI(), '1', 'profile', 'delete');
		}
		exit();	
	}
	
	function saveParam()
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );	
		$post 		   = JRequest::get('post');
		$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');
		$objJSNProfile->saveParameters($post);
		
		$mainframe->redirect('index.php?option=com_imageshow&controller=maintenance&type=configs');	
	}
	
	function saveProfile()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );	
		$post 		   = JRequest::get('post');
		$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');
		$objJSNProfile->saveProfileInfo($post);
		jexit();	
	}

	/*
	 *  Install sample data
	 */
	function installSampledata()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$sampleData 						= JSNISFactory::getObj('classes.jsn_is_sampledata');
		$objReadXmlDetail					= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$objJSNJSON 						= JSNISFactory::getObj('classes.jsn_is_json');
		$task 								= JRequest::getWord('task', '', 'POST');
		$post 								= JRequest::get('post');
		
		if ($task == 'installSampledata')
		{
			if (!$post['agree_install_sample']) 
			{
				$sampleData->returnError('false', JText::_('PLEASE CHECK I AGREE INSTALL SAMPLE DATA'));
			}
			
			$perm = $sampleData->checkFolderPermission();
			
			if ($perm == true)
			{	
				$sampleData->checkEnvironment();
				$inforPackage 	= $objReadXmlDetail->parserXMLDetails();
				
				$sampleData->getPackageVersion(trim(strtolower($inforPackage['realName'])));
				
				$package 		= $sampleData->getPackageFromUpload();
				$unpackage 		= $sampleData->unpackPackage($package);	
				
				if ($package != '')
				{
					$sampleData->deleteISDFile($package);
				}
				
				if ($unpackage != false)
				{	
					$dataInstall = $objReadXmlDetail->parserExtXmlDetailsSampleData($unpackage.DS.FILE_XML);
					$sampleData->deleteTempFolderISD($unpackage);	
					
					if ($dataInstall != false && is_array($dataInstall))
					{
						if (trim(strtolower($inforPackage['version'])) != trim(strtolower($dataInstall['imageshow']->version)))
						{
							$sampleData->returnError('false',JText::sprintf('ERROR IMAGESHOW VERSION',$inforPackage['name'],$dataInstall['imageshow']->version,$inforPackage['version']));
						}
						
						$sampleData->executeInstallSampleData($dataInstall);
						$this->setRedirect('index.php?option=com_imageshow&controller=maintenance&type=sampledata',JText::_('INSTALL SAMPLE DATA SUCCESSFULLY'));
					}
					else
					{
						$sampleData->returnError('false','');
					}
				}
				else
				{
					$sampleData->returnError('false','UNPACK SAMPLEDATA FALSE');
				}
			}
		}
	}
	
	function deleteTheme()
	{
		global $mainframe;
		
		$themeID 			= JRequest::getInt('themeID');
		
		if ($themeID)
		{
			$objShowcaseTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
			$objShowcaseTheme->deleteThemeByPluginID($themeID);
		}
		
		$link = 'index.php?option=com_imageshow&controller=maintenance&type=themes';
		$mainframe->redirect($link);	
	}
	
	function enableDisablePlugin()
	{
		global $mainframe;
		$arrayPluginID = JRequest::getVar('pluginID');
		$publishStatus = JRequest::getInt('publish');
		
		if (count($arrayPluginID) > 0)
		{
			$pluginTable =& JTable::getInstance('plugin', 'JTable');
			
			$pluginTable->publish($arrayPluginID, $publishStatus);
		}
		
		$link = 'index.php?option=com_imageshow&controller=maintenance&type=themes';
		$mainframe->redirect($link);	
	}
	
	function installPluginManager()
	{
		$post   = JRequest::get('post');
		$model	= &$this->getModel('installer');
		$link 	= $post['redirect_link'];
		$model->install();
		$this->setRedirect($link);
	}
}