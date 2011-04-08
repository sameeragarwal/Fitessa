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
class ThemeClassic
{
	var $_pluginName 	= 'themeclassic';
	var $_pluginType 	= 'jsnimageshow';
	
	function &getInstance()
	{
		static $themeClassic;
		if ($themeClassic == null){
			$themeClassic = new ThemeClassic();
		}
		return $themeClassic;
	}
	
	function ThemeClassic()
	{
		$pathModelShowcaseTheme = JPATH_PLUGINS.DS.$this->_pluginType.DS.$this->_pluginName.DS.'models';
		$pathTableShowcaseTheme = JPATH_PLUGINS.DS.$this->_pluginType.DS.$this->_pluginName.DS.'tables';
		JModel::addIncludePath($pathModelShowcaseTheme);
		JTable::addIncludePath($pathTableShowcaseTheme);
	}
	
	function _prepareSaveData($data)
	{
		if(!empty($data))
		{
			$imgPanelBackgroundValue = $data['imgpanel_bg_value'];
			if(count($imgPanelBackgroundValue) == 2 && $imgPanelBackgroundValue[1] != ''){
				$data['imgpanel_bg_value'] = implode(',', $imgPanelBackgroundValue);
			}else{
				$data['imgpanel_bg_value'] = $imgPanelBackgroundValue[0];
			}
			
			return $data;
		}
		return false;
	}
	
	function _initData()
	{
		$cid				= JRequest::getVar('cid', array(0), '', 'array');
		$showcaseID			= (int) $cid[0];
		$showcaseTable 		=& JTable::getInstance('showcase', 'Table');
		$showcaseThemeTable =& JTable::getInstance($this->_pluginName, 'Table');
		
		if($showcaseTable->load($showcaseID))
		{
			if(!$showcaseThemeTable->load((int) $showcaseTable->theme_id)){
				$showcaseThemeTable->theme_id = null;
			}
		}
		
		return $showcaseThemeTable;
	}
	
