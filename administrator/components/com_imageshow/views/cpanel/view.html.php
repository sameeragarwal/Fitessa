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
class ImageShowViewCpanel extends JView
{
		function display($tpl = null)
		{
			global $mainframe, $option;	
			JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
			JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');
			JHTML::_('behavior.modal', 'a.modal');	
			$lists 			= array();
			$model			= $this->getModel();
			$document 		=& JFactory::getDocument();
			$objJSNShowcase = JSNISFactory::getObj('classes.jsn_is_showcase');
			$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
			$objJSNLog 		= JSNISFactory::getObj('classes.jsn_is_log');
			$objJSNUtils	= JSNISFactory::getObj('classes.jsn_is_utils');
			$objJSNTip 		= JSNISFactory::getObj('classes.jsn_is_tip');
			$totalShowlist 	= $objJSNShowlist->countShowlist();	
			$totalShowcase 	= $objJSNShowcase->getTotalShowcase();
			$totalProfile 	= $objJSNUtils->getTotalProfile();
			$allContentTips = $objJSNTip->getAllContentTips($document->getLanguage());
			$objJSNLog->deleteRecordLog();
			
			$checkModule 			= $objJSNUtils->checkIntallModule();
			$checkPluginContent 	= $objJSNUtils->checkIntallPluginContent();
			$checkPluginSystem 		= $objJSNUtils->checkIntallPluginSystem();
			
			if ($checkModule == false || $checkPluginContent == false || $checkPluginSystem == false)
			{
				if ($checkModule == false)
				{
					$msgNotice [] = '<li>&nbsp;&nbsp;-&nbsp;&nbsp;'.JText::_('JSN IMAGESHOW MODULE').'</li>';
				}
				
				if ($checkPluginSystem == false)
				{
					$msgNotice [] = '<li>&nbsp;&nbsp;-&nbsp;&nbsp;'.JText::_('JSN IMAGESHOW SYSTEM PLUGIN').'</li>';
				}
				
				if ($checkPluginContent == false)
				{
					$msgNotice [] = '<li>&nbsp;&nbsp;-&nbsp;&nbsp;'.JText::_('JSN IMAGESHOW CONTENT PLUGIN').'</li>';
				}
								
				$strMsg = implode('', $msgNotice);
				JError::raiseWarning(100, JText::sprintf('FOLLOWING ELEMENTS ARE NOT INSTALLED: %S', $strMsg));		
			}
			
			$presentationMethods = array(
				'0' => array('value' => '',
				'text' => '- '.JText::_('SELECT PRESENTATION METHOD').' -'),
				'1' => array('value' => 'menu',
				'text' => JText::_('VIA MENU ITEM COMPONENT')),
				'2' => array('value' => 'module',
				'text' => JText::_('IN MODULE POSITION MODULE')),
				'3' => array('value' => 'plugin',
				'text' => JText::_('INSIDE ARTICLE CONTENT PLUGIN'))				
			);
				
			$lists['presentationMethods'] 	= JHTML::_('select.genericList', $presentationMethods, 'presentation_method', 'class="jsnis-gallery-selectbox" onchange="choosePresentMethode();" disabled="disabled"'. '', 'value', 'text', "" );			
			$pluginContentInfo 				= $objJSNUtils->getPluginContentInfo();
			$languageObj 					= $document->getLanguage();
			$lists['showlist'] 				= $objJSNShowlist->renderShowlistComboBox('0', JText::_('SELECT SHOWLIST'), 'showlist_id', 'class="jsnis-gallery-selectbox" onchange="enableButton();"');
			$lists['showcase'] 				= $objJSNShowcase->renderShowcaseComboBox('0', JText::_('SELECT SHOWCASE'), 'showcase_id', 'class="jsnis-gallery-selectbox" onchange="enableButton();"');
			$lists['menu'] 					= $objJSNUtils->renderMenuComboBox(null, 'Select menu' ,'menutype', 'class="jsnis-gallery-selectbox jsnis-menutype-selection" onchange="createViaMenu();"');
			
			$this->assignRef('pluginContentInfo', $pluginContentInfo);
			$this->assignRef('totalShowlist', $totalShowlist[0]);
			$this->assignRef('totalShowcase', $totalShowcase[0]);
			$this->assignRef('totalProfile', $totalProfile[0]);
			$this->assignRef('currentLanguage',	$languageObj);
			$this->assignRef('lists',	$lists);
			$this->assignRef('allContentTips', $allContentTips);
			parent::display($tpl);
		}
}