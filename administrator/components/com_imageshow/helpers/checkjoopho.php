<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
class CheckJoomPhocaHelper
{
	function CheckJoomPhocaHelper(){

	}
 
    function getInstance() 
    {
		static $_instance;
		if( $_instance == NULL ) {
			$_instance = new CheckJoomPhocaHelper();
		}
		return $_instance;
    }
    
	function checkComInstalled()
	{
		$this->_checkComPhocaInstalled();
		$this->_checkComJoomgaInstalled();
		
		return true;
	}
	
	function _checkComPhocaInstalled()
	{
		$db = JFactory::getDBO();		
		$queryPhoca 	= "SELECT COUNT(*) FROM #__components WHERE STRCMP(`option`, 'com_phocagallery') = 0";
		$db->setQuery($queryPhoca);
		$resultPhoca 	=  $db->loadRow();
		
		if($resultPhoca[0] == 0)
		{
			$showlistOfPhoca = $this->_getShowlists(4);
			
			if(count($showlistOfPhoca))
			{		
				foreach ($showlistOfPhoca as $value){
					$showlistIDOfPhocas[] = $value[0];
				}
				
				$showlistIDOfPhoca = implode(',', $showlistIDOfPhocas);
				
				$queryUpdateShowlistSourceOfPhoca = 'UPDATE 
														#__imageshow_showlist 
													 SET 
													 	configuration_id = 0, 
													   	showlist_source = 0 
													 WHERE 
													  	showlist_id IN ( '.$showlistIDOfPhoca.' )';
					
				$db->setQuery($queryUpdateShowlistSourceOfPhoca);
				$db->query();
								
				$queryDeleteImagesSourceOfPhoca = 'DELETE 
														imgs 
												   FROM 
												   		#__imageshow_showlist sl 
												   INNER JOIN 
												   		#__imageshow_images imgs ON sl.showlist_id = imgs.showlist_id 
												   WHERE 
												   		sl.showlist_id IN ('.$showlistIDOfPhoca.')';
				
				$db->setQuery($queryDeleteImagesSourceOfPhoca);
				$db->query();
			}			
		}
		return true;
	}
	
	function _checkComJoomgaInstalled()
	{
		$db 		 = JFactory::getDBO();		
		$queryJoomga = "SELECT COUNT(*) FROM #__components WHERE STRCMP(`option`, 'com_joomgallery') = 0";
		$db->setQuery($queryJoomga);
		$resultJoomga 	=  $db->loadRow();
		
		if($resultJoomga[0] == 0)
		{
			$showlistOfJoomga = $this->_getShowlists(5);
			
			if(count($showlistOfJoomga))
			{		
				foreach ($showlistOfJoomga as $value){
					$showlistIDOfJoomgas[] = $value[0];
				}
				
				$showlistIDOfJoomga = implode(',', $showlistIDOfJoomgas);
				
				$queryUpdateShowlistSourceOfJoomga	= 'UPDATE 
															#__imageshow_showlist 
													   SET 
													   		configuration_id = 0, 
													   		showlist_source = 0 
													   WHERE 
													   		showlist_id IN ( '.$showlistIDOfJoomga.' )';
					
				$db->setQuery($queryUpdateShowlistSourceOfJoomga);
				$db->query();
								
				$queryDeleteImagesSourceOfJoomga = 'DELETE 
														imgs 
													FROM 
													 	#__imageshow_showlist sl 
													INNER JOIN 
													   	#__imageshow_images imgs ON sl.showlist_id = imgs.showlist_id 
													WHERE 
														sl.showlist_id IN ('.$showlistIDOfJoomga.')';
				
				$db->setQuery($queryDeleteImagesSourceOfJoomga);
				$db->query();
			}
			
		}
		return true;
	}	
	
	function _getShowlists($sourceType)
	{
		$db = JFactory::getDBO();		
		$query = 'SELECT showlist_id FROM #__imageshow_showlist WHERE showlist_source = '.(int) $sourceType;
		$db->setQuery($query);
		return $db->loadRowList();
	}	
}
?>