	function _prepareDataJSON($showcaseID, $URL)
	{
		//$showcaseID 		= JRequest::getInt('showcase_id');
		$showcaseTable 		=& JTable::getInstance('showcase', 'Table');
		$showcaseThemeTable =& JTable::getInstance($this->_pluginName, 'Table');
		
		if($showcaseTable->load($showcaseID)){
			$showcaseThemeTable->load((int) $showcaseTable->theme_id);
		};
		
		$row =& $showcaseThemeTable;
	
		$booleanArray 	= array('no'=> 0, 'yes'=> 1);
		$imgPanelBgType = array('transparent'=>0, 'solid-color'=>1, 'linear-gradient'=>2, 'radial-gradient'=>3, 'pattern'=>4, 'image'=>5);
		$showcaseObject = new stdClass();
		
		//image-panel
		$imagePanelObj 								= new stdClass();
		$imagePanelObj->{'default-presentation'}	= $row->imgpanel_presentation_mode;
		$imagePanelObj->{'background-type'} 		= array_search($row->imgpanel_bg_type, $imgPanelBgType);
		$imagePanelObj->{'background-value'} 		= (strstr($row->imgpanel_bg_value, '#')== false and $row->imgpanel_bg_value!='') ? $URL.$row->imgpanel_bg_value : $row->imgpanel_bg_value;
		$imagePanelObj->{'show-watermark'} 			= array_search($row->imgpanel_show_watermark, $booleanArray);
		$imagePanelObj->{'watermark-path'} 			= ($row->imgpanel_watermark_path != null && $row->imgpanel_watermark_path != '') ? $URL.$row->imgpanel_watermark_path : '';
		$imagePanelObj->{'watermark-opacity'} 		= $row->imgpanel_watermark_opacity;
		$imagePanelObj->{'watermark-position'} 		= $row->imgpanel_watermark_position;
		$imagePanelObj->{'watermark-offset'} 		= $row->imgpanel_watermark_offset;
		$imagePanelObj->{'show-inner-shadow'} 		= array_search($row->imgpanel_show_inner_shawdow, $booleanArray);
		$imagePanelObj->{'inner-shadow-color'} 		= ($row->imgpanel_inner_shawdow_color != '') ? $row->imgpanel_inner_shawdow_color : '' ;
		$imagePanelObj->{'show-overlay'} 			= array_search($row->imgpanel_show_overlay_effect, $booleanArray);
		$imagePanelObj->{'overlay-type'} 			= $row->imgpanel_overlay_effect_type;
			
			//fitin-settings object
				$fitinSettingObj = new stdClass();
				$fitinSettingObj->{'transition-type'} 	= $row->imgpanel_img_transition_type_fit;
				$fitinSettingObj->{'transition-timing'} = $row->imgpanel_img_transition_timing_fit;
				$fitinSettingObj->{'click-action'} 		= $row->imgpanel_img_click_action_fit;
				
				$imagePanelObj->{'fitin-settings'} 		= $fitinSettingObj;
			//end fittin-settings object
			
			//expandout-settings object
				$expandOutSettingObj 						= new stdClass();
				$expandOutSettingObj->{'transition-type'} 	= $row->imgpanel_img_transition_type_expand;
				$expandOutSettingObj->{'transition-timing'} = $row->imgpanel_img_transition_timing_expand;
				$expandOutSettingObj->{'motion-type'} 		= $row->imgpanel_img_motion_type_expand;
				$expandOutSettingObj->{'motion-timing'} 	= $row->imgpanel_img_motion_timing_expand;
				$expandOutSettingObj->{'click-action'} 		= $row->imgpanel_img_click_action_expand;
				
				$imagePanelObj->{'expandout-settings'} = $expandOutSettingObj;
			//end expandout-settings object
			
		$showcaseObject->{'image-panel'} = $imagePanelObj;
		//end image-panel
		
		//thumbnail panel
		$thumbnailPanelObj 									= new stdClass();
		$thumbnailPanelObj->{'show-panel'} 					= $row->thumbpanel_show_panel;
		$thumbnailPanelObj->{'panel-position'} 				= $row->thumbpanel_panel_position;
		$thumbnailPanelObj->{'collapsible-panel'} 			= array_search($row->thumbpanel_collapsible_position,$booleanArray);
		$thumbnailPanelObj->{'background-color'} 			= $row->thumbpanel_thumnail_panel_color;
		$thumbnailPanelObj->{'thumbnail-row'} 				= $row->thumbpanel_thumb_row;
		$thumbnailPanelObj->{'thumbnail-width'} 			= $row->thumbpanel_thumb_width;
		$thumbnailPanelObj->{'thumbnail-height'} 			= $row->thumbpanel_thumb_height;
		$thumbnailPanelObj->{'active-state-color'} 			= $row->thumbpanel_active_state_color;
		$thumbnailPanelObj->{'normal-state-color'} 			= $row->thumbpanel_thumnail_normal_state;
		$thumbnailPanelObj->{'thumbnails-browsing-mode'} 	= $row->thumbpanel_thumb_browsing_mode;
		$thumbnailPanelObj->{'thumbnails-presentation-mode'} = $row->thumbpanel_presentation_mode;
		$thumbnailPanelObj->{'thumbnail-border'} 			= $row->thumbpanel_border;
		$thumbnailPanelObj->{'show-thumbnails-status'} 		= array_search($row->thumbpanel_show_thumb_status, $booleanArray);
		$thumbnailPanelObj->{'enable-big-thumbnail'} 		= array_search($row->thumbpanel_enable_big_thumb, $booleanArray);
		$thumbnailPanelObj->{'big-thumbnail-size'} 			= $row->thumbpanel_big_thumb_size;
		$thumbnailPanelObj->{'big-thumbnail-color'} 		= $row->thumbpanel_big_thumb_color;
		$thumbnailPanelObj->{'big-thumbnail-border'} 		= $row->thumbpanel_thumb_border;
		
		$showcaseObject->{'thumbnail-panel'} 				= $thumbnailPanelObj;
		//end thumbnail panel  
		
		//information-panel
		$informationPanelObj 							= new stdClass();
		$informationPanelObj->{'panel-presentation'} 	= $row->infopanel_presentation;
		$informationPanelObj->{'panel-position'} 		= $row->infopanel_panel_position;
		$informationPanelObj->{'background-color-fill'} = $row->infopanel_bg_color_fill;
		$informationPanelObj->{'show-title'} 			= array_search($row->infopanel_show_title, $booleanArray);
		$informationPanelObj->{'click-action'} 			= $row->infopanel_panel_click_action;
		$informationPanelObj->{'title-css'} 			= ($row->infopanel_title_css!='')?$row->infopanel_title_css:'';
		$informationPanelObj->{'show-description'} 		= array_search($row->infopanel_show_des, $booleanArray);
		$informationPanelObj->{'description-length-limitation'} = $row->infopanel_des_lenght_limitation;
		$informationPanelObj->{'description-css'} 				= ($row->infopanel_des_css!='')?$row->infopanel_des_css:'';
		$informationPanelObj->{'show-link'}						= array_search($row->infopanel_show_link, $booleanArray);
		$informationPanelObj->{'link-css'} 						= ($row->infopanel_link_css!='')?$row->infopanel_link_css:'';
		
		$showcaseObject->{'information-panel'} = $informationPanelObj;
		//end information-panel
		
		//toobar-panel
		$toobarPanelObj = new stdClass();
		$toobarPanelObj->{'panel-position'} 		= $row->toolbarpanel_panel_position;
		$toobarPanelObj->{'panel-presentation'} 	= $row->toolbarpanel_presentation;
		$toobarPanelObj->{'show-image-navigation'} 	= array_search($row->toolbarpanel_show_image_navigation, $booleanArray);
		$toobarPanelObj->{'show-slideshow-player'} 	= array_search($row->toolbarpanel_slideshow_player, $booleanArray);
		$toobarPanelObj->{'show-fullscreen-switcher'} 	= array_search($row->toolbarpanel_show_fullscreen_switcher, $booleanArray);
		$toobarPanelObj->{'show-tooltip'} 				= array_search($row->toolbarpanel_show_tooltip, $booleanArray);
		
		$showcaseObject->{'toobar-panel'} 				= $toobarPanelObj;
		// end toobar-panel 
		 
		//slideshow panel
		$slidePanelObj = new stdClass();
		$slidePanelObj->{'image-presentation'} = $row->slideshow_presentation_mode;
		
		if($row->slideshow_show_thumb_panel == 'inherited'){
			$slidePanelObj->{'show-thumbnail-panel'} = $row->thumbpanel_show_panel;	
		}else{
			$slidePanelObj->{'show-thumbnail-panel'} = $row->slideshow_show_thumb_panel;	
		}
		
		if($row->slideshow_show_image_navigation == 2){
			$slidePanelObj->{'show-image-navigation'} = array_search($row->toolbarpanel_show_image_navigation, $booleanArray);
		}else{
			$slidePanelObj->{'show-image-navigation'} = array_search($row->slideshow_show_image_navigation, $booleanArray);
		}

		if($row->slideshow_show_watermark == 2){
			$slidePanelObj->{'show-watermark'} = array_search($row->imgpanel_show_watermark, $booleanArray);
		}else{
			$slidePanelObj->{'show-watermark'} = array_search($row->slideshow_show_watermark, $booleanArray);
		}
		
		$slidePanelObj->{'show-status'} = array_search($row->slideshow_show_status, $booleanArray);
		
		if($row->slideshow_show_overlay_effect == 2){
			$slidePanelObj->{'show-overlay'} = array_search($row->imgpanel_show_overlay_effect, $booleanArray);
		}else{
			$slidePanelObj->{'show-overlay'} = array_search($row->slideshow_show_overlay_effect, $booleanArray);
		}
		
		$slidePanelObj->{'slide-timing'} 		= $row->slideshow_slide_timing;
		$slidePanelObj->{'auto-play'} 			= array_search($row->slideshow_process, $booleanArray);
		$slidePanelObj->{'slideshow-looping'} 	= array_search($row->slideshow_looping, $booleanArray);
		$slidePanelObj->{'enable-kenburn'} 		= array_search($row->slideshow_enable_ken_burn_effect, $booleanArray);
		
		$showcaseObject->{'slideshow'} = $slidePanelObj;
		//end slideshow panel
		
		return $showcaseObject;
	}
}