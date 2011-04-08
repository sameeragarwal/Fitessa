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
class ImageShowViewShowcase extends JView
{
		function display($tpl = null)
		{
			global $mainframe, $option;	

			$objISUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
			$mootool 		= $objISUtils->checkMootoolVersion();
			
			if($mootool == '1.2'){
				JHTML::script('mooRainbow.1.2.js','administrator/components/com_imageshow/assets/js/');
			}else{
				JHTML::script('mooRainbow.js','administrator/components/com_imageshow/assets/js/');
			}
			
			JHTML::stylesheet('mooRainbow.css','administrator/components/com_imageshow/assets/css/');
			JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
			JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');
			JHTML::script('jsn_is_utils.js','administrator/components/com_imageshow/assets/js/');
			
			JHTML::_('behavior.modal', 'a.modal');
			$lists 				= array();
			$format 			= JRequest::getVar('view_format', 'temporary');
			$showlist_id 		= JRequest::getInt('showlist_id');
			$showcaseTheme 		= JRequest::getVar('theme', 'showcasethemeclassic');			
			$model	 			=& $this->getModel();
			$items 				=& $this->get('data');
			$session 			=& JFactory::getSession();
			
			$showcaseThemeSession 	= $session->get('showcaseThemeSession');
			$session->clear('showcaseThemeSession');
			
			// GENERAL TAB BEGIN
			if($showcaseThemeSession){
				$publishShowcase = $showcaseThemeSession['published'];
			}else if($items->published != ''){
				$publishShowcase = $items->published;
			}else{
				$publishShowcase = 1;
			}
			$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $publishShowcase);	
			
			$query 		= 'SELECT ordering AS value, showcase_title AS text'
			. ' FROM #__imageshow_showcase'
			. ' ORDER BY ordering';
			$lists['ordering'] 			= JHTML::_('list.specificordering',  $items, $items->showcase_id, $query );

			$generalLinkSource = array(
				'0' => array('value' => 'image',
				'text' => JText::_('IMAGE LINK')),
				'1' => array('value' => 'showlist',
				'text' => JText::_('SHOWLIST LINK'))
			);	
			$lists['generalLinkSource'] = JHTML::_('select.genericList', $generalLinkSource, 'general_link_source', 'class="inputbox" '. '', 'value', 'text', (!empty($showcaseThemeSession['general_link_source'])) ? $showcaseThemeSession['general_link_source'] : $items->general_link_source );
					
			$generalOpenLinkIn = array(
				'0' => array('value' => 'new-browser',
				'text' => JText::_('NEW BROWSER')),
				'1' => array('value' => 'current-browser',
				'text' => JText::_('CURRENT BROWSER'))
			);	
			$lists['generalOpenLinkIn'] = JHTML::_('select.genericList', $generalOpenLinkIn, 'general_open_link_in', 'class="inputbox" '. '', 'value', 'text',(!empty($showcaseThemeSession['general_open_link_in'])) ? $showcaseThemeSession['general_open_link_in'] : $items->general_open_link_in );
			 
			$generalTitleSource = array(
				'0' => array('value' => 'image',
				'text' => JText::_('IMAGE TITLE')),
				'1' => array('value' => 'showlist',
				'text' => JText::_('SHOWLIST TITLE'))
			);	
			$lists['generalTitleSource'] = JHTML::_('select.genericList', $generalTitleSource, 'general_title_source', 'class="inputbox" '. '', 'value', 'text',(!empty($showcaseThemeSession['general_title_source'])) ? $showcaseThemeSession['general_title_source'] : $items->general_title_source );	
			
			$generalDesSource = array(
				'0' => array('value' => 'image',
				'text' => JText::_('IMAGE DESCRIPTION')),
				'1' => array('value' => 'showlist',
				'text' => JText::_('SHOWLIST DESCRIPTION'))
			);	
			$lists['generalDesSource'] = JHTML::_('select.genericList', $generalDesSource, 'general_des_source', 'class="inputbox" '. '', 'value', 'text',(!empty($showcaseThemeSession['general_des_source'])) ? $showcaseThemeSession['general_des_source'] : $items->general_des_source );
			
			$generalImagesOrder= array(
				'0' => array('value' => 'forward',
				'text' => JText::_('FORWARD')),
				'1' => array('value' => 'backward',
				'text' => JText::_('BACKWARD')),
				'2' => array('value' => 'random',
				'text' => JText::_('RANDOM'))
			);			
			$lists['generalImagesOrder'] = JHTML::_('select.genericList', $generalImagesOrder, 'general_images_order', 'class="inputbox" '. '', 'value', 'text', (!empty($showcaseThemeSession['general_images_order'])) ? $showcaseThemeSession['general_images_order'] : $items->general_images_order );
			// GENERAL TAB END
			
			$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
			$countShowlist 		= $objJSNShowlist->countShowList();
			$generalData 		= array();
			
			if(!empty($showcaseThemeSession))
      		{
      			$generalData['generalTitle'] 			= $showcaseThemeSession['showcase_title'];
      			$generalData['generalWidth'] 			= $showcaseThemeSession['general_overall_width'];
      			$generalData['generalHeight'] 			= $showcaseThemeSession['general_overall_height'];
      			$generalData['generalCornerRadius'] 	= $showcaseThemeSession['general_round_corner_radius'];
      			$generalData['generalBorderStroke'] 	= $showcaseThemeSession['general_border_stroke'];
      			$generalData['generalBgColor']	 		= $showcaseThemeSession['background_color'];
      			$generalData['generalBorderColor']	 	= $showcaseThemeSession['general_border_color'];
      			$generalData['generalImageLoad'] 		= $showcaseThemeSession['general_number_images_preload'];
      		}
      		else if($items->general_overall_width)
      		{
      			$generalData['generalTitle'] 			= htmlspecialchars($items->showcase_title);
      			$generalData['generalWidth'] 			= $items->general_overall_width;
      			$generalData['generalHeight'] 			= $items->general_overall_height;
      			$generalData['generalCornerRadius'] 	= $items->general_round_corner_radius;
      			$generalData['generalBorderStroke'] 	= $items->general_border_stroke;
      			$generalData['generalBgColor']	 		= $items->background_color;
      			$generalData['generalBorderColor']	 	= $items->general_border_color;
      			$generalData['generalImageLoad'] 		= $items->general_number_images_preload;
      		}
      		else
      		{
      			$generalData['generalTitle'] 			= '';
      			$generalData['generalWidth'] 			= '100%';
      			$generalData['generalHeight'] 			= '450';
      			$generalData['generalCornerRadius']  	= 0;
      			$generalData['generalBorderStroke']  	= 2;
      			$generalData['generalBgColor']			= '#ffffff';
      			$generalData['generalBorderColor']		= '#000000';
      			$generalData['generalImageLoad']  		= 3;
      		}
			
			$this->assignRef('generalData', $generalData);
			$this->assignRef('countShowlist',$countShowlist);			
			$this->assignRef('lists', $lists);
			$this->assignRef('items', $items);
			
			parent::display($tpl);
		}
}
?>