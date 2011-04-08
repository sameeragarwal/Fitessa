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

class JSNISSource
{
	var $_db = null;
	
	function JSNISSource()
	{
		if ($this->_db == null)
		{
			$this->_db = &JFactory::getDBO();
		}
	}
	
	function &getInstance()
	{
		static $instanceSource;
		if ($instanceSource == null)
		{
			$instanceSource = new JSNISSource();
		}
		return $instanceSource;
	}

	function getListConfigBySourceType($sourceType)
	{
		$query 	= "SELECT configuration_id, configuration_title FROM #__imageshow_configuration WHERE source_type = ".(int)$sourceType. " AND published = 1";
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	function getSourceTypeByShowlistID($showListID)
	{
		$query 	= 'SELECT 
					CASE showlist_source 
						WHEN 1 THEN "folder" 
						WHEN 2 THEN "flickr"  
						WHEN 3 THEN "picasa"  
						WHEN 4 THEN "phoca"  
						WHEN 5 THEN "joomga" END 
					FROM #__imageshow_showlist 
					WHERE showlist_id = ' .(int) $showListID;
		$this->_db->setQuery($query);
		return $this->_db->loadResult();		
	}
}