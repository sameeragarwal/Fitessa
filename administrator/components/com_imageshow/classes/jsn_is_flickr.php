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

class JSNISFlickr{

	var $_db 				= null;
	var $_showlistID 		= null;
	var $_configurationID 	= null;

	function &getInstance()
	{
		static $instanceFlickr;

		if ($instanceFlickr == null)
		{
			$instanceFlickr = new JSNISFlickr();
		}
		return $instanceFlickr;
	}

	function JSNISFlickr()
	{
		global $mainframe;

		if (empty($this->_db))
		{
			$this->_db = &JFactory::getDBO();
		}

		$showlistID	= JRequest::getInt('showlist_id');

		$this->setShowlistID($showlistID);
		$this->setConfigurationID();
	}

	function setShowlistID($id)
	{
		$this->_showlistID		= $id;
	}

	function setConfigurationID()
	{
		$query = 'SELECT configuration_id FROM #__imageshow_showlist WHERE showlist_id='.$this->_showlistID;

		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();
		$this->_configurationID = $result[0];
	}

	function loadPhpFlickrClasses()
	{
		global $mainframe;
		ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . JPATH_COMPONENT . DS . 'libraries');
		include_once 'phpFlickr' . DS . 'phpFlickr.php';
	}

	function getFlickrKey()
	{
		$query = 'SELECT flickr_api_key FROM #__imageshow_configuration WHERE configuration_id = '.(int)$this->_configurationID;
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function getFlickrSecretKey()
	{
		$query = 'SELECT flickr_secret_key FROM #__imageshow_configuration WHERE configuration_id = '.(int)$this->_configurationID;
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function getFlickrUsername()
	{
		$query = 'SELECT flickr_username FROM #__imageshow_configuration WHERE configuration_id = '.(int)$this->_configurationID;
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function getNsid()
	{
		$flickrUsername = $this->getFlickrUsername();
		$service 		= $this->getService();
		$nsid 			= $service->people_findByUsername($flickrUsername[0]);
		return $nsid;
	}

	function getValidation($username, $apiKey, $secretKey)
	{
		$this->loadPhpFlickrClasses();
		$service = new phpFlickr($apiKey, $secretKey);
		$nsid	 = $service->people_findByUsername($username);
		return $nsid;
	}

	function getService()
	{
		$this->loadPhpFlickrClasses();
		$flickrKey 			= $this->getFlickrKey();
		$flickrSecretKey 	= $this->getFlickrSecretKey();

		$service = new phpFlickr($flickrKey[0], $flickrSecretKey[0]);
		return $service;
	}

	function getAlbumsList()
	{
		$nsid 		= $this->getNsid();
		$service 	= $this->getService();
		$photoSets 	= $service->photosets_getList($nsid['id']);

		if (count($photoSets['photoset']))
		{
			$xml = "<node label='Not In set' isBranch='true' data='0'></node>\n";
			$xml .= "<node label='Image Set(s)' data=''>\n";

			foreach($photoSets['photoset'] as $album)
			{
				$xml .= "<node label='{$album['title']}' data='{$album['id']}'></node>\n";
			}

			$xml .= "</node>";

			return $xml;
		}

		return false;
	}

	function getArrayAlbums()
	{
		$nsid 		= $this->getNsid();
		$service 	= $this->getService();
		$photoSets 	= $service->photosets_getList($nsid['id']);
		$albumsList = $photoSets['photoset'];

		return $albumsList;
	}

	function getImagesFromFlickrByAlbumID($albumId)
	{
		$nsid 				= $this->getNsid();
		$album				= $this->getArrayAlbums();
		$arrayAlbumID 		= array();
		$arrayPhotoInSet	= array();
		$photoArray 		= array();
		$service 			= $this->getService();
		$albumInfo 			= $service->photosets_getInfo($albumId);
		$albumTitle 		= $albumInfo['title'];
		$numPhotos 			= $albumInfo['photos'];
		$service 			= $this->getService();

		if ($albumId == 0)
		{
			if (count($album))
			{
				foreach ($album as $value){
					$arrayAlbumID[] = $value['id'];
				}
			}

			if (count($arrayAlbumID)){
				$arrayPhotoInSet = $this->getPhotosInAlbum($arrayAlbumID);
			}

			$photos 	= $service->people_getPublicPhotos($nsid['id'], NULL, 'url_sq, url_t, url_s, url_m, url_o',2000);
			$photoArray = $photos["photos"]['photo'];

		}
		else
		{
			$photos 	= $service->photosets_getPhotos($albumId,'url_o,url_l,url_s,url_t,url_m', NULL, 2000);
			$photoArray = $photos["photoset"]['photo'];
		}

		$photosList = array();

		if (count($photoArray))
		{
			if ($albumId == 0)
			{
				foreach ($photoArray as $photo)
				{
					if (in_array($photo['id'], $arrayPhotoInSet) == false)
					{
						$photoObject 				= new stdClass();
						$photoObject->image_title 	= $photo['title'];
						$photoObject->image_extid 	= $photo['id'];
						$photoObject->image_small 	= $photo['url_t'];
						$photoObject->image_medium 	= $photo['url_s'];
						$photoObject->image_big 	= @$photo['url_s'].','.@$photo['url_m'].','.@$photo['url_l'].','.@$photo['url_o'];
						$photoObject->album_extid	= $albumId;
						$photoObject->image_link    = '';
						$photoObject->image_description = '';

						$photosList[] 	= $photoObject;
					}
				}
			}
			else
			{
				foreach ($photoArray as $photo)
				{
					$photoObject 				= new stdClass();
					$photoObject->image_title 	= $photo['title'];
					$photoObject->image_extid 	= $photo['id'];
					$photoObject->image_small 	= $photo['url_t'];
					$photoObject->image_medium 	= $photo['url_s'];
					$photoObject->image_big 	= @$photo['url_s'].','.@$photo['url_m'].','.@$photo['url_l'].','.@$photo['url_o'];
					$photoObject->album_extid	= $albumId;
					$photoObject->image_link    = '';
					$photoObject->image_description = '';

					$photosList[] 	= $photoObject;
				}
			}
			return $photosList;
		}
		return false;
	}

	function getPhotosInAlbum($albumIDs)
	{
		if (count($albumIDs))
		{
			$photosList = array();
			$service 	= $this->getService();

			foreach ($albumIDs as $albumID)
			{
				$photos = $service->photosets_getPhotos($albumID);
				if (count($photos["photoset"]['photo']))
				{
					foreach ($photos["photoset"]['photo'] as $photo)
					{
						$photosList[] = $photo['id'];
					}
				}
			}
			return $photosList;
		}
		return false;
	}

	function getPhotosInSingleAlbum($albumID)
	{
		$photosList = array();
		$service 	= $this->getService();
		$photos 	= $service->photosets_getPhotos($albumID);

		if (count($photos["photoset"]['photo']))
		{
			foreach ($photos["photoset"]['photo'] as $photo)
			{
				$photosList[] = $photo['id'];
			}
			return $photosList;
		}
		return false;
	}

	function getPhotosAlbum($albumIDs)
	{
		if (count($albumIDs))
		{
			$photosList = array();
			$service 	= $this->getService();
			foreach ($albumIDs as $albumID)
			{
				$photos = $service->photosets_getPhotos($albumID['album_extid']);
				if (count($photos["photoset"]['photo']))
				{
					foreach ($photos["photoset"]['photo'] as $photo){
						$photosList[] = $photo['id'];
					}
				}
			}
			return $photosList;
		}
		return false;
	}

	function getInfoPhoto($photoId)
	{
		$service 			= $this->getService();
		$flickrSecretKey 	= $this->getFlickrSecretKey();
		$photoInfo 			= $service->photos_getInfo($photoId, $flickrSecretKey);
		return $photoInfo;
	}

	function insertFlickrData($imgExtID, $imgSmall, $imgMedium, $imgBig, $imgTitle, $imgDescription, $imgLink, $albumID, $showListID, $customData)
	{
		if (count($imgExtID))
		{
			$objJSNImages 	= JSNISFactory::getObj('classes.jsn_is_images');
			$ordering 		= $objJSNImages->getMaxOrderingByShowlistID($showListID);
			$imagesTable 	= JTable::getInstance('images', 'Table');

			if (count($ordering) < 0 or is_null($ordering))
			{
				$ordering = 1;
			}
			else
			{
				$ordering = $ordering[0] + 1;
			}

			for ($i = 0 ; $i < count($imgExtID); $i++)
			{
				if($objJSNImages->checkImageLimition($showListID))
				{
					$result = true;
					break;
				}

				$imagesTable->showlist_id 		= $showListID;
				$imagesTable->image_extid 		= $imgExtID[$i];
				$imagesTable->album_extid 		= $albumID[$imgExtID[$i]];
				$imagesTable->image_small 		= $imgSmall[$imgExtID[$i]];
				$imagesTable->image_medium 		= $imgMedium[$imgExtID[$i]];
				$imagesTable->image_big			= $imgBig[$imgExtID[$i]];
				$imagesTable->image_title   	= $imgTitle[$imgExtID[$i]];
				$imagesTable->image_description = $imgDescription[$imgExtID[$i]];
				$imagesTable->image_link 		= $imgLink[$imgExtID[$i]];
				$imagesTable->ordering			= $ordering;
				$imagesTable->custom_data 		= $customData[$imgExtID[$i]];
				$imagesTable->image_size 		= @$imageSize;

				$imagesTable->trim();
				$result = $imagesTable->store();
				$imagesTable->image_id = null;

				$ordering ++;
			}

			if ($result)
			{
				return true;
			}
			return false;
		}
		return false;
	}

	function deleteFlickrData($imgExtID, $showListID)
	{
		if (is_array($imgExtID) and count($imgExtID))
		{
			for ($i = 0 ; $i < count($imgExtID); $i++)
			{
				$query = 'DELETE FROM #__imageshow_images WHERE image_extid="'.$imgExtID[$i].'" AND showlist_id='.$showListID;

				$this->_db->setQuery($query);
				$result = $this->_db->query();
			}

			if ($result)
			{
				return true;
			}
			return false;
		}
		return false;
	}

	function getPhotoLocalList($arrayImageID, $showListID)
	{
		if (count($arrayImageID))
		{
			$imageTable 	= JTable::getInstance('images','Table');
			$imageRevert  	= array();

			foreach ($arrayImageID as $ID)
			{
				if ($imageTable->load((int)$ID))
				{
					$info	= $this->getInfoPhoto($imageTable->image_extid);
					$imgObj = new stdClass();
					$imgObj->image_id			= $imageTable->image_id;
					$imgObj->image_extid 		= $imageTable->image_extid;
					$imgObj->album_extid 		= $imageTable->album_extid;
					$imgObj->image_title 		= $info['title'];
					$imgObj->image_description 	= $info['description'];
					$imgObj->image_link 		= $info['urls']['url'][0]['_content'];;
					$imgObj->custom_data 		= 0;
					$imageRevert[] = $imgObj;
				}
			}
			return $imageRevert;
		}
		return false;
	}

	function getFlickrImagesSizeByShowlistID($showlistID)
	{
		$query = 'SELECT cf.flickr_image_size
				  FROM #__imageshow_configuration cf
				  INNER JOIN #__imageshow_showlist sl ON sl.configuration_id = cf.configuration_id
				  WHERE sl.showlist_id ='.(int)$showlistID;
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}

	function getBigImageBySizeConfig($configID, $imgBig)
	{
		$query 	= "SELECT flickr_image_size FROM #__imageshow_configuration WHERE configuration_id =".(int)$configID;
		$this->_db->setQuery($query);
		$resutl 	 = $this->_db->loadResult();
		$arrayImg 	 = explode(',', $imgBig);
		$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
		
		if ($resutl)
		{
			return $objJSNUtils->checkValueArray($arrayImg, $resutl);
		}
		
		return $arrayImg[0];
	}
}