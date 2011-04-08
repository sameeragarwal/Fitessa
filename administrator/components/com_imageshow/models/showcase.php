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

class ImageShowModelShowCase extends JModel 
{
	var $_id = null;	
	var $_data = null;

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit	= JRequest::getVar('edit',true);
		
		if($edit){
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
		if ($this->_loadData())
		{
			return $this->_data;
		}
		else
		{
			return $this->_initData();
		}	
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
		
		$this->setId($row->showcase_id);
		$row->reorder();
		return true;
	}

	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM #__imageshow_showcase WHERE showcase_id = '.(int) $this->_id;
			
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}
	
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$showcase 									= new stdClass();
			$showcase->showcase_id 						= 0;
			$showcase->showcase_title 					= null;
			$showcase->published 						= 0;
			$showcase->ordering 						= 0;
			$showcase->background_color					= null;
			$showcase->general_overall_width 			= null;
			$showcase->general_overall_height 			= null;
			$showcase->general_round_corner_radius 		= null; 
			$showcase->general_border_stroke			= null; 
			$showcase->general_border_color				= null; 
			$showcase->general_number_images_preload	= null; 		
			$showcase->general_open_link_in 			= null; 	
			$showcase->general_link_source 				= null; 	
			$showcase->general_title_source 			= null;
			$showcase->general_des_source 				= null;	
			$showcase->general_images_order 			= null;
			$showcase->theme_name			 			= null;
			$showcase->theme_id				 			= null;						
			$showcase->date_created						= null;
			$showcase->date_modified					= null;
			
			$this->_data								= $showcase;
			return $this->_data;
		}
		//return true;
	}
}

?>