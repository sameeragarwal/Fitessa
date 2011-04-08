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

class JSNISProfile 
{
	var $_db = null;
	
	function JSNISProfile()
	{
		if ($this->_db == null) 
		{
			$this->_db =& JFactory::getDBO();
		}
	}

	function &getInstance()
	{
		static $instanceProfile;
		if ($instanceProfile == null)
		{
			$instanceProfile = new JSNISProfile();
		}
		return $instanceProfile;
	}	
	
	function getProfiles($title, $type)
	{
		$where 	= array();
		
		if ($title != '')
		{
			$where[] = 'LOWER(c.configuration_title) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $title, true ).'%', false );
		}
		
		if($type !=0)
		{
			$where[] = 'c.source_type = '.$type;
		}
		
		$where[] 	= 'c.source_type <>1';
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		$query		= 'SELECT c.*, COUNT(sl.showlist_id) AS totalshowlist 
					   FROM #__imageshow_configuration c 
					   LEFT JOIN #__imageshow_showlist sl ON c.configuration_id = sl.configuration_id '
					. $where
					.' GROUP BY c.configuration_id ORDER BY c.configuration_title ASC';		
		$this->_db->setQuery($query);		
		return $this->_db->loadObjectList();								
	}
	
	function deleteProfile($profileID)
	{
		$result = false;
		$query 	= 'DELETE FROM #__imageshow_configuration'
				. ' WHERE configuration_id = '.(int) $profileID;
		
		$this->_db->setQuery( $query );
		
		if (!$this->_db->query())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;			
		}
		
		$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
		$listShowlistID = $objJSNShowlist->getListShowlistIDByConfigID($profileID);	
		
		if (count($listShowlistID))
		{
			foreach ($listShowlistID as $value)
			{
				$values[] = $value[0];	
			}
			
			$listID = implode( ',', $values );	
			
			$query = 'UPDATE #__imageshow_showlist 
					  SET configuration_id = 0, showlist_source = 0 
					  WHERE showlist_id IN ( '.$listID.' )';	
			$this->_db->setQuery( $query );	
			$this->_db->query();
			
			$query = 'DELETE FROM #__imageshow_images WHERE showlist_id IN ( '.$listID.' )';
			$this->_db->setQuery( $query );
			$this->_db->query();									
		}
		return true;
	}
	function deleteProfileSelect($cid=array())	
	{
		$result = false;	
		
		if (count( $cid )) 
		{
			JArrayHelper::toInteger($cid);	
			$cids 	= implode( ',', $cid );		
			$query 	= 'DELETE FROM #__imageshow_configuration'
					. ' WHERE configuration_id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			$this->_db->query();
			
			$objJSNShowlist	= JSNISFactory::getObj('classes.jsn_is_showlist');
			$listShowlistID = $objJSNShowlist->getListShowlistID($cids);
			
			if (count($listShowlistID))
			{
				foreach ($listShowlistID as $value)
				{
					$values[] = $value[0];	
				}
				
				$listID = implode( ',', $values );	
				
				$query = 'UPDATE #__imageshow_showlist SET configuration_id = 0, showlist_source = 0 WHERE showlist_id IN ( '.$listID.' )';	
				$this->_db->setQuery( $query );
				$this->_db->query();
				
				$query = 'DELETE FROM #__imageshow_images WHERE showlist_id IN ( '.$listID.' )';
				$this->_db->setQuery( $query );
				$this->_db->query();				
			}
		}
		return true;
	}
	
	function getProfileIDFromShowListSrcFolder()
	{
		$profileID 	= array();
		
		$query 	= 'SELECT configuration_id FROM #__imageshow_showlist WHERE configuration_id <> 0 and showlist_source = 1';		
		$this->_db->setQuery( $query );
		$result	 	= $this->_db->loadRowList();
		
		if (count($result))
		{
			foreach ($result as $value)
			{				
				$profileID [] = $value[0];
			}
			return $profileID;			
		}
		return $profileID;			
	}
		
	function autoDelProfileSrcFolder()
	{
		$this->_db = JFactory::getDBO();
		$profileID = $this->getProfileIDFromShowListSrcFolder();

		if (is_array($profileID))
		{
			if (count($profileID))
			{
				$profileIDs = implode( ',', $profileID );
				$query 		= 'DELETE FROM #__imageshow_configuration WHERE configuration_id NOT IN ( '.$profileIDs.' ) AND source_type = 1';
			}
			else
			{
				$query 		= 'DELETE FROM #__imageshow_configuration WHERE source_type = 1';
			}
			
			$this->_db->setQuery( $query );
			$result = $this->_db->query();	
			
			if($result)
			{
				return true;
			}
		}
		return false;
	}
	
	function getParameters()
	{
		$query 	= 'SELECT * FROM #__imageshow_parameters';
		$this->_db->setQuery( $query );
		return $this->_db->loadObject();		
	}
	
	function saveParameters($post)
	{
		$query 	= 'SELECT * FROM #__imageshow_parameters';
		$this->_db->setQuery( $query );
		$resultCheck = $this->_db->loadAssoc( $query );
		
		if (count($resultCheck) > 0)
		{
			$query = 'UPDATE #__imageshow_parameters 
					  SET 
					  	general_swf_library 	= '.(int) $post['general_swf_library'].',
					  	root_url = '.(int) $post['root_url'].'  
					  WHERE id = '.$resultCheck['id'];
			$this->_db->setQuery( $query );
			$result = $this->_db->query();

			if ($result)
			{
				return true;
			}
		}
		else
		{
			$query = 'INSERT INTO 
							#__imageshow_parameters 
							(id, general_swf_library , root_url) 
					 VALUES 
							(1, '.(int) $post['general_swf_library'].',
							'.(int) $post['root_url'].')';
			
			$this->_db->setQuery( $query );
			$result = $this->_db->query();

			if ($result)
			{
				return true;
			}			
		}
		return false;			
	}
	
	function getProfileInfo($id)
	{
		$query 	= 'SELECT * FROM #__imageshow_configuration WHERE configuration_id='. (int)$id;
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}
	
	function saveProfileInfo($post)
	{
		$row 	=& JTable::getInstance('configuration', 'Table');
		$row->bind($post);
		$row->store();
		return true;
	}
}