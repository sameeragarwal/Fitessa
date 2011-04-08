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

class TableShowList extends JTable
{
	
	var $showlist_id 	= null;
	var $showlist_title	= null ;
	var $published 		= null ;
	var $ordering 		= null ;
	var $access			= null ;
	var $hits 			= null ;
	var $description 	= null ;
	var $showlist_link 	= null ;
	var $alter_id	 	= null ;
	var $alter_autid 	= null ;
	var $alter_module_id = null;
	var $alter_image_path = null;
	var $seo_article_id = null;
	var $seo_module_id = null;
	var $date_create 	= null ;
	var $showlist_source = null ;
	var $configuration_id = null;
	var $alternative_status = null;
	var $seo_status = null;
	var $authorization_status = null;
	var $date_modified = null;
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableShowList(& $db) {
		parent::__construct('#__imageshow_showlist', 'showlist_id', $db);
	}
}
?>