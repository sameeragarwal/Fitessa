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

class ImageShowModelShowList extends JModel 
{
	var $_id = null;	
	var $_data = null;

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit	= JRequest::getVar('edit',true);
		if($edit)
		{
			$this->setId((int)$array[0]);
		}
	}

	function setId($id)
	{		
		$this->_id		= $id;
		$this->_data	= null;
	}

	function getData()
	{
	
		if ($this->_loadData()){
			return $this->_data;
		}else{
			return $this->_initData();
		}	
	}

	function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data)){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->store()){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$this->setId($row->showlist_id);
		$row->reorder();
		return true;
	}

	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM #__imageshow_showlist WHERE showlist_id = '.(int) $this->_id;
			
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			$result = (boolean) $this->_data;
			
			if($result)
			{			
				$this->_data->article_title 		= @$this->getArticleTitleByID($this->_data->alter_id);
				$this->_data->aut_article_title 	= @$this->getArticleTitleByID($this->_data->alter_autid);
				$this->_data->alter_module_title 	= @$this->getModuleTitleByID($this->_data->alter_module_id);
				$this->_data->seo_module_title		= @$this->getModuleTitleByID($this->_data->seo_module_id);
				$this->_data->seo_article_title		= @$this->getArticleTitleByID($this->_data->seo_article_id);
				return (boolean) $this->_data;
			}
			else
			{		
				return $result;
			}
		}
		//return true;
	}

	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$showlist 					= new stdClass();
			$showlist->showlist_id 		= 0;
			$showlist->showlist_title	= null ;
			$showlist->published 		= 0 ;
			$showlist->ordering 		= 0 ;
			$showlist->access			= 0 ;
			$showlist->hits 			= 0 ;
			$showlist->description 		= null ;
			$showlist->showlist_link 	= null ;
			$showlist->alter_id 		= 0 ;
			$showlist->alter_autid 		= 0 ;
			$showlist->alter_module_id  = 0;
			$showlist->alter_image_path = null;
			$showlist->seo_module_id   	= 0;
			$showlist->seo_article_id	= 0;
			$showlist->date_create 		= null ;
			$showlist->date_modified 		= null ;
			$showlist->showlist_source 		= null ;
			$showlist->configuration_id 	= 0;
			$showlist->alternative_status 	= 0;
			$showlist->seo_status 			= 0;	
			$showlist->authorization_status	= 0;
				
			$this->_data = $showlist;
			return $this->_data;
		}
		return true;
	}
	
	function accesslevel( &$row )
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__groups'
		. ' ORDER BY id'
		;
		$db->setQuery( $query );
		$groups = $db->loadObjectList(); 
		$access = JHTML::_('select.genericlist',   $groups, 'access', 'class="inputbox"', 'value', 'text', $row->access, '', 1 );

		return $access;
	}
	
