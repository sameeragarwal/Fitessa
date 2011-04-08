<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class ImageShowViewMaintenance extends JView
{
	function display($tpl = null) 
	{		
		global $mainframe, $option;
		JHTML::_('behavior.modal','a.modal');	
		JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');	
		JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
		
		$screen				= $mainframe->getUserStateFromRequest('com_imageshow.maintenance.msg_screen', 'msg_screen', '', 'string');
		$profileTitle 		= $mainframe->getUserStateFromRequest('com_imageshow.maintenance.configuration_title', 'config_title', '', 'string');
		$profileSource		= $mainframe->getUserStateFromRequest('com_imageshow.maintenance.img_source', 'img_source', '', 'int');
				
		$lists 		= array();
		$type  		= JRequest::getWord('type','backup');
		$profileID  = JRequest::getInt('configuration_id');
		$model 		=& $this->getModel();
		$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');	
		$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');	
		switch($type)
		{
			case 'inslangs':
				$objJSNLang	 = JSNISFactory::getObj('classes.jsn_is_language');
				$arrayFolder = $objJSNLang->megerArrayFolder();
				$this->assignRef('arrayFolder', $arrayFolder);
			break;
			case 'msgs':
				$arrayScreen 			= $objJSNMsg->listScreenDisplayMsg();
				$lists['arrayScreen'] 	= JHTML::_('select.genericList', $arrayScreen, 'msg_screen', 'class="inputbox" onchange="document.adminForm.submit();"'. '', 'value', 'text', $screen);
				$getMessages 			= $objJSNMsg->getMessages($screen);
				$this->assignRef('messages', $getMessages);
				$this->assignRef('screen', $screen);
			break;
			case 'profiles':
				$objJSNProfile->autoDelProfileSrcFolder();
				$imgSources = array(
						'0' => array('value' => '0',
						'text' => '- '.JText::_('SELECT SOURCE TYPE').' -'),
						'1' => array('value' => '2',
						'text' => JText::_('FLICKR')),
						'2' => array('value' => '3',
						'text' => JText::_('PICASA'))
				);
				$lists['profileTitle'] 	= $profileTitle;	
				$lists['imgSource'] = JHTML::_('select.genericList', $imgSources, 'img_source', 'id="img_source" class="inputbox" onchange="document.adminForm.submit();"'. '', 'value', 'text', $profileSource);	
				$getProfiles 		= $objJSNProfile->getProfiles($profileTitle, $profileSource);
				$this->assignRef('profiles', $getProfiles);				
			break;
			case 'editprofile':
				$fickrImageSize = array(
					'0' => array('value' => '0',
					'text' => JText::_('SMALL')),
					'1' => array('value' => '1',
					'text' => JText::_('MEDIUM')),
					'2' => array('value' => '2',
					'text' => JText::_('LARGE'))
				);				
				$getProfileInfo		= $objJSNProfile->getProfileInfo($profileID);
				$this->assignRef('profileinfo', $getProfileInfo);
				$lists['fickrImageSize'] = JHTML::_('select.genericList', $fickrImageSize, 'flickr_image_size', 'class="inputbox"'. '', 'value', 'text', $getProfileInfo->flickr_image_size);		
			break;	
			case 'configs':
				$parameters = $objJSNProfile->getParameters();
				
				$rootURL = array(
						'0' => array('value' => '1',
						'text' => JText::_('URL BASE REQUEST')),
						'1' => array('value' => '2',
						'text' => JText::_('JURI BASE')));	
						
				$outputMethod = array(
						'0' => array('value' => '0',
						'text' => JText::_('BY TAGS OBJECT AND EMBED')),
						'1' => array('value' => '1',
						'text' => JText::_('BY SWFOBJECT SCRIPT')));	
															
				$lists['rootURL'] 				= JHTML::_('select.genericList', $rootURL, 'root_url', 'class="inputbox"'. '', 'value', 'text', (@$parameters->root_url == '')?'1':@$parameters->root_url);
				$lists['generalSwfLibrary'] 	= JHTML::_('select.genericList', $outputMethod ,'general_swf_library','class="inputbox"', 'value', 'text', @$parameters->general_swf_library);	

				$this->assignRef('parameters', $parameters);					
			break;
			case 'sampledata':
				$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
				$sampleData 		= JSNISFactory::getObj('classes.jsn_is_sampledata');
				$objReadXmlDetail	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
				$inforPackage 		= $objReadXmlDetail->parserXMLDetails();
				$sampleData->getPackageVersion(trim(strtolower($inforPackage['realName'])));
				$objJSNUtils->checkTmpFolderWritable();															
			break;
			case 'backup':
				$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
			    $objJSNUtils->checkTmpFolderWritable();
			break;
			case 'themes':
				$filterState 		= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.filter_state', 'filter_state', '', 'word');
				$filterOrder		= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.filter_order','filter_order', '', 'cmd');
				$filterOrderDir		= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.filter_order_Dir',	'filter_order_Dir',	'',	'word');
				$filterPluginName	= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.plugin_name', 'plugin_name', '', 'string');
				
				$lists['state']		= JHTML::_('grid.state',  $filterState);
				$lists['order_Dir'] = $filterOrderDir;
				$lists['order'] 	= $filterOrder;	
				$pluginModel 		=& JModel::getInstance('plugins', 'imageshowmodel');
				
				$listJSNPlugins		= $pluginModel->getData();
				$pagination 		= $pluginModel->getPagination();
				
				$this->assignRef('lists', $lists);
				$this->assignRef('pagination', $pagination);			
				$this->assignRef('filterPluginName', $filterPluginName);
				$this->assignRef('listJSNPlugins', $listJSNPlugins);
			break;
			default:
		    break;  
		}
		$this->assignRef('lists', $lists);	
		parent::display($tpl);
	}
}