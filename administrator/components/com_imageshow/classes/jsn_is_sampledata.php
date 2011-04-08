<?php
defined('_JEXEC') or die( 'Restricted access' );
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');
class JSNISSampledata
{
	function &getInstance()
	{
		static $instanceSampleData;
		if ($instanceSampleData == null)
		{
			$instanceSampleData = new JSNISSampledata();
		}
		return $instanceSampleData;
	}
	
	/**  
	 * Define link download, name of zip file, name of json file & prefix folder will be created in ../tmp
	 * $infor get from parse com_imageshow.xml
	 */
	function getPackageVersion($infor)
	{
		define("FILE_URL", 'http://demo.joomlashine.com/joomla-extensions/sample-data/jsn_'.$infor.'_sample_data_j15.zip');
		define("FILE_XML", 'jsn_'.$infor.'_sample_data.xml');
		define("PREFIX_FOLDER_NAME", 'jsn_'.$infor.'_sample_data_');
	}

	/**
	 *  Check environment allow to upload , zip file 
	 */
	function checkEnvironment()
	{
		if (!(bool) ini_get('file_uploads'))
		{
			$this->returnError('false', JText::_('ENABLE UPLOAD FUNCTION'));	
		}
		
		if (!extension_loaded('zlib')) 
		{
			$this->returnError('false', JText::_('ENABLE ZLIB'));
		}
		return true;
	}
	
	/**
	 * Check upload_max_file 
	 * Check upload file have been selected & correct format 
	 */
	function checkFileUpload()
	{
		$user_file 			= JRequest::getVar('install_package', null, 'files', 'array');
		$max_upload_file 	= ini_get('upload_max_filesize');
		$postion 			= strpos($max_upload_file, 'M');
		$max_allow 			= substr($max_upload_file, 0, $postion)*(1024*1024);
		
		if ($user_file['name'] == '') 
		{
			$this->returnError('false', JText::_('SAMPLE DATA NOT SELECTED'));	
		}
		
		if (trim(strtolower(JFile::getExt($user_file['name']))) != 'zip')
		{
			$this->returnError('false', JText::_('SAMPLE DATA INCORRECT FORMAT'));	
		}

		if($user_file['size'] >= $max_allow)
		{
			$this->returnError('false', JText::_('AMOUNT UPLOAD ALLOW').' '.$max_upload_file);
		}
		return true;
		
	}
	
	/**
	 * Upload package from local
	 */
	function getPackageFromUpload()
	{
		$this->checkFileUpload();
		$user_file 	= JRequest::getVar('install_package', null, 'files', 'array');
		$tmp_dest 	= JPath::clean(JPATH_ROOT.DS.'tmp'.DS.$user_file['name']);
		$tmp_src	= $user_file['tmp_name'];
		
		if (!JFile::upload($tmp_src, $tmp_dest))
		{
			$error = JText::_('FOLDER TMP IS UNWRITE');
			$this->returnError('false', $error);	
		}
		
		return 	$user_file['name'];
	}
	
	/**
	 * Extract package
	 */
	function unpackPackage($p_file)
	{
		$tmp_dest 		= JPATH_ROOT.DS.'tmp';
		$tmpdir			= uniqid(PREFIX_FOLDER_NAME);
		$archive_name 	= $p_file;
		$extract_dir 	= JPath::clean($tmp_dest.DS.dirname($p_file).DS.$tmpdir);
		$archive_name 	= JPath::clean($tmp_dest.DS.$archive_name);
		$result 		= JArchive::extract( $archive_name, $extract_dir);
		
		if ($result)
		{
			$path = $tmp_dest.DS.$tmpdir;
			return $path;
		}
		return false;
	}
	
