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
require_once( JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_maintenance.php');
jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.utilities.simplexml');
jimport('joomla.filesystem.file');

class ImageShowModelMaintenance extends JModel 
{
	var $_lang = array();
	
	function __construct()
	{
		parent::__construct();
		$this->setLang();
	}
	
	function setLang()
	{			
		$objectReadxmlDetail 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();	
		$this->_lang 			= $infoXmlDetail['langs'];
	}	
	
	function backup ($showlists = false, $showcases = false, $timestamp = false, $filename = '')
	{
		global $objectLog;
		
		if(!$showlists && !$showcases)
		{
			return false;
		} 
		
		$objectReadxmlDetail 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();			
		$config 				= new JConfig();
		$user					=& JFactory::getUser();
		$userID					= $user->get ('id');		
		$database 				= $config->db;
		$db 					=& JFactory::getDBO();

		if(!empty($filename) && !is_null($filename))
		{
			$fileZipName = $filename;
		}
		else
		{
			$fileZipName = date('YmdHis');
		}		
		
		if($timestamp)
		{
			$fileZipName .='_'.date('YmdHis');
		}
		
		$objectLog->addLog($userID, JRequest::getURI(), $fileZipName, 'maintenance', 'backup');
		
		$fileBackupName 		 = "jsn_".JString::strtolower(@$infoXmlDetail['realName']).'_backup_db.xml';
		$objJSNISMaintenance	 = new JSNISMaintenance('database');
		$xmlString  		 	 = $objJSNISMaintenance->renderXMLData($showlists, $showcases);

			
		if (JFile::write(JPATH_ROOT.DS.'tmp'.DS.$fileBackupName, $xmlString))
		{
			$config = JPATH_ROOT.DS.'tmp'.DS. $fileZipName . '.zip';
			$zip 	= JSNISFactory::getObj('classes.jsn_is_archive', 'JSNISZIPFile', $config);
			$zip->setOptions(array('inmemory' => 1, 'recurse' => 0, 'storepaths' => 0)); 
			$zip->addFiles(JPATH_ROOT.DS.'tmp'.DS. $fileBackupName); 
			$zip->createArchive();
			$zip->downloadFile();	
			$FileDelte = JPATH_ROOT.DS.'tmp'.DS. $fileBackupName;
			JFile::delete($FileDelte);	
			exit();			
		}
		 return true;		
	}	
}
