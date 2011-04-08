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

class TableConfiguration extends JTable
{
	
	var $configuration_id = null;
	var $configuration_title = null;
	var $flickr_api_key = null;
	var $flickr_secret_key = null;
	var $flickr_username = null;
	//var $flickr_caching = null;
	//var $flickr_cache_expiration = null;
	var $flickr_image_size = null;
	var $root_image_folder = null;
	var $picasa_user_name = null;
	var $source_type = null;
	var $published = null;
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__imageshow_configuration', 'configuration_id', $db);
	}
	
}

?>