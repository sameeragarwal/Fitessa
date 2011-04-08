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
			$objJSNShowcaseTheme = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
			$objJSNShowcaseTheme->importTableByThemeName($this->_showcaseThemeName);
			$objJSNShowcaseTheme->importModelByThemeName($this->_showcaseThemeName);
			$modelShowcaseTheme =& JModel::getInstance($this->_showcaseThemeName);
			$items = $modelShowcaseTheme->_initData();
			
			/**
			 * /////////////////////////////////////////////////////////Image Panel Begin////////////////////////////////////////////////////////////////////////////
			 */
			//Image Presentation Begin
				//Fit Begin
				$classImagePanel = 'imagePanel';
				$imgPanelPresentationMode = array(
					'0' => array('value' => 'fit-in',
					'text' => JText::_( 'Fit In')),
					'1' => array('value' => 'expand-out',
					'text' => JText::_( 'Expand Out'))
				);
				$lists['imgPanelPresentationMode'] = JHTML::_('select.genericList', $imgPanelPresentationMode, 'imgpanel_presentation_mode', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', $items->imgpanel_presentation_mode );
				
				$imgPanelImgTransitionTypeFit = array(			
					'0' => array('value' => 'random',
					'text' => JText::_( 'Random')),
					'1' => array('value' => 'fade',
					'text' => JText::_( 'Fade')),
					'2' => array('value' => 'push',
					'text' => JText::_( 'Push')),
					'3' => array('value' => 'zoom',
					'text' => JText::_( 'Zoom')),
					'4' => array('value' => 'flip3d',
					'text' => JText::_( '3D Flip')),
					'5' => array('value' => 'page-curl',
					'text' => JText::_( 'Page Curl')),
					'6' => array('value' => 'page-flip',
					'text' => JText::_( 'Page Flip'))
				);
				$lists['imgPanelImgTransitionTypeFit'] = JHTML::_('select.genericList', $imgPanelImgTransitionTypeFit, 'imgpanel_img_transition_type_fit', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_img_transition_type_fit!='')?$items->imgpanel_img_transition_type_fit:'random');
				
				$imgPanelImgClickActionFit = array(
					'0' => array('value' => 'no-action',
					'text' => JText::_( 'No Action')),
					'1' => array('value' => 'image-zooming',
					'text' => JText::_( 'Image Zooming')),
					'2' => array('value' => 'open-image-link',
					'text' => JText::_( 'Open Image Link'))
				);							
				$lists['imgPanelImgClickActionFit'] = JHTML::_('select.genericList', $imgPanelImgClickActionFit, 'imgpanel_img_click_action_fit', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_img_click_action_fit!='')?$items->imgpanel_img_click_action_fit:'image-zooming');
				//Fit End
						
				//Expand Begin
				$imgPanelImgTransitionTypeExpand = array(
					'0' => array('value' => 'random',
					'text' => JText::_( 'Random')),
					'1' => array('value' => 'cross-fade',
					'text' => JText::_( 'Cross Fade')),
					'2' => array('value' => 'linear-fade',
					'text' => JText::_( 'Linear Fade')),
					'3' => array('value' => 'radial-fade',
					'text' => JText::_( 'Radial Fade')),
					'4' => array('value' => 'black-dim',
					'text' => JText::_( 'Black Dim')),
					'5' => array('value' => 'white-burn',
					'text' => JText::_( 'White Burn'))
				);
				$lists['imgPanelImgTransitionTypeExpand'] = JHTML::_('select.genericList', $imgPanelImgTransitionTypeExpand, 'imgpanel_img_transition_type_expand', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_img_transition_type_expand!='')?$items->imgpanel_img_transition_type_expand:'random' );
				$imgPanelImgMotionTypeExpand = array(
					'0' => array('value' => 'no-motion',
					'text' => JText::_( 'No Motion')),
					'1' => array('value' => 'center-zoom-in',
					'text' => JText::_( 'Center Zoom In')),
					'2' => array('value' => 'center-zoom-out',
					'text' => JText::_( 'Center Zoom Out')),
					'3' => array('value' => 'center-random',
					'text' => JText::_( 'Center Random')),
					'4' => array('value' => 'edge-zoom-in',
					'text' => JText::_( 'Edge Zoom In')),
					'5' => array('value' => 'edge-zoom-out',
					'text' => JText::_( 'Edge Zoom Out')),
					'6' => array('value' => 'edge-random',
					'text' => JText::_( 'Edge Random'))
				);
				$lists['imgPanelImgMotionTypeExpand'] = JHTML::_('select.genericList', $imgPanelImgMotionTypeExpand, 'imgpanel_img_motion_type_expand', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_img_motion_type_expand!='')?$items->imgpanel_img_motion_type_expand:'center-random' );			
				$imgPanelImgClickActionExpand = array(
					'0' => array('value' => 'no-action',
					'text' => JText::_( 'No Action')),
					'1' => array('value' => 'image-zooming',
					'text' => JText::_( 'Image Zooming')),
					'2' => array('value' => 'open-image-link',
					'text' => JText::_( 'Open Image Link'))
				);
				$lists['imgPanelImgClickActionExpand'] = JHTML::_('select.genericList', $imgPanelImgClickActionExpand, 'imgpanel_img_click_action_expand', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_img_click_action_expand!='')?$items->imgpanel_img_click_action_expand:'open-image-link' );							
				//Expand End
			//Image Presentation End
			 
			//Background Begin
			$imgPanelBgType = array(
				'0' => array('value' => '1',
				'text' => JText::_( 'Solid Color')),
				'1' => array('value' => '2',
				'text' => JText::_( 'Linear Gradient')),
				'2' => array('value' => '3',
				'text' => JText::_( 'Radial Gradient')),
				'3' => array('value' => '4',
				'text' => JText::_( 'Pattern')),
				'4' => array('value' => '5',
				'text' => JText::_( 'Image'))
			);
			$lists['imgPanelBgType'] = JHTML::_('select.genericList', $imgPanelBgType, 'imgpanel_bg_type', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', ($items->imgpanel_bg_type!='')?$items->imgpanel_bg_type:2  );					
			//Background End
			
			//Watermark Presentation Begin
			$lists['imgPanelShowWatermark'] = JHTML::_('select.booleanlist', 'imgpanel_show_watermark','class="inputbox '.$classImagePanel.'"', $items->imgpanel_show_watermark);
			$imgPanelWatermarkPosition = array(
				'0' => array('value' => 'center',
				'text' => JText::_(  'Center' )),
				'1' => array('value' => 'top-left',
				'text' => JText::_(  'Top Left' )),
				'2' => array('value' => 'top-right',
				'text' => JText::_( 'Top Right' )),
				'3' => array('value' => 'bottom-left',
				'text' => JText::_( 'Bottom Left' )),
				'4' => array('value' => 'bottom-right',
				'text' => JText::_( 'Bottom Right' ))
			);
			$lists['imgPanelWatermarkPosition'] = JHTML::_('select.genericList', $imgPanelWatermarkPosition, 'imgpanel_watermark_position', 'class="inputbox '.$classImagePanel.'" onChange="JSNISClassicTheme.ChangeWatermark();"'. '', 'value', 'text', ($items->imgpanel_watermark_position!='')?$items->imgpanel_watermark_position:'top-right' );	
			//Watermark Presentation End
			
			//Overlay Effect Begin
			$imgPanelOverlayEffectType = array(
				'0' => array('value' => 'horizontal-floating-bar',
				'text' => JText::_( 'Horizontal Floating Bar')),
				'1' => array('value' => 'vertical-floating-bar',
				'text' => JText::_( 'Vertical Floating Bar')),
				'2' => array('value' => 'winter-snow',
				'text' => JText::_( 'Winter Snow')),
				'3' => array('value' => 'old-movie',
				'text' => JText::_( 'Old Movie')),
				'4' => array('value' => 'water-bubbles',
				'text' => JText::_( 'Water Bubbles'))
			);
			$lists['imgPanelOverlayEffectType'] = JHTML::_('select.genericList', $imgPanelOverlayEffectType, 'imgpanel_overlay_effect_type', 'class="inputbox '.$classImagePanel.'" '. '', 'value', 'text', $items->imgpanel_overlay_effect_type );
			$lists['imgPanelShowOverlayEffect'] = JHTML::_('select.booleanlist', 'imgpanel_show_overlay_effect','class="inputbox '.$classImagePanel.'"', $items->imgpanel_show_overlay_effect);
			//Overlay Effect End
			
			//Inner Shawdow Begin
			$lists['imgPanelShowInnerShawdow'] = JHTML::_('select.booleanlist', 'imgpanel_show_inner_shawdow','class="inputbox '.$classImagePanel.'"', ($items->imgpanel_show_inner_shawdow!='')?$items->imgpanel_show_inner_shawdow:1);		
			//Inner Shawdow End
				
			/**
			 * /////////////////////////////////////////////////////////Image Panel End////////////////////////////////////////////////////////////////////////////
			 */	
					
			/**
			 * /////////////////////////////////////////////////////////////////////////////////Thumbnail Panel Begin//////////////////////////////////////////////////////////// 
			 */	
				//General Begin
				$classThumbPanel = 'thumbnailPanel';
				$thumbPanelStatus = array(
					'0' => array('value' => 'auto',
					'text' => JText::_( 'Auto Show/Hide')),
					'1' => array('value' => 'on',
					'text' => JText::_( 'Always On')),
					'2' => array('value' => 'off',
					'text' => JText::_( 'Off'))
				);
				$lists['thumbPanelShowPanel'] = JHTML::_('select.genericList', $thumbPanelStatus, 'thumbpanel_show_panel', 'class="inputbox '.$classThumbPanel.'" '. '', 'value', 'text', $items->thumbpanel_show_panel);	
			 	$thumbPanelPanelPosition = array(
					'0' => array('value' => 'top',
					'text' => JText::_( 'Top')),
					'1' => array('value' => 'bottom',
					'text' => JText::_( 'Bottom'))
				);
				$lists['thumbPanelPanelPosition'] = JHTML::_('select.genericList', $thumbPanelPanelPosition, 'thumbpanel_panel_position', 'class="inputbox '.$classThumbPanel.'" '. '', 'value', 'text', (!empty($items->thumbpanel_panel_position))?$items->thumbpanel_panel_position:'bottom' );					
				$lists['thumbPanelCollapsiblePosition'] = JHTML::_('select.booleanlist', 'thumbpanel_collapsible_position','class="inputbox '.$classThumbPanel.'"', (!empty($items->thumbpanel_collapsible_position))?$items->thumbpanel_collapsible_position:1);
				$thumbPanelThumbBrowsingMode = array(
					'0' => array('value' => 'pagination',
					'text' => JText::_( 'Pagination')),
					'1' => array('value' => 'sliding',
					'text' => JText::_( 'Sliding'))
				);
				$lists['thumbPanelThumbBrowsingMode'] = JHTML::_('select.genericList', $thumbPanelThumbBrowsingMode, 'thumbpanel_thumb_browsing_mode', 'class="inputbox '.$classThumbPanel.'" onchange="JSNISClassicTheme.ShowcaseSwitchBrowsingMode();"'. '', 'value', 'text', $items->thumbpanel_thumb_browsing_mode );			
				$lists['thumbPanelShowThumbStatus'] = JHTML::_('select.booleanlist', 'thumbpanel_show_thumb_status','class="inputbox '.$classThumbPanel.'"', (!empty($items->thumbpanel_show_thumb_status))?$items->thumbpanel_show_thumb_status:1);
				//General End
				 	
				//Thumbnail Begin
				$thumbPanelPresentationMode = array(
					'0' => array('value' => 'image',
					'text' => JText::_( 'Image')),
					'1' => array('value' => 'number',
					'text' => JText::_( 'Number'))
				);
				$lists['thumbPanelPresentationMode'] = JHTML::_('select.genericList', $thumbPanelPresentationMode, 'thumbpanel_presentation_mode', 'class="inputbox '.$classThumbPanel.'" '. '', 'value', 'text', $items->thumbpanel_presentation_mode );						
				$lists['thumbPanelEnableBigThumb'] = JHTML::_('select.booleanlist', 'thumbpanel_enable_big_thumb','class="inputbox '.$classThumbPanel.'"', (!empty($items->thumbpanel_enable_big_thumb))?$items->thumbpanel_enable_big_thumb:1);
				
				//Thumbnail End
			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////Thumbnail Panel End//////////////////////////////////////////////////////////////////////////////////
			 */	
			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////Information Panel Begin//////////////////////////////////////////////////////////////////////////////////
			 */
				$classInfoPanel = 'informationPanel';
				//General Begin 
				$infoPanelPanelPosition = array(
					'0' => array('value' => 'top',
					'text' => JText::_( 'Top')),
					'1' => array('value' => 'bottom',
					'text' => JText::_( 'Bottom'))
				);
				$lists['infoPanelPanelPosition'] = JHTML::_('select.genericList', $infoPanelPanelPosition, 'infopanel_panel_position', 'class="inputbox '.$classInfoPanel.'" '. '', 'value', 'text', $items->infopanel_panel_position );
				
				$infoPanelPresentation = array(
					'0' => array('value' => 'auto',
					'text' => JText::_( 'Auto Show/Hide')),
					'1' => array('value' => 'on',
					'text' => JText::_( 'Always On')),
					'2' => array('value' => 'off',
					'text' => JText::_( 'Off'))
				);			
				$lists['infoPanelPresentation'] = JHTML::_('select.genericList', $infoPanelPresentation, 'infopanel_presentation', 'class="inputbox '.$classInfoPanel.'" '. '', 'value', 'text', $items->infopanel_presentation );					
				//General End
				 	
				//Image Title Begin 
			 	$lists['infoPanelShowTitle'] = JHTML::_('select.booleanlist', 'infopanel_show_title','class="inputbox '.$classInfoPanel.'"', (!empty($items->infopanel_show_title))?$items->infopanel_show_title:0);
				
				$infoPanelPanelClickAction = array(
					'0' => array('value' => 'no-action',
					'text' => JText::_( 'No Action')),
					'1' => array('value' => 'open-image-link',
					'text' => JText::_( 'Open Image Link'))
				);	
				$lists['infoPanelPanelClickAction'] = JHTML::_('select.genericList', $infoPanelPanelClickAction, 'infopanel_panel_click_action', 'class="inputbox '.$classInfoPanel.'" '. '', 'value', 'text', $items->infopanel_panel_click_action );	
				//Image Title End
				 
				//Image Description Begin 
				$lists['infoPanelShowDes'] = JHTML::_('select.booleanlist', 'infopanel_show_des','class="inputbox '.$classInfoPanel.'"', (!empty($items->infopanel_show_des))?$items->infopanel_show_des:0);
				//Image Description End 
			
				//Link Begin 
					$lists['infoPanelShowLink'] = JHTML::_('select.booleanlist', 'infopanel_show_link','class="inputbox '.$classInfoPanel.'"', (!empty($items->infopanel_show_link))?$items->infopanel_show_link:0);
				//Link End 
			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////Information Panel End//////////////////////////////////////////////////////////////////////////////////
			 */	

			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////Toolbar Panel Begin//////////////////////////////////////////////////////////////////////////////////
			 */
				$classToolBarPanel = 'toolbarPanel';
				//General Begin
				$toolBarPanelPanelPosition = array(
					'0' => array('value' => 'top',
					'text' => JText::_( 'Top')),
					'1' => array('value' => 'bottom',
					'text' => JText::_( 'Bottom'))
				);
				$lists['toolBarPanelPanelPosition'] = JHTML::_('select.genericList', $toolBarPanelPanelPosition, 'toolbarpanel_panel_position', 'class="inputbox '.$classToolBarPanel.'" '. '', 'value', 'text', ($items->toolbarpanel_panel_position!='')?$items->toolbarpanel_panel_position:'bottom' );			
				
				$toolBarPanelPresentation = array(
					'0' => array('value' => 'auto',
					'text' => JText::_( 'Auto Show/Hide')),
					'1' => array('value' => 'on',
					'text' => JText::_( 'Always On')),
					'2' => array('value' => 'off',
					'text' => JText::_( 'Off'))
				);
				$lists['toolBarPanelPresentation'] = JHTML::_('select.genericList', $toolBarPanelPresentation, 'toolbarpanel_presentation', 'class="inputbox '.$classToolBarPanel.'" '. '', 'value', 'text', ($items->toolbarpanel_presentation!=''?$items->toolbarpanel_presentation:'auto') );
				//General End
				
				//Functions Begin
			 	$lists['toolBarPanelShowImageNavigation'] = JHTML::_('select.booleanlist', 'toolbarpanel_show_image_navigation','class="inputbox '.$classToolBarPanel.'"', (!empty($items->toolbarpanel_show_image_navigation))?$items->toolbarpanel_show_image_navigation:0);	
				$lists['toolBarPanelSlideShowPlayer'] = JHTML::_('select.booleanlist', 'toolbarpanel_slideshow_player','class="inputbox '.$classToolBarPanel.'"', (!empty($items->toolbarpanel_slideshow_player))?$items->toolbarpanel_slideshow_player:0);	
				$lists['toolBarPanelShowFullscreenSwitcher'] = JHTML::_('select.booleanlist', 'toolbarpanel_show_fullscreen_switcher','class="inputbox '.$classToolBarPanel.'"', (!empty($items->toolbarpanel_show_fullscreen_switcher))?$items->toolbarpanel_show_fullscreen_switcher:0);	
				$lists['toolBarPanelShowTooltip'] = JHTML::_('select.booleanlist', 'toolbarpanel_show_tooltip','class="inputbox '.$classToolBarPanel.'"', $items->toolbarpanel_show_tooltip);
				//Functions End
			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////Toolbar Panel End//////////////////////////////////////////////////////////////////////////////////
			 */	
			
			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////SlideShow Begin//////////////////////////////////////////////////////////////////////////////////
			 */
				$classSlideShowPanel = 'slideshowPanel';
				//Action on Slideshow Start Begin
			 	$slideShowPresentationMode = array(
					'0' => array('value' => 'fit-in',
					'text' => JText::_( 'Fit In')),
					'1' => array('value' => 'expand-out',
					'text' => JText::_( 'Expand Out'))
				);
				$lists['slideShowPresentationMode'] = JHTML::_('select.genericList', $slideShowPresentationMode, 'slideshow_presentation_mode', 'class="inputbox '.$classSlideShowPanel.'" onChange="JSNISClassicTheme.SwitchPresentationModeKenBurn();"'. '', 'value', 'text', (!empty($items->slideshow_presentation_mode))?$items->slideshow_presentation_mode:'expand-out' );
							
				$lists['slideShowEnableKenBurnEffect'] = JHTML::_('select.booleanlist', 'slideshow_enable_ken_burn_effect','class="inputbox '.$classSlideShowPanel.'" onClick="JSNISClassicTheme.SwitchKenBurnPresentationMode();"', $items->slideshow_enable_ken_burn_effect);
				
				$slideShowShowThumbPanel = array(
					'0' => array('value' => 'auto',
					'text' => JText::_( 'Auto Show/Hide')),
					'1' => array('value' => 'on',
					'text' => JText::_( 'Always on')),
					'2' => array('value' => 'off',
					'text' => JText::_( 'Off' )),
					'3' => array('value' => 'inherited',
					'text' => JText::_( 'Inherited'))
				);			
				
				$lists['slideShowShowThumbPanel'] = JHTML::_('select.genericList', $slideShowShowThumbPanel, 'slideshow_show_thumb_panel','class="inputbox '.$classSlideShowPanel.'"', 'value', 'text', $items->slideshow_show_thumb_panel);
				$slideShowShowOverlayEffect = array(
					'0' => array('value' => '0',
					'text' => JText::_( 'No')),
					'1' => array('value' => '1',
					'text' => JText::_( 'Yes')),
					'2' => array('value' => '2',
					'text' => JText::_( 'Inherited' ))
				);	
				$lists['slideShowShowOverlayEffect'] = JHTML::_('select.genericList', $slideShowShowOverlayEffect, 'slideshow_show_overlay_effect','class="inputbox '.$classSlideShowPanel.'"', 'value', 'text', $items->slideshow_show_overlay_effect);
				$slideShowShowImageNavigation = array(
					'0' => array('value' => '0',
					'text' => JText::_( 'No')),
					'1' => array('value' => '1',
					'text' => JText::_( 'Yes')),
					'2' => array('value' => '2',
					'text' => JText::_( 'Inherited' ))
				);				
				$lists['slideShowShowImageNavigation'] = JHTML::_('select.genericList', $slideShowShowImageNavigation, 'slideshow_show_image_navigation','class="inputbox '.$classSlideShowPanel.'"', 'value', 'text', $items->slideshow_show_image_navigation);
				$slideShowShowWaterMark = array(
					'0' => array('value' => '0',
					'text' => JText::_( 'No')),
					'1' => array('value' => '1',
					'text' => JText::_( 'Yes')),
					'2' => array('value' => '2',
					'text' => JText::_( 'Inherited' ))
				);			
				$lists['slideShowShowWaterMark'] = JHTML::_('select.genericList', $slideShowShowWaterMark, 'slideshow_show_watermark','class="inputbox '.$classSlideShowPanel.'"', 'value', 'text', $items->slideshow_show_watermark);
				//Action on Slideshow Start End
				
				//Slideshow Process Begin
			 
				$lists['slideShowProcess'] = JHTML::_('select.booleanlist', 'slideshow_process','class="inputbox '.$classSlideShowPanel.'"', $items->slideshow_process);
				$lists['slideShowShowStatus'] = JHTML::_('select.booleanlist', 'slideshow_show_status','class="inputbox '.$classSlideShowPanel.'"', (!empty($items->slideshow_show_status))?$items->slideshow_show_status:1);
				$lists['slideShowLooping'] = JHTML::_('select.booleanlist', 'slideshow_looping','class="inputbox '.$classSlideShowPanel.'"', $items->slideshow_looping);				
				//Slideshow Process End
			 
			/**
			 * ///////////////////////////////////////////////////////////////////////////////////////SlideShow End//////////////////////////////////////////////////////////////////////////////////
			 */	
			
			$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
			$lists['showlist'] 	= $objJSNShowlist->renderShowlistComboBox(null, 'Select showlist to see live view with', 'showlist_id', 'onchange="JSNISClassicTheme.EnableShowCasePreview();"');