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
class ImageShowModelImages extends JModel 
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_showListID = null;	
	var $_arrImage	= null;	

	function __construct() 
	{
		parent::__construct();
		global $mainframe, $option;
		$showlistID = JRequest::getInt('showlist_id');
		$arrImageID = JRequest::getVar( 'cid', array(), '', 'array' );
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		
		if(count($arrImageID)){
			$limit = 0;
		}
		
		$limitstart = JRequest::getInt('limitstart');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
		$this->setShowListID((int)$showlistID);
		$this->setArrImage($arrImageID);
	}
	
	function setShowListID($id)
	{		
		$this->_showListID	= $id;
		$this->_data		= null;
	}
	
	function setArrImage($array)
	{		
		$this->_arrImage	= $array;
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
		$query		= 'SELECT * FROM #__imageshow_images'
					. $where
					. $orderby;	
		return $query;		
	}
	
	function _buildContentOrderBy()
	{
		global $mainframe, $option;
		$filterOrder		= $mainframe->getUserStateFromRequest( 'com_imageshow.images.filter_order', 'filter_order', '', 'cmd' );
		$filterOrderDir		= $mainframe->getUserStateFromRequest( 'com_imageshow.images.filter_order_Dir','filter_order_Dir', '', 'word' );
		
		if ($filterOrder != '')
		{
			$orderby 	= ' ORDER BY '.$filterOrder.' '.$filterOrderDir;
		}
		else 
		{			
			$orderby 	= ' ORDER BY ordering ASC ';
		}
		return $orderby;
	}
	
	function _buildContentWhere() 
	{
		global $mainframe, $option;
		$db			=& JFactory::getDBO();
		$where 		= null;
		$where[] 	= 'showlist_id='.(int)$this->_showListID;
		
		if (count( $this->_arrImage ))
		{
			JArrayHelper::toInteger($this->_arrImage);
			$cids 		= implode( ',', $this->_arrImage );	
			$where[] 	= 'image_id IN ( '.$cids.' )';
		}
		
		$where 	= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );	
		return $where;
	}
	
	function delete($showlistId, $sourceType, $cid = array())	
	{
		$result = false;	
		$row 	=& $this->getTable();

		if (count( $cid )) 
		{
			JArrayHelper::toInteger($cid);	
			$cids = implode( ',', $cid );
			
			if($sourceType == 'folder')
			{
				$objJSNThumb = JSNISFactory::getObj('classes.jsn_is_imagethumbnail');
				$imageThumbs = $objJSNThumb->getArrayThumbImage($cids);
				
				if (is_array($imageThumbs) && count($imageThumbs) && $imageThumbs != null)
				{
					$objJSNThumb->deleteThumbImage($imageThumbs);
				}			
			}			
			$query = 'DELETE FROM #__imageshow_images'.
					 ' WHERE image_id IN ( '.$cids.' )';
			
			$this->_db->setQuery( $query );
			
			if(!$this->_db->query()) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			$objJSNImages 	= JSNISFactory::getObj('classes.jsn_is_images');
			$totalImage 	= $objJSNImages->countImagesShowList($showlistId);
			
			for( $i=0; $i < $totalImage[0]; $i++ )
			{
				$row->reorder('showlist_id = '.(int) $showlistId);
			}		
		}
		return true;
	}
	
	function deleteAll($showlistId)	
	{

		$query = 'DELETE FROM #__imageshow_images WHERE showlist_id='.(int) $showlistId;
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}		
		return true;
	}		
	
	function getSourceType(){
		$query = 'SELECT showlist_source FROM #__imageshow_showlist WHERE showlist_id = '.(int) $this->_showListID;
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}
	
	function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->store()) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
	}
}
?>