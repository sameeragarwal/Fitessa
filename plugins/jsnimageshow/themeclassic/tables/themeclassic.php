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
class TableThemeClassic extends JTable
{
	var $theme_id 								= null;
	var $imgpanel_presentation_mode 			= 'fit-in';
	var $imgpanel_img_transition_type_fit 		= 'random';
	var $imgpanel_img_transition_timing_fit 	= 2;
	var $imgpanel_img_click_action_fit 			= 'image-zooming';			
	var $imgpanel_img_transition_type_expand 	= 'random';
	var $imgpanel_img_transition_timing_expand 	= 1;
	var $imgpanel_img_motion_type_expand 		= 'center-random';
	var $imgpanel_img_motion_timing_expand 		= 2;
	var $imgpanel_img_click_action_expand 		= 'open-image-link';	
	var $imgpanel_bg_type 						= 2;
	var $imgpanel_bg_value	 					= '#595959,#262626';
	var $imgpanel_show_watermark 				= 0;
	var $imgpanel_watermark_path 				= null;
	var $imgpanel_watermark_position 			= 'top-right';
	var $imgpanel_watermark_offset 				= 10;
	var $imgpanel_watermark_opacity 			= 75;
	var $imgpanel_show_overlay_effect 			= 0;
	var $imgpanel_overlay_effect_type 			= 'horizontal-floating-bar';
	var $imgpanel_show_inner_shawdow 			= 1;	
	var $imgpanel_inner_shawdow_color			= '#000000';	
	var $thumbpanel_show_panel 					= 'on';
	var $thumbpanel_panel_position 				= 'bottom';
	var $thumbpanel_collapsible_position 		= 1;
	var $thumbpanel_thumb_browsing_mode 		= 'pagination';
	var $thumbpanel_show_thumb_status 			= 1;
	var $thumbpanel_active_state_color 			= '#ff6200';
	var $thumbpanel_presentation_mode 			= 'image';
	var $thumbpanel_border						= 2;
	var $thumbpanel_enable_big_thumb 			= 1;
	var $thumbpanel_big_thumb_size 				= 150;
	var $thumbpanel_big_thumb_color 			= '#ffffff';
	var $thumbpanel_thumb_row					= 1;		
	var $thumbpanel_thumb_width					= 50;
	var $thumbpanel_thumb_height				= 40;
	var $thumbpanel_thumb_border				= 2;
	var $thumbpanel_thumnail_panel_color		= '#000000';
	var $thumbpanel_thumnail_normal_state 		= '#ffffff';
	var $infopanel_panel_position 				= 'top';
	var $infopanel_presentation      			= 'auto';
	var $infopanel_bg_color_fill 				= '#000000';
	var $infopanel_panel_click_action			= 'no-action';
	var $infopanel_show_title 					= 1;
	var $infopanel_title_css 					= "font-family: Verdana;\nfont-size: 12px;\nfont-weight: bold;\ntext-align: left;\ncolor: #E9E9E9;";
	var $infopanel_show_des 					= 1;
	var $infopanel_des_lenght_limitation 		= 50;
	var $infopanel_des_css 						= "font-family: Arial;\nfont-size: 11px;\nfont-weight: normal;\ntext-align: left;\ncolor: #AFAFAF;";
	var $infopanel_show_link 					= 0;
 	var $infopanel_link_css 					= "font-family: Verdana;\nfont-size: 11px;\nfont-weight: bold;\ntext-align: right;\ncolor: #E06614;";
 	var $toolbarpanel_panel_position 			= 'bottom';
	var $toolbarpanel_presentation 				= 'auto';
	var $toolbarpanel_show_image_navigation 	= 1;
  	var $toolbarpanel_slideshow_player 			= 1;
  	var $toolbarpanel_show_fullscreen_switcher 	= 1;
  	var $toolbarpanel_show_tooltip 				= 0;
  	var $slideshow_show_thumb_panel 			= 'off';
	var $slideshow_show_overlay_effect 			= 1;
	var $slideshow_slide_timing					= 8;			
	var $slideshow_show_image_navigation		= 0;
	var $slideshow_process						= 0;
	var $slideshow_show_watermark				= 0;			
	var $slideshow_show_status					= 1;
	var $slideshow_looping						= 1;
	var $slideshow_enable_ken_burn_effect 		= 1;
	var $slideshow_presentation_mode			= 'expand-out';
	
	function __construct(& $db) {
		parent::__construct('#__imageshow_theme_classic', 'theme_id', $db);
	}
}
?>