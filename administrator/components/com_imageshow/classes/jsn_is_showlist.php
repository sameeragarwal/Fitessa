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

class JSNISShowlist
{
	var $_db = null;

	function JSNISShowlist()
	{
		if ($this->_db == null)
		{
			$this->_db =& JFactory::getDBO();
		}
	}

	function &getInstance()
	{
		static $instanceShowlist;
		if ($instanceShowlist == null)
		{
			$instanceShowlist = new JSNISShowlist();
		}
		return $instanceShowlist;
	}

	function getTitleShowList($showListID)
	{
		$query 	= "SELECT showlist_title
				   FROM #__imageshow_showlist
				   WHERE showlist_id = ".(int)$showListID;
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function renderShowlistComboBox($ID, $elementText, $elementName, $parameters = '')
	{
		$query	= 'SELECT showlist_title AS text, showlist_id AS value
				   FROM #__imageshow_showlist
				   ORDER BY showlist_title ASC';
		$this->_db->setQuery($query);
		$data 	= $this->_db->loadObjectList();

		array_unshift($data, JHTML::_('select.option', '0', '- '.JText::_($elementText).' -', 'value', 'text'));
		return JHTML::_('select.genericlist', $data, $elementName, $parameters, 'value', 'text', $ID);
	}

	function updateDateModifiedShowlist($showListID)
	{
		$date 						=& JFactory::getDate();
		$showlist 					= new stdClass;
		$showlist->showlist_id 		= $showListID;
		$showlist->date_modified 	= JHTML::_('date', $date->toUnix(), '%Y-%m-%d %H:%M:%S');

		$this->_db->updateObject( '#__imageshow_showlist', $showlist, 'showlist_id' );
	}

	function getShowListByID($showlistID, $published = true, $resultType = 'loadAssoc')
	{
		$condition = '';

		if ($published == true)
		{
			$condition = ' published = 1 AND ';
		}

		$query 	= 'SELECT * FROM #__imageshow_showlist WHERE '.$condition.' showlist_id = '.(int)$showlistID;
		$this->_db->setQuery($query);

		return $this->_db->$resultType();
	}

	function countShowList()
	{
		$query	= 'SELECT COUNT(*) FROM #__imageshow_showlist';
		$this->_db->setQuery( $query );
		return $this->_db->loadRow();
	}

	function getListShowlistIDByConfigID($configID)
	{
		$query 	= 'SELECT showlist_id FROM #__imageshow_showlist WHERE configuration_id IN ('.$configID.')';
		$this->_db->setQuery( $query );
		return $this->_db->loadRowList();
	}

	function removeAllImageByShowlistID($showlistID)
	{
		if (!empty($showlistID))
		{
			$objJSNThumb 	= JSNISFactory::getObj('classes.jsn_is_imagethumbnail');
			$objJSNThumb->deleteAllThumbnailByShowlistID($showlistID);

			$query 	= 'DELETE FROM #__imageshow_images WHERE showlist_id='.(int) $showlistID;
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				return false;
			}
			return true;
		}
		return false;
	}

