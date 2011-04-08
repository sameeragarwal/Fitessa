<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 3.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISMediaManager
{
	var $comMediaBase 		= '';
	var $comMediaBaseURL 	= '';
	
	function JSNISMediaManager()
	{
		$this->comMediaBase		= JPATH_ROOT.DS.'images';
		$this->comMediaBaseURL 	= JURI::root().'images';	
	}
	
	function &getInstance()
	{
		static $instanceMediaManager;
		if ($instanceMediaManager == null)
		{
			$instanceMediaManager = new JSNISMediaManager();
		}
		return $instanceMediaManager;
	}	
	
	function getStateFolder()
	{
		$folder = JRequest::getVar('folder', '', '', 'path');
		
		$parent = str_replace("\\", "/", $folder);
		$parent = ($parent == '.') ? null : $parent;
		
		return $parent;
	}
	
	function getFolderList($base = null)
	{
		global $mainframe;

		// Get some paths from the request
		if (empty($base)) {
			$base = $this->comMediaBase;
		}
		
		// Get the list of folders
		jimport('joomla.filesystem.folder');
		$folders = JFolder::folders($base, '.', true, true);
		
		// Load appropriate language files
		$lang = & JFactory::getLanguage();
		$lang->load('', JPATH_ADMINISTRATOR);
		$lang->load(JRequest::getCmd( 'option' ), JPATH_ADMINISTRATOR);
		
		$document =& JFactory::getDocument();
		$document->setTitle(JText::_('Insert Image'));

		// Build the array of select options for the folder list
		$options[] = JHTML::_('select.option', "","/");
		
		foreach ($folders as $folder) {
			
			$folder 	= str_replace($this->comMediaBase, "", $folder);
			
			$value		= str_replace(DS,"/", substr($folder, 1));
			$text	 	= str_replace(DS, "/", $folder);		
			$options[] 	= JHTML::_('select.option', $value, $text);
		}
		
		// Sort the folder list array
		if (is_array($options)) {
			sort($options);
		}
		
		// Create the drop-down folder select list
		$list = JHTML::_('select.genericlist',  $options, 'folderlist', "class=\"inputbox\" size=\"1\" onchange=\"JSNISImageManager.setFolder(this.options[this.selectedIndex].value)\" ", 'value', 'text', $base);
		return $list;
	}
	
	function getList()
	{
		// Get current path from request
		$current = $this->getStateFolder();
		
		// If undefined, set to empty
		if ($current == 'undefined') {
			$current = '';
		}

		// Initialize variables
		if (strlen($current) > 0) 
		{
			$basePath = $this->comMediaBase.DS.$current;
			//$basePath = $current;
		}
		else
		{
			$basePath = $this->comMediaBase;
		}
		
		$mediaBase = str_replace(DS, '/', $this->comMediaBase.'/');

		$images 	= array ();
		$folders 	= array ();
		$docs 		= array ();
		
		// Get the list of files and folders from the given folder
		$fileList 	= JFolder::files($basePath);
		$folderList = JFolder::folders($basePath);
		jimport('joomla.filesystem.file');
		
		// Iterate over the files if they exist
		if ($fileList !== false) 
		{
			foreach ($fileList as $file)
			{
				if (is_file($basePath.DS.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html') {
					$tmp = new JObject();
					$tmp->name = $file;
					$tmp->path = str_replace(DS, '/', JPath::clean($basePath.DS.$file));
					$tmp->path_relative = str_replace($mediaBase, '', $tmp->path);
					$tmp->size = filesize($tmp->path);

					$ext = strtolower(JFile::getExt($file));
					switch ($ext)
					{
						// Image
						case 'jpg':
						case 'png':
						case 'gif':
						case 'xcf':
						case 'odg':
						case 'bmp':
						case 'jpeg':
							$info = @getimagesize($tmp->path);
							$tmp->width		= @$info[0];
							$tmp->height	= @$info[1];
							$tmp->type		= @$info[2];
							$tmp->mime		= @$info['mime'];

							$filesize		= MediaHelper::parseSize($tmp->size);

							if (($info[0] > 60) || ($info[1] > 60)) 
							{
								$dimensions = MediaHelper::imageResize($info[0], $info[1], 60);
								$tmp->width_60 = $dimensions[0];
								$tmp->height_60 = $dimensions[1];
							} 
							else 
							{
								$tmp->width_60 = $tmp->width;
								$tmp->height_60 = $tmp->height;
							}

							if (($info[0] > 16) || ($info[1] > 16)) 
							{
								$dimensions = MediaHelper::imageResize($info[0], $info[1], 16);
								$tmp->width_16 = $dimensions[0];
								$tmp->height_16 = $dimensions[1];
							}
							else 
							{
								$tmp->width_16 = $tmp->width;
								$tmp->height_16 = $tmp->height;
							}
							
							$images[] = $tmp;
							break;
						// Non-image document
						default:
							
							$iconfile_32 = JPATH_ADMINISTRATOR.DS."components".DS."com_imageshow".DS."assets".DS."images".DS."mime-icon-32".DS.$ext.".png";
							
							if (file_exists($iconfile_32)) {
								$tmp->icon_32 = "components/com_imageshow/assets/images/mime-icon-32/".$ext.".png";
							} else {
								$tmp->icon_32 = "components/com_imageshow/assets/images/con_info.png";
							}
							
							$iconfile_16 = JPATH_ADMINISTRATOR.DS."components".DS."com_imageshow".DS."assets".DS."images".DS."mime-icon-16".DS.$ext.".png";
							
							if (file_exists($iconfile_16)) {
								$tmp->icon_16 = "components/com_imageshow/assets/images/mime-icon-16/".$ext.".png";
							} else {
								$tmp->icon_16 = "components/com_imageshow/assets/images/con_info.png";
							}
							
							$docs[] = $tmp;
							break;
					}
				}
			}
		}

		// Iterate over the folders if they exist
		if ($folderList !== false) 
		{
			foreach ($folderList as $folder) 
			{
				$tmp 				= new JObject();
				$tmp->name 			= basename($folder);
				$tmp->path 			= str_replace(DS, '/', JPath::clean($basePath.DS.$folder));
				$tmp->path_relative = str_replace($mediaBase, '', $tmp->path);
				$count 				= MediaHelper::countFiles($tmp->path);
				$tmp->files 		= $count[0];
				$tmp->folders 		= $count[1];

				$folders[] = $tmp;
			}
		}
		
		$list = array('folders' => $folders, 'docs' => $docs, 'images' => $images);
		return $list;
	}
		
	function getImages()
	{
		$list = $this->getList();
		return $list['images'];
	}

	function getFolders()
	{
		$list = $this->getList();
		return $list['folders'];
	}
	
	function setFolder($index = 0, &$instance)
	{
		if (isset($instance->folders[$index])) {
			$instance->_tmp_folder = $instance->folders[$index];
		} else {
			$instance->_tmp_folder = new JObject;
		}
	}
	
	function setImage($index = 0, &$instance)
	{
		if (isset($instance->images[$index])) {
			$instance->_tmp_img = $instance->images[$index];
		} else {
			$instance->_tmp_img = new JObject;
		}
	}

	function setPath($mediaBase, $mediaBaseURL)
	{
		$this->comMediaBase 	= $mediaBase;
		$this->comMediaBaseURL 	= $mediaBaseURL;
	}
	
	function setMediaBasePath()
	{
		$act = JRequest::getCmd('act','custom');
		
		if ($act == 'custom' || $act == 'showlist')
		{
			$this->setPath(JPATH_ROOT.DS.'images', JURI::root().'images');
		}
	}
	
	function upload()
	{
		global $mainframe;		
		
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );
		$file 		= JRequest::getVar( 'Filedata', '', 'files', 'array' );
		$folder		= JRequest::getVar( 'folder', '', '', 'path' );
		$format		= JRequest::getVar( 'format', 'html', '', 'cmd');
		$return		= JRequest::getVar( 'return-url', null, 'post', 'base64' );
		$err		= null;
		
		// Make the filename safe
		jimport('joomla.filesystem.file');
		$file['name']	= JFile::makeSafe($file['name']);

		if (isset($file['name'])) 
		{
			if($folder == '')
			{
				$filepath = JPath::clean(JPATH_ROOT.DS.'images'.DS.strtolower($file['name']));
			}
			else
			{
				$filepath = JPath::clean(JPATH_ROOT.DS.'images'.DS.$folder.DS.strtolower($file['name']));
			}

			if (!MediaHelper::canUpload( $file, $err )) 
			{
				if ($format == 'json') 
				{
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Invalid: '.$filepath.': '.$err));
					header('HTTP/1.0 415 Unsupported Media Type');
					jexit('Error. Unsupported Media Type!');
				}
				else
				{
					JError::raiseNotice(100, JText::_($err));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			}

			if (JFile::exists($filepath)) 
			{
				if ($format == 'json') 
				{
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'File already exists: '.$filepath));
					header('HTTP/1.0 409 Conflict');
					jexit('Error. File already exists');
				}
				else 
				{
					JError::raiseNotice(100, JText::_('Error. File already exists'));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			}

			if (!JFile::upload($file['tmp_name'], $filepath))
			{
				if ($format == 'json') 
				{
					jimport('joomla.error.log');
					$log = &JLog::getInstance('upload.error.php');
					$log->addEntry(array('comment' => 'Cannot upload: '.$filepath));
					header('HTTP/1.0 400 Bad Request');
					jexit('Error. Unable to upload file');
				}
				else 
				{
					JError::raiseWarning(100, JText::_('Error. Unable to upload file'));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			} 
			else 
			{
				if ($format == 'json') 
				{
					jimport('joomla.error.log');
					$log = &JLog::getInstance();
					$log->addEntry(array('comment' => $folder));
					jexit('Upload complete');
				} 
				else 
				{
					$mainframe->enqueueMessage(JText::_('Upload complete'));
					// REDIRECT
					if ($return) {
						$mainframe->redirect(base64_decode($return).'&folder='.$folder);
					}
					return;
				}
			}
		}
		else 
		{
			$mainframe->redirect('index.php', 'Invalid Request', 'error');
		}
	}
}