	function executeInstallSampleData($data)
	{
		$db			=& JFactory::getDBO();
		$queries 	= array();
		
		foreach ($data as $rows)
		{
			$truncate 	= $rows->truncate;
			if (count($truncate))
			{
				foreach ($truncate as $value)
				{
					$queries [] = $value;
				}
			}
			$install 	= $rows->install;
			
			if (count($install))
			{
				foreach ($install as $value)
				{
					$queries [] = $value;
				}
			}
		}
		
		if (count($queries) != 0)
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '')
				{
					$db->setQuery($query);
					if (!$db->query())
					{
						$this->returnError("false", "ERROR QUERY DATABASE");
					}
				}
			}
			return true;
		}
		return false;	
	}
	
	function deleteTempFolderISD($path)
	{
		$path = JPath::clean($path);
		if (JFolder::exists($path))
		{	
			JFolder::delete($path);
			return true;
		}
		return false;	
	}
	
	function deleteISDFile($file)
	{
		$path = JPATH_ROOT.DS.'tmp'.DS.$file;
		
		if (JFile::exists($path))
		{
			JFile::delete($path);
			return true;
		}
		return false;
	}
	
	function returnError($result, $msg)
	{
		global $mainframe;
		
		if (is_array($msg))
		{
			foreach ($msg as $value)
			{
				JError::raiseWarning(100,JText::_($value));
			}
		}
		else
		{
			JError::raiseWarning(100,JText::_($msg));
		}
		
		$mainframe->redirect('index.php?option=com_imageshow&controller=maintenance&type=sampledata');
		return $result;
	}

	function checkFolderPermission()
	{
		$folderpath = JPATH_ROOT.DS.'tmp';
		if (is_writable($folderpath) == false)
		{	 
			$this->returnError('false','');
			return false;		
		}
		return true;
	}
	
	// convert json sampledata to object data
	function jsonSampleDataToObject($path)
	{
		$path 		= JPath::clean($path);	
		$objJSNJSON = JSNISFactory::getObj('classes.jsn_is_json');
		
		if (!$jsonString = @file_get_contents($path)) 
		{
			JError::raiseWarning(100,JText::_('Not found installation file in sample data package'));
			return false;
		}
		
		return $dataObj = $objJSNJSON->decode($jsonString);
		
	}
	
	// Prepare sampledata json
	function parserSampleData($path)
	{		
		$dataObj = $this->jsonSampleDataToObject($path);
		
		if ($dataObj === false)
		{
			return false;
		}
		
		if ($dataObj != null)
		{
			$arrayObj = array();
		
			$obj = new stdClass();
			$attributes = &$dataObj->product->attributes;
			
			if ($attributes)
			{
				if ($attributes->name != 'imageshow' || $attributes->author != 'joomlashine' || $attributes->description != 'JSN ImageShow' )
				{
					JError::raiseWarning(100,JText::_('JSON STRUCTURE WAS EDITED'));
					return false;
				}
				
				$obj->name 			= trim(strtolower($attributes->name));
				$obj->version 		= (isset($attributes->version)?$attributes->version:'');
				$obj->author 		= (isset($attributes->author)?$attributes->author:'');
				$obj->description 	= (isset($attributes->description)?$attributes->description:'');
			}
			else
			{
				JError::raiseWarning(100,JText::_('Incorrect file json sample data package'));
				return false;
			}	
					
			$arrayTruncate 	= array();
			$arrayInstall 	= array();
			$taskObj 		= &$dataObj->product->task;
			
			if ($taskObj == null)
			{
				JError::raiseWarning(100,JText::_('JSON STRUCTURE WAS EDITED'));
				return false;
			}
			
			foreach ($taskObj as $task)
			{
				// truncate table
				if (isset($task->name) && $task->name == 'dbtruncate')
				{ 
					foreach ($task->tables as $table)
					{
						foreach ($table->queries as $query)
						{
							$arrayTruncate[] = $query;
						}
					}
				}
				
				// install table
				if (isset($task->name) && $task->name == 'dbinstall')
				{ 
					foreach ($task->tables as $table)
					{
						foreach ($table->queries as $query)
						{
							$arrayInstall[] = $query;
						}
					}
				}
			}
			
			if (count($arrayTruncate) < 1 || count($arrayInstall) < 1)
			{
				JError::raiseWarning(100,JText::_('JSON STRUCTURE WAS EDITED'));
				return false;
			}
			
			$obj->truncate 	= $arrayTruncate;
			$obj->install 	= $arrayInstall;
			
			$arrayObj [$attributes->name] = $obj;	
			
			return $arrayObj;		
		}
		else
		{
			JError::raiseWarning(100,JText::_('JSON STRUCTURE WAS EDITED'));
			return false;
		}
	}
	
}