	// prepare data showlist for encode to json data
	function getShowlist2JSON($URL, $showlistID)
	{
		$objJSNImages 	= JSNISFactory::getObj('classes.jsn_is_images');
		$objJSNSource 	= JSNISFactory::getObj('classes.jsn_is_source');
		$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');

		$data 			= $objJSNImages->getImagesByShowlistID($showlistID);
		$sourceType 	= $objJSNSource->getSourceTypeByShowlistID($showlistID);
		$showlistInfo 	= $objJSNShowlist->getShowListByID($showlistID);

		$document =& JFactory::getDocument();
		$document->setMimeEncoding('application/json');

		$dataObj = new stdClass();

		//showlist
		$showlistObj = new stdClass();
		$showlistObj->{'title'} 		= $showlistInfo['showlist_title'];
		$showlistObj->{'description'} 	= $showlistInfo['description'];
		$showlistObj->{'link'} 			= $showlistInfo['showlist_link'];

			//images object
			$imagesObj 		= new stdClass();
			$imageDetailObj = new stdClass();
			$array_image 	= array();

			switch ($sourceType)
			{
				case 'flickr':
					$objJSNFlickr 		= JSNISFactory::getObj('classes.jsn_is_flickr');
					$imageSizeFlickr	= $objJSNFlickr->getFlickrImagesSizeByShowlistID($showlistID);
					$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');

					for ($i=0, $n=count( $data ); $i < $n; $i++)
					{
						$row 								= &$data[$i];
						$imageDetailObj 					= new stdClass();
						$imageDetailObj->{'thumbnail'} 		= $row->image_small;
						$imageBig 							= explode(',', $row->image_big);
						$imageLink 							= $objJSNUtils->checkValueArray($imageBig,$imageSizeFlickr['flickr_image_size']);
						$imageDetailObj->{'image'} 			= $imageLink;
						$imageDetailObj->{'title'} 			= $row->image_title;
						$imageDetailObj->{'description'} 	= (!is_null($row->image_description))?$row->image_description:'';
						$imageDetailObj->{'link'} 			= $row->image_link;
						$array_image[] 						= $imageDetailObj;
					}
				break;
				case 'joomga':
					$objJSNJoomga 	= JSNISFactory::getObj('classes.jsn_is_joomga');
					$configJoomga 	= $objJSNJoomga->getJoomgaConfig();
					$syncData 		= $objJSNJoomga->getSyncImages($showlistID);

					if (!empty($syncData))
					{
						$data = $syncData;
					}

					for ($i=0, $n=count( $data ); $i < $n; $i++)
					{
						$row 								= &$data[$i];
						$originalPhotoInfoOfJoomga 			= $objJSNJoomga->getOriginalPhotoInfoOfJoomgaByImageID($row->image_extid);
						$imageDetailObj 					= new stdClass();
						$imageDetailObj->{'thumbnail'} 		= $URL.$row->image_small;
						$imageDetailObj->{'image'} 			= $URL.$row->image_big;
						$imageDetailObj->{'title'} 			= ($row->image_title!='')?$row->image_title:$originalPhotoInfoOfJoomga['imgtitle'];
						$imageDetailObj->{'description'} 	= ($row->image_description!='')?strip_tags($row->image_description):strip_tags($originalPhotoInfoOfJoomga['imgtext']);
						$imageDetailObj->{'link'} 			= $row->image_link;
						$array_image[] 						= $imageDetailObj;
					}
				break;
				case 'phoca':
					$objJSNPhoca 	= JSNISFactory::getObj('classes.jsn_is_phoca');
					$syncData 		= $objJSNPhoca->getSyncImages($showlistID);

					if (!empty($syncData))
					{
						$data = $syncData;
					}

					for ($i=0, $n=count( $data ); $i < $n; $i++)
					{
						$row 								= &$data[$i];
						$originalPhotoInfoOfPhoca 			= $objJSNPhoca->getOriginalPhotoInfoOfPhocaByImageID($row->image_extid);
						$imageDetailObj 					= new stdClass();
						$imageDetailObj->{'thumbnail'} 		= $URL.$row->image_small;
						$imageDetailObj->{'image'} 			= $URL.$row->image_big;
						$imageDetailObj->{'title'} 			= ($row->image_title!='')?$row->image_title:$originalPhotoInfoOfPhoca['title'];
						$imageDetailObj->{'description'} 	= ($row->image_description!='')?strip_tags($row->image_description):strip_tags($originalPhotoInfoOfPhoca['description']);
						$imageDetailObj->{'link'} 			= $row->image_link;
						$array_image[] 						= $imageDetailObj;
					}
				break;
				case 'picasa':
					for ($i=0, $n=count( $data ); $i < $n; $i++)
					{
						$row 								= &$data[$i];
						$imageDetailObj 					= new stdClass();
						$imageDetailObj->{'thumbnail'} 		= $row->image_small;
						$imageDetailObj->{'image'} 			= $row->image_big;
						$imageDetailObj->{'title'} 			= $row->image_title;
						$imageDetailObj->{'description'} 	= (!is_null($row->image_description))?$row->image_description:'';
						$imageDetailObj->{'link'} 			= $row->image_link;
						$array_image[$i] 					= $imageDetailObj;
					}
				break;
				default:
					$folder   = JSNISFactory::getObj('classes.jsn_is_folder');
					$syncData = $folder->getSyncImages($showlistID);

					if (!empty ($syncData))
					{
						$data = $syncData;
					}

					for ($i=0, $n=count( $data ); $i < $n; $i++)
					{
						$row = &$data[$i];
						$imageDetailObj 					= new stdClass();
						$imageDetailObj->{'thumbnail'} 		= $URL.$row->image_small;
						$imageDetailObj->{'image'}			= $URL.$row->image_big;
						$imageDetailObj->{'title'} 			= $row->image_title;
						$imageDetailObj->{'description'} 	= (!is_null($row->image_description))?$row->image_description:'';
						$imageDetailObj->{'link'} 			= $row->image_link;
						$array_image[] 						= $imageDetailObj;
					}
					break;
			}

			$imagesObj->{'image'} = $array_image;
			// end images object

		$showlistObj->{'images'} = $imagesObj;
		$dataObj->{'showlist'} = $showlistObj;
		// end show list

		return $dataObj;
	}

