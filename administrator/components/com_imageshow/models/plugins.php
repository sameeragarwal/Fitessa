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

class ImageShowModelPlugins extends JModel 
{

	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	
	function __construct() 
	{
		parent::__construct();
		global $mainframe, $option;
		
		$limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart	= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.limitstart', 'limitstart', 0, 'int');

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
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
				
		return $this->_data;
	}

	function getTotal() 
	{
		if (empty($this->_total)) 
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
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
		$and		= $this->_buildContentAnd();
		$orderby	= $this->_buildContentOrderBy();
		$query		= 'SELECT *
				   	   FROM #__plugins 
				   	   WHERE 
				   		(name LIKE "Theme%" AND folder = \'jsnimageshow\') '
					. $and
					. $orderby ;
		return $query;
	}
	
	function _buildContentOrderBy()
	{
		global $mainframe, $option;
		$filterOrder	= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.filter_order', 'filter_order', '', 'cmd');
		$filterOrderDir	= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.filter_order_Dir','filter_order_Dir', '', 'word');
		
		if ($filterOrder != ''){
			$orderby 	= ' ORDER BY '.$filterOrder.' '.$filterOrderDir;
		}else{			
			$orderby 	= ' ORDER BY ordering ASC ';
		}
		
		return $orderby;
	}

	function _buildContentAnd() 
	{
		global $mainframe, $option;
		$db						=& JFactory::getDBO();
		$and 					= null;
		$filterState			= $mainframe->getUserStateFromRequest( 'com_imageshow.themesManager.filter_state', 'filter_state', '', 'word' );
		$filter_order			= $mainframe->getUserStateFromRequest( 'com_imageshow.themesManager.filter_order', 'filter_order', '', 'cmd' );
		$filter_order_Dir		= $mainframe->getUserStateFromRequest( 'com_imageshow.themesManager.filter_order_Dir','filter_order_Dir', '', 'word' );
		$pluginName				= $mainframe->getUserStateFromRequest( 'com_imageshow.themesManager.plugin_name', 'plugin_name', '', 'string' );
		$pluginName				= JString::strtolower($pluginName);
		
		if($pluginName){
			$and[] = 'LOWER(name) LIKE '.$db->Quote('%'.$db->getEscaped($pluginName, true).'%', false);
		}

		if($filterState) 
		{
			if($filterState == 'P'){
				$and[] = 'published = 1';
			} 
			else if($filterState == 'U'){
				$and[] = 'published = 0';
			}
		}
		
		$and 	= (count($and) ? ' AND '. implode(' AND ', $and) : '');	
		return $and;
	}
}

?>