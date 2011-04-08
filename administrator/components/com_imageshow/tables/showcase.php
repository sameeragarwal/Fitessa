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
class TableShowcase extends JTable
{
	var $showcase_id = null;
	var $showcase_title = null;
	var $published = null;
	var $ordering = null;
	var $background_color = null;
	var $general_overall_width = null;
	var $general_overall_height = null;
	var $general_round_corner_radius = null; 
	var $general_border_stroke = null; 
	var $general_border_color = null; 
	var $general_number_images_preload = null;  
	var $general_open_link_in = null; 	
	var $general_link_source = null; 
	var $general_title_source = null;
	var $general_des_source = null;	
	var $general_images_order= null;
	var $theme_name	= null;
	var $theme_id	= null;
	var $date_created = null;
	var $date_modified = null;
	
	function __construct(& $db) {
		parent::__construct('#__imageshow_showcase', 'showcase_id', $db);
	}
}
?>