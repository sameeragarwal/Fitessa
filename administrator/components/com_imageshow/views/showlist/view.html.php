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
class ImageShowViewShowlist extends JView
{
		function display($tpl = null)
		{
			global $mainframe, $option;			
			$objJSNImages 		= JSNISFactory::getObj('classes.jsn_is_images');
			JHTML::_('behavior.modal', 'a.modal');
			JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
			JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');
			JHTML::script('jsn_is_utils.js','administrator/components/com_imageshow/assets/js/');
			
			$model 	=& $this->getModel();

			$document =& JFactory::getDocument();
			$document->addScriptDeclaration("
				function getModuleID(moduleID, moduleTitle, object)
				{
					window.parent.document.getElementById(object+'_id').value = moduleID;
					window.parent.document.getElementById(object+'_title').value = moduleTitle;
					window.parent.document.getElementById('sbox-window').close();
				};
				
				function jSelectArticle(id, title, object) 
				{
					document.getElementById(object + '_id').value = id;
					document.getElementById(object + '_name').value = title;
					document.getElementById('sbox-window').close();
				}
			");	
			
			$lists 	= array();
			$task 	= JRequest::getVar('task');
		
			switch ($task)
			{
				case 'modules':
					$moduleData = $model->getModules();
					$this->assignRef('moduleData', $moduleData);
				break;
				
				default:
					$items 	=& $this->get('data');
					if($items->showlist_id != 0 && $items->showlist_id != '')
					{
						if($objJSNImages->checkImageLimition($items->showlist_id))
						{
							$msg = JText::_('YOU HAVE REACHED THE LIMITATION OF 10 IMAGES IN FREE EDITION');
							JError::raiseNotice(100, $msg);
						}
					}					
					$alternativeContentCombo = array(
						'0' => array('value' => '4', 
						'text' => JText::_('HTML/JS GALLERY')),					
						'1' => array('value' => '0', 
						'text' => JText::_('FLASH PLAYER REQUIREMENT MESSAGE')),
						'2' => array('value' => '3', 
						'text' => JText::_('STATIC IMAGE')),
						'3' => array('value' => '2', 
						'text' => JText::_('JOOMLA ARTICLE')),												
						'4' => array('value' => '1',
						'text' => JText::_('JOOMLA MODULE'))
					);
					$seoContent = array(
						'0' => array('value' => '0',
						'text' => JText::_('SHOWLIST AND IMAGES DETAILS')),
						'1' => array('value' => '1',
						'text' => JText::_('JOOMLA ARTICLE')),
						'2' => array('value' => '2',
						'text' => JText::_('JOOMLA MODULE'))
					);
					$authorizationCombo = array(
						'0' => array('value' => '0',
						'text' => JText::_('NO MESSAGE')),
						'1' => array('value' => '1',
						'text' => JText::_('JOOMLA ARTICLE'))				
					); 
					$lists['alternativeContentCombo'] = JHTML::_('select.genericList', $alternativeContentCombo, 'alternative_status', 'class="inputbox" onchange="JSNISImageShow.ShowListCheckAlternativeContent();"'. '', 'value', 'text', ($items->alternative_status != '')?$items->alternative_status:4);	
					$lists['seoContent'] = JHTML::_('select.genericList', $seoContent, 'seo_status', 'class="inputbox" onchange="JSNISImageShow.ShowListCheckSeoContent();"'. '', 'value', 'text', $items->seo_status );
					$lists['authorizationCombo'] = JHTML::_('select.genericList', $authorizationCombo, 'authorization_status', 'class="inputbox" onchange="JSNISImageShow.ShowListCheckAuthorizationContent();"'. '', 'value', 'text', $items->authorization_status );	
					
					$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', ($items->published !='')?$items->published:1 );				
					$isNew				= ($model->_id < 1);
					$query 				= 'SELECT ordering AS value, showlist_title AS text'
											. ' FROM #__imageshow_showlist'
											. ' ORDER BY ordering';
					$lists['ordering'] 			= JHTML::_('list.specificordering',  $items, $items->showlist_id, $query );		
					//$lists['access'] 			= JHTML::_('list.accesslevel',  $items );	
					$lists['access']= $model->accesslevel($items);
					$this->assignRef('lists', $lists);
					$this->assignRef('items', $items);
				break;
				
			}
			
			parent::display($tpl);
		}
}
?>