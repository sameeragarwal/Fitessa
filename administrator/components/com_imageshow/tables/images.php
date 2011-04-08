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

class TableImages extends JTable
{
	var $image_id 			= null;
	var $showlist_id 		= null;
	var $image_extid 		= null;
	var $album_extid 		= null;
	var $image_small 		= null;
	var $image_medium 		= null;
	var $image_big 			= null;
	var $image_title 		= null;
	var $image_description 	= null;
	var $image_link 		= null;
	var $custom_data 		= null;
	var $sync				= null;
	var $ordering 			= null;
	var $image_size 		= null;
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__imageshow_images', 'image_id', $db);
	}
	
	function encodeURL($replaceSpace = false)
	{
		$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
		
		if (!empty($this->image_extid))
		{
			$this->image_extid = $objJSNUtils->encodeUrl($this->image_extid , $replaceSpace);
		}
		
		if (!empty($this->image_small))
		{
			$this->image_small = $objJSNUtils->encodeUrl($this->image_small, $replaceSpace);
		}
		
		if (!empty($this->image_medium))
		{
			$this->image_medium = $objJSNUtils->encodeUrl($this->image_medium, $replaceSpace);
		}
		
		if (!empty($this->image_big))
		{
			$this->image_big = $objJSNUtils->encodeUrl($this->image_big, $replaceSpace);
		}
		
		if (!empty($this->image_link))
		{
			$this->image_link = $objJSNUtils->encodeUrl($this->image_link, $replaceSpace);
		}
	}
	
	function trim()
	{
		if (!empty($this->image_title))
		{
			$this->image_title = trim($this->image_title);
		}
		
		if (!empty($this->image_description))
		{
			$this->image_description = trim($this->image_description);
		}
		
		if (!empty($this->image_link))
		{
			$this->image_link = trim($this->image_link);
		}
	}
}
?>