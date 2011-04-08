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

class ImageShowModelShowCases extends JModel 
{

	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	function __construct() 
	{
		
		parent::__construct();
		global $mainframe, $option;
		
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( 'com_imageshow.showcase.limitstart', 'limitstart', 0, 'int' );

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	function getData() 
	{
		$db	=& JFactory::getDBO();
		
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = @$this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
				
		return $this->_data;
	}

	function getTotal() 
	{
		if (empty($this->_total)) 
		{
			$query = $this->_buildQuery();
			$this->_total = @$this->_getListCount($query);
		}
		return $this->_total;
	}
	function getPagination() 
	{
		if (empty($this->_pagination)) 
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	function _buildQuery() 
	{
		$where		= $this->_buildContentWhere();
		 
		$orderby	= $this->_buildContentOrderBy();
		$query		= ' SELECT * FROM #__imageshow_showcase'
					. $where
					. $orderby ;
		
		return $query;
		
	}
	
	function _buildContentOrderBy()
	{
		global $mainframe, $option;
		$filterOrder		= $mainframe->getUserStateFromRequest( 'com_imageshow.showcase.filter_order', 'filter_order', '', 'cmd' );
		$filterOrderDir	= $mainframe->getUserStateFromRequest( 'com_imageshow.showcase.filter_order_Dir','filter_order_Dir', '', 'word' );
		
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
		$where 					= null;
		$filterState			= $mainframe->getUserStateFromRequest( 'com_imageshow.showcase.filter_state', 'filter_state', '', 'word' );
		$filter_order			= $mainframe->getUserStateFromRequest( 'com_imageshow.showcase.filter_order', 'filter_order', '', 'cmd' );
		$filter_order_Dir		= $mainframe->getUserStateFromRequest( 'com_imageshow.showcase.filter_order_Dir','filter_order_Dir', '', 'word' );
		$showcaseTitle			= $mainframe->getUserStateFromRequest( 'com_imageshow.showcase.showcase_title', 'showcase_title', '', 'string' );
		$showcaseTitle			= JString::strtolower( $showcaseTitle );
		
		if ($showcaseTitle) {
			$where[] = 'LOWER(showcase_title) LIKE '.$db->Quote( '%'.$db->getEscaped( $showcaseTitle, true ).'%', false );
		}

		if ( $filterState) 
		{
			if ( $filterState == 'P' ){
				$where[] = 'published = 1';
			} 
			else if ($filterState == 'U' ){
				$where[] = 'published = 0';
			}
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
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__imageshow_showcase'
				. ' WHERE showcase_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );

			if(!$this->_db->query()) 
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
			$cids 	= implode( ',', $cid );

			$query 	= 'UPDATE #__imageshow_showcase'
				. ' SET published = '.(int) $publish
				. ' WHERE showcase_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			
			if (!$this->_db->query()){
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
}

?>