function getModuleTitleByID($id)
	{
		$query = 'SELECT title FROM #__modules WHERE id = '.(int)$id;
		$this->_db->setQuery($query);
		$result = @$this->_db->loadObject();
		return $result->title;
	}
	
	function getArticleTitleByID($id)
	{
		$query = 'SELECT title FROM #__content WHERE id = '.(int)$id;
		$this->_db->setQuery($query);
		$result = @$this->_db->loadObject();
		return $result->title;
	}
	
	function getModules()
	{
		global $mainframe;

		// Initialize some variables
		$db		=& JFactory::getDBO();
		$client	=& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));
		$option	= 'com_imageshow.modules';

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'filter_order',		'filter_order',		'm.position',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$filter_state		= $mainframe->getUserStateFromRequest( $option.'filter_state',		'filter_state',		'',				'word' );
		$filter_position	= $mainframe->getUserStateFromRequest( $option.'filter_position',	'filter_position',	'',				'cmd' );
		$filter_type		= $mainframe->getUserStateFromRequest( $option.'filter_type',		'filter_type',		'',				'cmd' );
		$filter_assigned	= $mainframe->getUserStateFromRequest( $option.'filter_assigned',	'filter_assigned',	'',				'cmd' );
		$search				= $mainframe->getUserStateFromRequest( $option.'search',			'search',			'',				'string' );
		if (strpos($search, '"') !== false) {
			$search = str_replace(array('=', '<'), '', $search);
		}
		$search = JString::strtolower($search);

		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		$where[] = 'm.client_id = '.(int) $client->id;

		$joins[] = 'LEFT JOIN #__users AS u ON u.id = m.checked_out';
		$joins[] = 'LEFT JOIN #__groups AS g ON g.id = m.access';
		$joins[] = 'LEFT JOIN #__modules_menu AS mm ON mm.moduleid = m.id';

		// used by filter
		if ( $filter_assigned ) {
			$joins[] = 'LEFT JOIN #__templates_menu AS t ON t.menuid = mm.menuid';
			$where[] = 't.template = '.$db->Quote($filter_assigned);
		}
		if ( $filter_position ) {
			$where[] = 'm.position = '.$db->Quote($filter_position);
		}
		if ( $filter_type ) {
			$where[] = 'm.module = '.$db->Quote($filter_type);
		}
		if ( $search ) {
			$where[] = 'LOWER( m.title ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}
			// NOTE :don't select imageshow	
			$where[] = 'm.module <> \'mod_imageshow\''; 
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'm.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'm.published = 0';
			}
		}

		// sanitize $filter_order
		if (!in_array($filter_order, array('m.title', 'm.published', 'm.ordering', 'groupname', 'm.position', 'pages', 'm.module', 'm.id'))) {
			$filter_order = 'm.position';
		}

		$where 		= ' WHERE ' . implode( ' AND ', $where );
		$join 		= ' ' . implode( ' ', $joins );
		if ($filter_order == 'm.ordering') {
			$orderby = ' ORDER BY m.position, m.ordering '. $filter_order_Dir;
		} else {
			$orderby = ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', m.ordering ASC';
		}

		// get the total number of records
		$query = 'SELECT COUNT(DISTINCT m.id)'
		. ' FROM #__modules AS m'
		. $join
		. $where
		;
		$db->setQuery( $query );
		$total = $db->loadResult();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $total, $limitstart, $limit );

		$query = 'SELECT m.*, u.name AS editor, g.name AS groupname, MIN(mm.menuid) AS pages'
		. ' FROM #__modules AS m'
		. $join
		. $where
		. ' GROUP BY m.id'
		. $orderby
		;
		$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}

		// get list of Positions for dropdown filter
		$query = 'SELECT m.position AS value, m.position AS text'
		. ' FROM #__modules as m'
		. ' WHERE m.client_id = '.(int) $client->id
		. ' GROUP BY m.position'
		. ' ORDER BY m.position'
		;
		$positions[] = JHTML::_('select.option',  '0', '- '. JText::_('Select Position') .' -' );
		$db->setQuery( $query );
		$positions = array_merge( $positions, $db->loadObjectList() );
		$lists['position']	= JHTML::_('select.genericlist',   $positions, 'filter_position', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', "$filter_position" );

		// get list of Positions for dropdown filter
		$query = 'SELECT module AS value, module AS text'
		. ' FROM #__modules'
		. ' WHERE client_id = '.(int) $client->id
		. ' GROUP BY module'
		. ' ORDER BY module'
		;
		$db->setQuery( $query );
		$types[] 		= JHTML::_('select.option',  '0', '- '. JText::_('Select Type') .' -' );
		$types 			= array_merge( $types, $db->loadObjectList() );
		$lists['type']	= JHTML::_('select.genericlist',   $types, 'filter_type', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', "$filter_type" );

		// state filter
		$lists['state']	= JHTML::_('grid.state',  $filter_state );

		// template assignment filter
		$query = 'SELECT DISTINCT(template) AS text, template AS value'.
				' FROM #__templates_menu' .
				' WHERE client_id = '.(int) $client->id;
		$db->setQuery( $query );
		$assigned[]		= JHTML::_('select.option',  '0', '- '. JText::_('Select Template') .' -' );
		$assigned 		= array_merge( $assigned, $db->loadObjectList() );
		$lists['assigned']	= JHTML::_('select.genericlist',   $assigned, 'filter_assigned', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', "$filter_assigned" );

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;

		$moduleData 			= new stdClass();
		$moduleData->rows 		= $rows;
		$moduleData->client 	= $client;
		$moduleData->pageNav 	= $pageNav;
		$moduleData->lists 		= $lists;
		
		return $moduleData;
		
	}
}

?>