	function getAbumTreeByShowlistID($showlistID)
	{
		$albumTree = '';

		if (!empty($showlistID))
		{
			$objJSNSource  = JSNISFactory::getObj('classes.jsn_is_source');
			$sourceType    = $objJSNSource->getSourceTypeByShowlistID($showlistID);
			$objJSNImages  = JSNISFactory::getObj('classes.jsn_is_images');
			$syncAlbum 	   = $objJSNImages->getSyncAlbumsByShowlistID($showlistID);
			$syncAlbum 	   = (count($syncAlbum) > 0) ? $syncAlbum : array();
			switch ($sourceType)
			{
				case 'folder':
					$objJSNFolder	= JSNISFactory::getObj('classes.jsn_is_folder');
					$albumTree		= $objJSNFolder->loadTreeFolder($syncAlbum);
				break;
				case 'flickr':
					$objJSNFlickr 	= JSNISFactory::getObj('classes.jsn_is_flickr');
					$albumTree 		= $objJSNFlickr->getAlbumslist();
				break;
				case 'picasa':
					$objJSNPicasa 	= JSNISFactory::getObj('classes.jsn_is_picasa');
					$albumTree 		= $objJSNPicasa->getAlbumsList();
				break;
				case 'phoca':
					$objJSNPhoca 	= JSNISFactory::getObj('classes.jsn_is_phoca');
					$albumTree 		= $objJSNPhoca->getCategoriesTree($syncAlbum);
				break;
				case 'joomga':
					$objJSNJoomga 	= JSNISFactory::getObj('classes.jsn_is_joomga');
					$albumTree 		= $objJSNJoomga->getCategoriesTree($syncAlbum);
				break;
			}
		}

		return $albumTree;
	}

	function getRemoteImageBySourceTypeFromShowlist($showlistID, $album)
	{
		$images = '';
		if (!empty($showlistID))
		{
			$objJSNSource  	= JSNISFactory::getObj('classes.jsn_is_source');
			$sourceType 	= $objJSNSource->getSourceTypeByShowlistID($showlistID);

			switch ($sourceType)
			{
				case 'folder':
					$objJSNFolder 	= JSNISFactory::getObj('classes.jsn_is_folder');
					$images 		= $objJSNFolder->loadImageInFolder($album);
				break;
				case 'flickr':
					$objJSNFlickr 	= JSNISFactory::getObj('classes.jsn_is_flickr');
					$images 		= $objJSNFlickr->getImagesFromFlickrByAlbumID($album);
				break;
				case 'picasa':
					$objJSNPicasa 	= JSNISFactory::getObj('classes.jsn_is_picasa');
					$images 		= $objJSNPicasa->getImagesFromPicasaByAlbumID($album);
				break;
				case 'phoca':
					$objJSNPhoca 	= JSNISFactory::getObj('classes.jsn_is_phoca');
					$images			= $objJSNPhoca->getImagesFromPhocaByCatID($album);
				break;
				case 'joomga':
					$objJSNJoomga 	= JSNISFactory::getObj('classes.jsn_is_joomga');
					$images			= $objJSNJoomga->getImagesFromJoomgaByCatID($album);
				break;
			}
		}
		return $images;
	}

