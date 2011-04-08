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
include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'log.php');
class JSNISLog {	
	
	var $modelObject;
	var $_db = null;
	function JSNISLog()
	{
		if ($this->_db == null)
		{
			$this->_db =& JFactory::getDBO();
		}
		
		$this->modelObject = new ImageShowModelLog();
	}

	function &getInstance()
	{
		static $instanceLog;
		if ($instanceLog == null)
		{
			$instanceLog = new JSNISLog();
		}
		return $instanceLog;
	}
	
	function addLog($userID, $url, $result, $screen, $action)
	{
		$date 				=& JFactory::getDate();
		$data 				= array();
		$data['user_id'] 	= $userID;
		$data['url'] 		= $url;
		$data['result'] 	= $result;
		$data['screen'] 	= $screen;
		$data['action'] 	= $action;
		$data['time_created'] = JHTML::_('date', $date->toUnix(), '%Y-%m-%d %H:%M:%S');
		$this->modelObject->store($data);
	}
	
	function getLog($limit = null)
	{
		$query 	= 'SELECT 
						user_id, result, 
						screen, action, 
						time_created 
				   FROM #__imageshow_log 
				   ORDER BY time_created DESC 
				   LIMIT '. (int) $limit;
		
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();		
	}
	
//	function getAllLog()
//	{
//		$query 	= 'SELECT 
//						user_id, result, 
//						screen, action, 
//						time_created 
//				   FROM #__imageshow_log 
//				   ORDER BY time_created DESC 
//				   LIMIT 50';
//		$this->_db->setQuery($query);
//		return $this->_db->loadObjectList();		
//	}	
	
	function getTotalRecordLog()
	{
		$query 	= 'SELECT COUNT(*) FROM #__imageshow_log';
		$this->_db->setQuery($query);
		return $this->_db->loadRow();		
	}
	
	function getMinLogID()
	{
		$query 	= 'SELECT MIN( log_id ) AS min_id FROM #__imageshow_log';
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}
	
	function deleteRecordLog()
	{
		$minID 					= $this->getMinLogID();
		$totalRecord 			= $this->getTotalRecordLog();
		$arrayRecordRemoved  	= array();
		
		if ($totalRecord[0] > 50)
		{
			$totalRecordRemoved = $totalRecord[0] - 50;
			$maxIDRemoved 		= $minID['min_id'] + $totalRecordRemoved - 1;
			
			for ($i = $minID['min_id']; $i <= $maxIDRemoved; $i++)
			{
				$arrayRecordRemoved[] = $i;
			}
			
			$recordRemoved 	= implode(",", $arrayRecordRemoved) ;
			$query 			= 'DELETE FROM #__imageshow_log WHERE log_id IN ('.$recordRemoved.')';
			
			$this->_db->setQuery($query);	
			$this->_db->query();
			
			return true;
		}
		return false;
	}
	
	function getLastBackUp()
	{
		$query 	= 'SELECT time_created FROM #__imageshow_log WHERE action="backup" ORDER BY time_created DESC LIMIT 1';
		$this->_db->setQuery($query);
		return $this->_db->loadObject();			
	}
	
	function getLastRestore()
	{
		$query 	= 'SELECT time_created FROM #__imageshow_log WHERE action="restore" ORDER BY time_created DESC LIMIT 1';
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}
}
?>