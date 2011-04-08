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

class ImageShowModelConfiguration extends JModel {
	
	var $_id = null;	
	var $_data = null;

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid', array(1), '', 'array');
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
		
		$this->setId($row->configuration_id);
		$row->reorder();
		return true;
	}

	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT c.*, COUNT(sl.showlist_id) AS totalshowlist FROM #__imageshow_configuration c LEFT JOIN #__imageshow_showlist sl ON c.configuration_id = sl.configuration_id WHERE c.configuration_id='.$this->_id.' GROUP BY c.configuration_id';
			
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
			$configuration							= new stdClass();
			$configuration->configuration_id 		= 0;
			$configuration->configuration_title		= null;
			$configuration->flickr_api_key 			= null;
			$configuration->flickr_secret_key 		= null;
			$configuration->flickr_username			= null;
			$configuration->flickr_image_size 		= 0;
			$configuration->root_image_folder 		= null;
			$configuration->picasa_user_name 		= null;
			$configuration->source_type 			= 0;
			$configuration->published	 			= 0;			
			$this->_data							= $configuration;
			return $this->_data;
		}
		return true;
	}
	
	function getConfiguration($sourceType)
	{
		$query = 'SELECT 
						configuration_title AS text, configuration_id AS id 
				  FROM 
				  		#__imageshow_configuration 
				  WHERE 
				  		source_type='.(int) $sourceType .' 
				  AND 
				  		published = 1 
				  ORDER BY 
				  		configuration_title ASC';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();			
	}	
}