	function deleteRemoteImageBySourceTypeFromShowlist($info)
	{
		if (!empty($info->showlistID))
		{
			$objJSNSource 	= JSNISFactory::getObj('classes.jsn_is_source');
			$sourceType 	= $objJSNSource->getSourceTypeByShowlistID((int)$info->showlistID);

			switch ($sourceType)
			{
				case 'folder':
					$objJSNFolder = JSNISFactory::getObj('classes.jsn_is_folder');
					return $objJSNFolder->deleteFolderImages($info->arrayImgExtID, $info->showlistID);
				break;
				case 'flickr':
					$objJSNFlickr = JSNISFactory::getObj('classes.jsn_is_flickr');
					return $objJSNFlickr->deleteFlickrData($info->arrayImgExtID, $info->showlistID);
				break;
				case 'picasa':
					$objJSNPicasa = JSNISFactory::getObj('classes.jsn_is_picasa');
					return $objJSNPicasa->deletePicasaData($info->arrayImgExtID, $info->showlistID);
				break;
				case 'phoca':
					$objJSNPhoca = JSNISFactory::getObj('classes.jsn_is_phoca');
					return  $objJSNPhoca->deletePhocaData($info->arrayImgExtID, $info->showlistID);
				break;
				case 'joomga':
					$objJSNJoomga = JSNISFactory::getObj('classes.jsn_is_joomga');
					return $objJSNJoomga->deleteJoomgaData($info->arrayImgExtID, $info->showlistID);
				break;
			}
		}
	}

	function insertRemoteImageBySourceTypeFromShowlist($info)
	{
		if (!empty($info->showlistID))
		{
			$objJSNSource 	= JSNISFactory::getObj('classes.jsn_is_source');
			$sourceType 	= $objJSNSource->getSourceTypeByShowlistID((int)$info->showlistID);

			switch($sourceType)
			{
				case 'folder':
					$objJSNFolder = JSNISFactory::getObj('classes.jsn_is_folder');
					return $objJSNFolder->insertFolderImages($info->imgExtID, $info->imgSmall, $info->imgMedium, $info->imgBig, $info->imgTitle, $info->imgDescription, $info->imgLink, $info->albumID, $info->showlistID, $info->customData);
				break;
				case 'flickr':
					$objJSNFlickr = JSNISFactory::getObj('classes.jsn_is_flickr');
					return $objJSNFlickr->insertFlickrData($info->imgExtID, $info->imgSmall, $info->imgMedium, $info->imgBig, $info->imgTitle, $info->imgDescription, $info->imgLink, $info->albumID, $info->showlistID, $info->customData);
				break;
				case 'picasa':
					$objJSNPicasa = JSNISFactory::getObj('classes.jsn_is_picasa');
					return $objJSNPicasa->insertPicasaData($info->imgExtID, $info->imgSmall, $info->imgMedium, $info->imgBig, $info->imgTitle, $info->imgDescription, $info->imgLink, $info->albumID, $info->showlistID, $info->customData);
				break;
				case 'phoca':
					$objJSNPhoca = JSNISFactory::getObj('classes.jsn_is_phoca');
					return  $objJSNPhoca->insertPhocaData($info->imgExtID, $info->imgSmall, $info->imgMedium, $info->imgBig, $info->imgTitle, $info->imgDescription, $info->imgLink, $info->albumID, $info->showlistID, $info->customData);
				break;
				case 'joomga':
					$objJSNJoomga = JSNISFactory::getObj('classes.jsn_is_joomga');
					return $objJSNJoomga->insertJoomgaData($info->imgExtID, $info->imgSmall, $info->imgMedium, $info->imgBig, $info->imgTitle, $info->imgDescription, $info->imgLink, $info->albumID, $info->showlistID, $info->customData);
				break;
			}
		}
	}

