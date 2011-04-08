<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 3.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JSNISFactory::importFile('classes.jsn_is_mediamanager');
class JSNISThemeMedia extends JSNISMediaManager
{
	var $_showcaseThemeName = 'themeclassic';
	var $_showcaseThemeType = 'jsnimageshow';
	
	function &getInstance()
	{
		static $instanceThemeMedia;
		if ($instanceThemeMedia == null)
		{
			$instanceThemeMedia = new JSNISThemeMedia();
		}
		return $instanceThemeMedia;
	}

	function setMediaBasePath()
	{
		$act = JRequest::getCmd('act','custom');
		
		if ($act == 'custom')
		{
			$this->setPath(JPATH_ROOT.DS.'images', JURI::root().'images');
		}
		
		if ($act == 'background')
		{
			$this->setPath(JPATH_COMPONENT_SITE.DS.'assets'.DS.'images'.DS.'bg-images', JURI::root().'components/com_imageshow/assets/images/bg-images');
		}
		
		if ($act == 'pattern')
		{
			$this->setPath(JPATH_PLUGINS.DS.$this->_showcaseThemeType.DS.$this->_showcaseThemeName.DS.'assets'.DS.'images'.DS.'bg-patterns', JURI::root().'plugins/'.$this->_showcaseThemeType.'/'.$this->_showcaseThemeName.'/assets/images/bg-patterns');
		}
		
		if ($act == 'watermark')
		{
			$this->setPath(JPATH_ROOT.DS.'images', JURI::root().'images');
		}
	}
}