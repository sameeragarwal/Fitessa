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
jimport( 'joomla.plugin.plugin' );
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_factory.php');
class plgJSNImageshowThemeClassic extends JPlugin 
{
	var $_showcaseThemeName = 'themeclassic';
	var $_showcaseThemeType = 'jsnimageshow';
	var $_pathAssets 		= 'plugins/jsnimageshow/themeclassic/assets/';
	var $_tableName			= 'theme_classic';
	
	function onLoadJSNShowcaseTheme($name)
	{
		if($name != $this->_showcaseThemeName){
			return true;
		}
		
		JPlugin::loadLanguage('plg_'.$this->_showcaseThemeType.'_'.$this->_showcaseThemeName);
		
		ob_start();
		
		JHTML::stylesheet('style.css', $this->_pathAssets.'css/');
		JHTML::script('jsn_is_classictheme.js', $this->_pathAssets.'js/');
		JHTML::script('jsn_is_accordions.js', $this->_pathAssets.'js/');
		JHTML::script('swfobject.js', $this->_pathAssets.'js/');
		
		include(dirname(__FILE__).DS.$this->_showcaseThemeName.DS.'helper'.DS.'helper.php');
		include(dirname(__FILE__).DS.$this->_showcaseThemeName.DS.'views'.DS.'default.php');

		return ob_get_clean();
	}

	function loadMedia()
	{
		JPlugin::loadLanguage('plg_'.$this->_showcaseThemeType.'_'.$this->_showcaseThemeName);
		$basePath 			= JPATH_PLUGINS.DS.$this->_showcaseThemeType.DS.$this->_showcaseThemeName;
		$objThemeMedia 		= JSNISFactory::getObj('classes.jsn_is_thememedia', null ,null, $basePath);
		$objThemeMedia->setMediaBasePath();
		
		JHTML::script('jsn_is_imagemanager.js', $this->_pathAssets.'js/');
		JHTML::stylesheet('system.css', 'templates/system/css/');
		
		$this->session 		= &JFactory::getSession();
		$this->stateFolder	= $objThemeMedia->getStateFolder();
		$this->folderList 	= $objThemeMedia->getFolderList();
		
		include(dirname(__FILE__).DS.$this->_showcaseThemeName.DS.'views'.DS.'media'.DS.'default.php');
	}
	
	function loadMediaImagesList()
	{
		JPlugin::loadLanguage('plg_'.$this->_showcaseThemeType.'_'.$this->_showcaseThemeName);
		$basePath 			= JPATH_PLUGINS.DS.$this->_showcaseThemeType.DS.$this->_showcaseThemeName;
		$objThemeMedia 		= JSNISFactory::getObj('classes.jsn_is_thememedia', null ,null, $basePath);
		$objThemeMedia->setMediaBasePath();
		
		$document =& JFactory::getDocument();
		$document->addScriptDeclaration("var JSNISImageManager = window.parent.JSNISImageManager;");
		
		$this->session 		= &JFactory::getSession();
		$this->folderList 	= $objThemeMedia->getFolderList();
		$this->images 		= $objThemeMedia->getImages();
		$this->folders 		= $objThemeMedia->getFolders();
		$this->baseURL 		= $objThemeMedia->comMediaBaseURL;	
		$this->stateFolder	= $objThemeMedia->getStateFolder();
		
		include(dirname(__FILE__).DS.$this->_showcaseThemeName.DS.'views'.DS.'mediaimages'.DS.'default.php');
	}
	
	function onUpload()
	{
		$basePath 		= JPATH_PLUGINS.DS.$this->_showcaseThemeType.DS.$this->_showcaseThemeName;
		$objThemeMedia 	= JSNISFactory::getObj('classes.jsn_is_thememedia', null ,null, $basePath = $basePath);
		$objThemeMedia->setMediaBasePath();
		
		$objThemeMedia->upload();
	}
	