	function synchronize($arrayImageID, $showlistID)
	{
		if (!empty($showlistID))
		{
			$objJSNSource = JSNISFactory::getObj('classes.jsn_is_source');
			$sourceType = $objJSNSource->getSourceTypeByShowlistID((int)$showlistID);

			switch ($sourceType)
			{
				case 'folder':
					$controlObj = JSNISFactory::getObj('classes.jsn_is_folder');
				break;
				case 'flickr':
					$controlObj = JSNISFactory::getObj('classes.jsn_is_flickr');
				break;
				case 'picasa':
					$controlObj = JSNISFactory::getObj('classes.jsn_is_picasa');
				break;
				case 'phoca':
					$controlObj = JSNISFactory::getObj('classes.jsn_is_phoca');
				break;
				case 'joomga':
					$controlObj = JSNISFactory::getObj('classes.jsn_is_joomga');
				break;
			}
			return $controlObj->getPhotoLocalList($arrayImageID,$showlistID);
		}
	}

	function refreshFlexAfterDeleteByShowlistID($showlistID, $albumExtID, $type = 'removeAll')
	{
		$showlistImages = array();
		$albumImages 	= array();

		if	($type != 'removeAll' && $showlistID)
		{
			$objJSNImages   = JSNISFactory::getObj('classes.jsn_is_images');
			$showlistImages = $objJSNImages->getImagesByShowlistID($showlistID);
		}

		if ($albumExtID)
		{
			$albumImages 	= $this->getRemoteImageBySourceTypeFromShowlist((int)$showlistID, $albumExtID);
		}

		$obj = new stdClass();
		$obj->showlist_images 	= $showlistImages;
		$obj->album_images 		= $albumImages;

		return $obj;
	}

	function getConfigTitleByShowlistID($showlistID)
	{
		if ($showlistID)
		{
			$showlistTable  = JTable::getInstance('showlist', 'Table');
			$configTable	= JTable::getInstance('configuration', 'Table');

			$showlistTable->load((int)$showlistID);

			if ($showlistTable->showlist_id)
			{
				switch ($showlistTable->showlist_source)
				{
					case '1': //folder
						$configTitle = 'Images Folder(s)';
					break;
					case '2': //flickr
					case '3': // picasa
						$configTable->load((int)$showlistTable->configuration_id);
						$configTitle = $configTable->configuration_title;
					break;
					case '4': // phoca
						$configTitle = 'Phoca Gallery';
					break;
					case '5': // joomga
						$configTitle = 'JoomGallery';
					break;
					default:
						$configTitle = 'Images Folder(s)';
					break;
				}
				return $configTitle;
			}
			return false;
		}
	}

	function insertHitsShowlist($showListID)
	{
		$query 	= 'UPDATE #__imageshow_showlist SET hits = hits + 1 WHERE showlist_id = '.(int)$showListID;
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	function getShowlistID()
	{
		$arrayID = array();
		$query	= 'SELECT showlist_id FROM #__imageshow_showlist';

		$this->_db->setQuery( $query );
		$result = $this->_db->loadAssocList();

		if(count($result))
		{
			foreach ($result as $value)
			{
				$arrayID[] = $value['showlist_id'];
			}
			return $arrayID;
		}
		return false;
	}

	function checkShowlistLimition()
	{
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$hashString			= $objJSNUtils->checkHashString();
		$count 				= $this->countShowlist();

		if(@$count[0] >= 3 && $hashString == false)
		{
			$msg = JText::_('You have reached the limitation of 3 showlists in Free edition');
			JError::raiseNotice(100, $msg);
		}
	}
}