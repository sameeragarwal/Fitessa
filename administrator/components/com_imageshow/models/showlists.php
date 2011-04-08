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
jimport('joomla.application.component.model');
class ImageShowModelShowLists extends JModel 
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	
	function __construct() 
	{
		parent::__construct();
		global $mainframe, $option;
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );
	
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function getData() 
	{
		$db	=& JFactory::getDBO();
		
		if (empty($this->_data)){
			$query = $this->_buildQuery();
			$this->_data = @$this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
				
		return $this->_data;
	}

	function getTotal() 
	{
		if (empty($this->_total)){
			$query = $this->_buildQuery();
			$this->_total = @$this->_getListCount($query);
		}
		
		return $this->_total;
	}
	
	function getPagination()
	{
		if (empty($this->_pagination)){
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		
		return $this->_pagination;
	}

	function _buildQuery() 
	{
		$where		= $this->_buildContentWhere();
		 
		$orderby	= $this->_buildContentOrderBy();
		$query		= ' SELECT sl.*, COUNT(img.showlist_id) AS totalimage, c.configuration_title, g.name AS groupname FROM #__imageshow_showlist AS sl LEFT JOIN #__groups AS g ON g.id = sl.access'.
					  ' LEFT JOIN #__imageshow_images img ON sl.showlist_id = img.showlist_id LEFT JOIN #__imageshow_configuration c ON sl.configuration_id = c.configuration_id'
					. $where
					.' GROUP BY sl.showlist_id'. $orderby ;
	
		return $query;
		
	}
	
	function _buildContentOrderBy()
	{
		global $mainframe, $option;
		$filterOrder		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_order', 'filter_order', '', 'cmd' );
		$filterOrderDir		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_order_Dir','filter_order_Dir', '', 'word' );
		
		if ($filterOrder != ''){
			$orderby 	= ' ORDER BY '.$filterOrder.' '.$filterOrderDir;
		}else{			
			$orderby 	= ' ORDER BY ordering ASC ';
		}
		
		return $orderby;
	}

	function _buildContentWhere() 
	{
		
		global $mainframe, $option;
		$db						=& JFactory::getDBO();
		$where					=array();
		$filterState			= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_state', 'filter_state', '', 'word' );
		$filter_order			= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_order', 'filter_order', '', 'cmd' );
		$filter_order_Dir		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_order_Dir','filter_order_Dir', '', 'word' );
		$showlistTitle			= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.showlist_stitle', 'showlist_stitle', '', 'string' );
		$showlistAccess			= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.showlist_access', 'access', '', 'string' );
		$showlistTitle			= JString::strtolower( $showlistTitle );
		$configurationID        = JRequest::getInt('configuration_id');
		
		if ($showlistTitle) {
			$where[] = 'LOWER(sl.showlist_title) LIKE '.$db->Quote( '%'.$db->getEscaped( $showlistTitle, true ).'%', false );
		}
		
		if ( $filterState) 
		{
			if ( $filterState == 'P' ){
				$where[] = 'sl.published = 1';
			}else if ($filterState == 'U' ){
				$where[] = 'sl.published = 0';
			}
		}
		
		if($showlistAccess !=''){
			$where[] = 'sl.access = '.$showlistAccess;
		}
		
		if($configurationID != 0){
			$where[] = 'c.configuration_id ='.$configurationID;
		}
		
		$where 	= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );	
	
		return $where;
	}
	
	function delete($cid = array())	
	{
		$result = false;	
		if (count( $cid )) 
		{
			JArrayHelper::toInteger($cid);
			$objImageThumb 	= JSNISFactory::getObj('classes.jsn_is_imagethumbnail');
			$cids 			= implode( ',', $cid );
			$query 			= "SELECT image_small FROM #__imageshow_images WHERE showlist_id IN(" .$cids. ")";
			
			$this->_db->setQuery($query);
			$result = $this->_db->loadResultArray();
			$objImageThumb->deleteThumbImage($result);
			
			$query = 'DELETE FROM #__imageshow_showlist'
				. ' WHERE showlist_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			$result = $this->_db->query();
			
			$query = 'DELETE FROM #__imageshow_images'
				. ' WHERE showlist_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			$result = $this->_db->query();
			
			if(!$result) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}	
	
	function approve($cid = array(), $publish = 1) 
	{
		if (count( $cid )) 
		{			
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__imageshow_showlist'
				. ' SET published = '.(int) $publish
				. ' WHERE showlist_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );

			if (!$this->_db->query()) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
	
	function accessmenu($id, $access) 
	{
		$row =& $this->getTable('showlist');
		$row->showlist_id = $id;
		$row->access = $access;	
		
		if ( !$row->check() ) {		
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if ( !$row->store() ) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
	}
	function accesslevel( &$row )
	{
		$db 	=& JFactory::getDBO();
		$query 	= 'SELECT id AS value, name AS text'
		. ' FROM #__groups'
		. ' ORDER BY id'
		;
		
		$db->setQuery( $query );
		$groups 	= $db->loadObjectList();
		$results[] 	= JHTML::_('select.option', '', '- '.JText::_( 'Select Access Level' ).' -', 'value', 'text' );
		$results 	= array_merge( $results, $groups ); 
		$access 	= JHTML::_('select.genericlist',   $results, 'access', 'class="inputbox" onchange="document.adminForm.submit( );"', 'value', 'text', $row, '', 1 );

		return $access;
	}	
}

?>