	function onLoadJSGallery(&$showcase, &$showlist, &$images, $width, $height)
	{
		if (is_null($showcase) || !count($showlist) || !count($images))
		{
			return JText::_('JS Gallery data missing');
		}
		
		$objJSNShow 		 = JSNISFactory::getObj('classes.jsn_is_show');
		$objISNUtils 		 = JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNFlickr		 = JSNISFactory::getObj('classes.jsn_is_flickr');
		$randString			 = $objISNUtils->randSTR(6);
		$JSEvent 			 = $this->_setJSEvent();
		$optionsJSGallery 	 = $this->_setOptionsJSGallery($showcase->theme_id);
		$slides 			 = '';
		$links  			 = '';
		$countImage			 = count($images);
		$assetLinks		 	 = 'plugins/'.$this->_showcaseThemeType.'/'.$this->_showcaseThemeName.'/assets';
		$URL 				 = $objISNUtils->overrideURL();
		
		if($countImage)
		{
			for ($i = 0; $i < $countImage; $i++)
			{
				$image = $images [$i];
				if ($showlist['showlist_source'] == 2)
				{
					$imgSrc = $objJSNFlickr->getBigImageBySizeConfig($showlist['configuration_id'], $image->image_big);
					$slides .= "'$imgSrc',";
				}
				else
				{
					if ($showlist['showlist_source'] == 3)// picasa
					{
						$slides .= "'$image->image_big',";
					}
					else // folder , joomga, phoca
					{
						$slides .= "'".$URL.$image->image_big."',";
					}										
				}
				$links .= "'$image->image_link',";
			}
			
			$links 	= substr($links, 0 , -1);
			$slides = substr($slides, 0 , -1);
			
			JHTML::stylesheet('js_gallery.css', $assetLinks.'/css/');
			JHTML::script('jsn_is_slidegallery.js',$assetLinks.'/js/');
			
			$string	 = '<script type="text/javascript">'."\n";
			$string	.='window.addEvent("'.$JSEvent.'", function(){'."\n";
			$string	.='var options = {"changeSlideDuration" : '.$optionsJSGallery->slideTiming.',
									  "positionController" : "'.$optionsJSGallery->positionController.'",
									  "slides":['.$slides.'],
									  "links" :['.$links.']
					 				};'."\n";
			
			$string	.='var slideGallery'.$randString.' = new JSNISSlideGallery("jsnis-slide-gallery-'.$randString.'", options);'."\n";
			$string	.='})'."\n";
			$string	.='</script>'."\n";
			$string	.='<div id="jsnis-slide-gallery-'.$randString.'" class="jsnis-slide-gallery" style="'.$width.'; height:'.$height.'px">';
			$string	.='<div class="jsnis-slide-loading">';
			$string	.='<img src="'.$assetLinks.'/images/js-gallery-icon/ajax-loader.gif" alt=""/>';
			$string	.='</div>';
			$string	.='<div class="jsnis-slide-nav jsnis-slide-nav-left">';
			$string	.='<img src="'.$assetLinks.'/images/js-gallery-icon/left-slide-button.png" alt=""/>';
			$string	.='</div>';
			$string	.='<div class="jsnis-slide-nav jsnis-slide-nav-right">';
			$string	.='<img src="'.$assetLinks.'/images/js-gallery-icon/right-slide-button.png" alt=""/>';
			$string	.='</div>';
			$string	.='<div class="jsnis-slide-controller jsnis-slide-controller-play">';
			$string	.='<img src="'.$assetLinks.'/images/js-gallery-icon/play-slide-button.png" alt=""/>';
			$string	.='</div>';
			$string	.='<div class="jsnis-slide-controller jsnis-slide-controller-pause">';
			$string	.='<img src="'.$assetLinks.'/images/js-gallery-icon/pause-slide-button.png" alt=""/>';
			$string	.='</div>';
			$string	.='</div>';	
			
			return  $string;		
		}		
	}
	
	function _setOptionsJSGallery($themeID)
	{
		$objJSNTheme				= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		$theme 						= $objJSNTheme->getThemeByID($this->_tableName, $themeID);
		
		$option						= new stdClass();
		$option->slideTiming   		= $theme->slideshow_slide_timing*1000;
		$option->fadeTiming  		= ($theme->slideshow_presentation_mode == 'expand-out') ? $theme->imgpanel_img_transition_timing_expand : $theme->imgpanel_img_transition_timing_fit;
		$option->fadeTiming    		= $option->fadeTiming*1000;
		$option->positionController = $theme->toolbarpanel_panel_position;		
		return $option;
	}

	function _setJSEvent()
	{
		$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
		$mootoolVersion = $objJSNUtils->checkMootoolVersion();
		$event 			= "domready";
		$userAgent		= JString::strtolower($_SERVER['HTTP_USER_AGENT']);
		
		if ($mootoolVersion == '1.2')
		{
			if (preg_match('/msie/', $userAgent))
			{
				$event = "domready";
			}
			else
			{
				$event = "load";
			}
		}
		else
		{
			if (preg_match('/safari/', $userAgent) || preg_match('/chrome/', $userAgent))
			{
				$event = "domready";
			}
			else
			{
				$event = "load";
			}
		}		
		return $event;
	}	
}
?>