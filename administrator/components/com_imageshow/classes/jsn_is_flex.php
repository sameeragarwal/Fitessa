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
jimport( 'joomla.utilities.utility' );
class JSNISFlex{

	var $_token = '';

	function &getInstance()
	{
		static $instanceFlex;

		if ($instanceFlex == null){
			$instanceFlex = new JSNISFlex();
		}
		return $instanceFlex;
	}

	function JSNISFlex()
	{
		$this->_token = JUtility::getToken();
	}

	function getToken()
	{
		return $this->_token;
	}

	function bindObject($success = true, $msg, $data = '')
	{
		$obj			= new stdClass();
		$obj->isSuccess = $success;
		$obj->msg 		= $msg;

		if ($data != '')
		{
			$obj->data		= $data;
		}

		$objJSNJSON = JSNISFactory::getObj('classes.jsn_is_json');
		return $objJSNJSON->encode($obj);
	}

	function init($showlistID)
	{
		if (!JRequest::checkToken('get'))
		{
			return JSNISFlex::bindObject(false, JText::_('Invalid Token'));
		}

		$configTable 	= JTable::getInstance('configuration','Table');
		$showlistTable 	= JTable::getInstance('showlist','Table');

		if ($showlistID)
		{
			$showlistTable->load((int)$showlistID);
			$configTable->load((int)$showlistTable->configuration_id);

			$objJSNThumb		= JSNISFactory::getObj('classes.jsn_is_imagethumbnail');
			$objJSNImages 		= JSNISFactory::getObj('classes.jsn_is_images');
			$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
			$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');

			//if source is FOLDER , check thumbnail
			if ($showlistTable->showlist_source == 1)
			{
				$images 		= $objJSNImages->getImagesByShowlistID($showlistID);
				$memory 		= (int) ini_get('memory_limit');

				if ($memory == 0)
				{
					$memory = 8;
				}

				foreach ($images as $img)
				{
					$objJSNThumb->checkImageFolderStatus($img);
				}
				$memoryString = $memory.'M';
				@ini_set('memory_limit', $memoryString);
			}
			// update joomga
			if ($showlistTable->showlist_source == 5)
			{
				$objJSNJoomga = JSNISFactory::getObj('classes.jsn_is_joomga');
				$objJSNJoomga->autoUpdateJoomgaImages($showlistID);
			}

			$images 					= $objJSNImages->getImagesByShowlistID($showlistID);
			$albumTree					= $objJSNShowlist->getAbumTreeByShowlistID($showlistID);
			$albumSync					= $objJSNImages->getSyncAlbumsByShowlistID($showlistID);

			if (count($albumSync) > 0 )
			{
				$images = $objJSNImages->getSyncImagesByShowlistID($showlistID, false);
			}

			$obj 						= new stdClass();
			$obj->showlist_title 		= $showlistTable->showlist_title;
			$obj->configuration_title 	= $objJSNShowlist->getConfigTitleByShowlistID($showlistID);
			$obj->images				= $images;
			$obj->album					= $albumTree;
			$obj->showlist_source		= $showlistTable->showlist_source;
			$obj->syncmode				= (count($albumSync) > 0) ? true : false;

			if (!empty($obj->showlist_title))
			{
				return JSNISFlex::bindObject(true, '',$obj);
			}
			else
			{
				return JSNISFlex::bindObject(false, JText::_('Showlist not exists'));
			}
		}
	}

	function removeAllImagesByShowlistID($showlistID)
	{
		if (!JRequest::checkToken('get'))
		{
			return JSNISFlex::bindObject(false, JText::_('Invalid Token'));
		}

		if (!empty($showlistID))
		{
			$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
			$objJSNShowlist->removeAllImageByShowlistID($showlistID);

			$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
			$get				= JRequest::get('get');
			$get['album']		= (isset($get['album'])) ? $get['album'] : '';
			$checkJoomgaPhoca 	= (isset($get['enable_check_joomga_phoca'])) ? $get['enable_check_joomga_phoca'] : true;
			$currentImages		= $objJSNShowlist->refreshFlexAfterDeleteByShowlistID($showlistID, $get['album']);

			$data 				= new stdClass();
			$data->currentImage = $currentImages;

			if ($checkJoomgaPhoca == true)
			{
				$phoca 	= $objJSNUtils->checkComInstalled('com_phocagallery');
				$joomga = $objJSNUtils->checkComInstalled('com_joomgallery');

				$data->phoca  = $phoca;
				$data->joomga = $joomga;
			}

			return JSNISFlex::bindObject(true, '', $data);
		}

	}

	function addImages()
	{
		if (!JRequest::checkToken('post'))
		{
			return JSNISFlex::bindObject(false, JText::_('Invalid Token'));
		}

		global $objectLog;

		$objJSNImages		= JSNISFactory::getObj('classes.jsn_is_images');
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
		$user				= & JFactory::getUser();
		$userID				= $user->get('id');

		$infoInsert 				= new stdClass();
		$infoInsert->imgID			= JRequest::getVar('image_id', array(), 'post', 'array');
		$infoInsert->imgExtID		= JRequest::getVar('image_extid', array(), 'post', 'array');
		$infoInsert->imgSmall		= JRequest::getVar('image_small', array(), 'post', 'array');
		$infoInsert->imgMedium		= JRequest::getVar('image_medium', array(), 'post', 'array');
		$infoInsert->imgBig			= JRequest::getVar('image_big', array(), 'post', 'array');
		$infoInsert->imgTitle		= JRequest::getVar('image_title', array(), 'post', 'array');
		$infoInsert->imgLink		= JRequest::getVar('image_link', array(), 'post', 'array');
		$infoInsert->albumID		= JRequest::getVar('album_extid', array(), 'post', 'array');
		$infoInsert->imgDescription = JRequest::getVar('image_description', array(), 'post', 'array');
		$infoInsert->showlistID 	= JRequest::getInt('showlist_id' );
		$infoInsert->customData    	= JRequest::getVar('custom_data', array(), 'post', 'array');
		$showlistTitle 				= $objJSNShowlist->getTitleShowList($infoInsert->showlistID);
		$imgArrayLocalExtID 		= $objJSNImages->getImageExtByShowlistID($infoInsert->showlistID);
		$ordering 					= JRequest::getVar( 'ordering');

		if (!is_null($imgArrayLocalExtID) && !empty($imgArrayLocalExtID))
		{
			$arrayImagesRemove = array_diff($imgArrayLocalExtID, $infoInsert->imgExtID);

			// remove images that not selected
			if (count($arrayImagesRemove))
			{
				$infoDelete 				= new stdClass();
				$infoDelete->arrayImgExtID 	= array_values($arrayImagesRemove);
				$infoDelete->showlistID 	= $infoInsert->showlistID;
				$deleteImageExist 			= $objJSNShowlist->deleteRemoteImageBySourceTypeFromShowlist($infoDelete);

				if($deleteImageExist == false){
					return JSNISFlex::bindObject(false, JText::_('Can not remove available images from showlist'), '');
				}
			}

			if (!empty($infoInsert->imgExtID[0]))
			{
				$arrayNewImages = array_diff($infoInsert->imgExtID, $imgArrayLocalExtID);
				$arrayKeyImages = array();

				// update images was edited
				foreach ($infoInsert->imgExtID as $key => $imgExtID)
				{
					if(!in_array($imgExtID, $arrayNewImages)){
						$arrayKeyImages[] = $key;
					}
				}
				$objJSNImages->updateImageDetail($arrayKeyImages, $infoInsert);

				// insert new images
				if (count($arrayNewImages))
				{
					$infoInsert->imgExtID 	= array_values($arrayNewImages);
					$inserImageExist 		= $objJSNShowlist->insertRemoteImageBySourceTypeFromShowlist($infoInsert);

					if ($inserImageExist == false)
					{
						return JSNISFlex::bindObject(false, JText::_('Can not insert images after removing available images from showlist'), '');
					}
				}
			}
		}
		else
		{
			if (count($infoInsert->imgExtID) > 0 && !is_null($infoInsert->imgExtID[0]) && !empty($infoInsert->imgExtID[0]))
			{
				$insertImage = $objJSNShowlist->insertRemoteImageBySourceTypeFromShowlist($infoInsert);
				if ($insertImage == false)
				{
					return JSNISFlex::bindObject(false, JText::_('Can not insert images'));
				}
			}
		}

		$objJSNImages->updateOrder($ordering , $infoInsert->showlistID);
		$objJSNShowlist->updateDateModifiedShowlist((int)$infoInsert->showlistID);
		$objectLog->addLog($userID, JRequest::getURI(), $showlistTitle[0],'addimages','any');

		return JSNISFlex::bindObject(true,'');
	}

	function loadAllProfileConfigBySourceType($sourceType)
	{
		if (!empty($sourceType))
		{
			$objJSNSource 	= JSNISFactory::getObj('classes.jsn_is_source');
			$profiles 		= $objJSNSource->getListConfigBySourceType($sourceType);
			return JSNISFlex::bindObject(true,'',$profiles);
		}

		return JSNISFlex::bindObject(false, JText::_('Invalid source type'));
	}

	function createProfile()
	{
		$post 			= JRequest::get('post');
		$configTable 	= JTable::getInstance('configuration', 'Table');
		$showlistTable 	= JTable::getInstance('showlist', 'Table');

		$configTable->published = 1;
		$configTable->bind($post);

		if ($configTable->source_type == 2) // validate flickr
		{
			$objJSNFlickr 	= JSNISFactory::getObj('classes.jsn_is_flickr');
			$verifyFlickr 	= $objJSNFlickr->getValidation($post['flickr_username'], $post['flickr_api_key'], $post['flickr_secret_key']);

			if ($verifyFlickr == false)
			{
				return JSNISFlex::bindObject(false, JText::_('Invalid flickr informations'));
			}
		}

		if($configTable->source_type == 3)// validate picasa
		{
			$objJSNPicasa 	= JSNISFactory::getObj('classes.jsn_is_picasa');
			$verifyPicasa 	= $objJSNPicasa->getValidation($post['picasa_user_name']);

			if ($verifyPicasa == false)
			{
				return JSNISFlex::bindObject(false, JText::_('Invalid picasa username'));
			}
		}

		if (!$configTable->store())
		{
			return JSNISFlex::bindObject(false, JText::_('Can not save config'));
		}

		$showlistTable->load((int)$post['showlist_id']);

		if ($showlistTable->showlist_id)
		{
			$showlistTable->showlist_source = (int)$post['source_type'];
			$showlistTable->configuration_id = (int)$configTable->configuration_id;

			if (!$showlistTable->store())
			{
				return JSNISFlex::bindObject(false, JText::_('Can not save showlist config'));
			}

			$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
			$objJSNShowlist->removeAllImageByShowlistID((int)$showlistTable->showlist_id);

			$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
			$albumTree		= $objJSNShowlist->getAbumTreeByShowlistID($showlistTable->showlist_id);

			return JSNISFlex::bindObject(true, JText::_('Return album tree'),$albumTree);
		}

		return JSNISFlex::bindObject(false, JText::_('Invalid showlistID, can not save config'));
	}

	function saveProfile()
	{
		$get = JRequest::get('get');
		$showlistTable = JTable::getInstance('showlist', 'Table');

		$showlistTable->load((int)$get['showlist_id']);
		$showlistTable->showlist_source = $get['source_type'];

		if (!empty($get['configuration_id']))
		{
			$showlistTable->configuration_id = (int)$get['configuration_id'];
		}

		if (!$showlistTable->store())
		{
			return JSNISFlex::bindObject(false, JText::_('Can not change showlist source'));
		}

		$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
		$objJSNShowlist->removeAllImageByShowlistID((int)$get['showlist_id']);

		return JSNISFlex::bindObject(true, JText::_('Showlist source have changed'));
	}

	function loadRemoteImage()
	{
		$showlistID = JRequest::getVar('showlist_id');
		$album 		= JRequest::getVar('album');

		if (!empty($showlistID) && $album != '')
		{
			$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
			$images 		= $objJSNShowlist->getRemoteImageBySourceTypeFromShowlist($showlistID, $album);

			return JSNISFlex::bindObject(true, '', $images);
		}

		return JSNISFlex::bindObject(false, JText::_('Invalid showlistID or Album'));
	}

	function checkJoomgaPhoca()
	{
		$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
		$phoca 		= $objJSNUtils->checkComInstalled('com_phocagallery');
		$joomga 	= $objJSNUtils->checkComInstalled('com_joomgallery');

		$info 			= new stdClass();
		$info->phoca 	= $phoca;
		$info->joomga 	= $joomga;

		return JSNISFlex::bindObject(true, '',$info);
	}

	function getFlickrPicasaImageInfo()
	{
		if (!JRequest::checkToken('post'))
		{
			return JSNISFlex::bindObject(false, JText::_('Invalid Token'));
		}

		$arrayImgExtID 	= JRequest::getVar('image_extid', array(), 'post', 'array');
		$albumID		= JRequest::getString('album_extid');
		$sourceType 	= JRequest::getInt('showlist_source');
		$arrayImageInfo = array();

		if ($sourceType == 2)
		{
			foreach ($arrayImgExtID as $imgExtID)
			{
				$objJSNFlickr 			= JSNISFactory::getObj('classes.jsn_is_flickr');
				$photoInfoOriginal 	= $objJSNFlickr->getInfoPhoto($imgExtID);

				$imageObj 				= new stdClass();
				$imageObj->album_extid	= (string)$albumID;
				$imageObj->image_extid 	= (string)$imgExtID;
				$imageObj->title 		= ($photoInfoOriginal['title']) ? $photoInfoOriginal['title'] : '';
				$imageObj->description 	= ($photoInfoOriginal['description']) ? $photoInfoOriginal['description'] : '';
				$imageObj->link			= ($photoInfoOriginal['urls']['url'][0]['_content']) ? $photoInfoOriginal['urls']['url'][0]['_content'] : '';
				$arrayImageInfo[] 		= $imageObj;
			}
		}

		if ($sourceType == 3)
		{
			foreach ($arrayImgExtID as $imgExtID)
			{
				$objJSNPicasa 			= JSNISFactory::getObj('classes.jsn_is_picasa');
				$photoInfoOriginal 		= $objJSNPicasa->getInfoPhoto($albumID, $imgExtID);

				$imageObj 				= new stdClass();
				$imageObj->album_extid	= (string)$albumID;
				$imageObj->image_extid 	= (string)$imgExtID;
				$imageObj->title 		= ($photoInfoOriginal['title']) ? ($photoInfoOriginal['title']) : '';
				$imageObj->description 	= ($photoInfoOriginal['description']) ? $photoInfoOriginal['description'] : '';
				$imageObj->link			= ($photoInfoOriginal['url']) ? $photoInfoOriginal['url'] : '';
				$arrayImageInfo[] 		= $imageObj;
			}
		}

		return JSNISFlex::bindObject(true, '', $arrayImageInfo);
	}

	function synchronize()
	{
		$arrayRevert 	= JRequest::getVar('custom_data_id', array(), 'post', 'array');
		$showlistID 	= JRequest::getInt('showlist_id');

		if (count($arrayRevert) >0)
		{
			$objJSNShowlist	= JSNISFactory::getObj('classes.jsn_is_showlist');
			$result 		= $objJSNShowlist->synchronize($arrayRevert, $showlistID);

			return JSNISFlex::bindObject(true,JText::_('Synchronize successfully'), $result);
		}
		return JSNISFlex::bindObject(true,JText::_('Have not images to synchronize'));
	}

	function loadLanguage()
	{
		$objJSNLang = JSNISFactory::getObj('classes.jsn_is_language');
		$result  = $objJSNLang->loadLanguageFlex();
		return JSNISFlex::bindObject(true,'',$result);
	}

	function saveSyncAlbum()
	{
		if (!JRequest::checkToken('post'))
		{
			return JSNISFlex::bindObject(false, JText::_('Invalid Token'));
		}

		$post = JRequest::get('post');

		if (!isset($post['album_extid']))
		{
			return JSNISFlex::bindObject(false, JText::_('SELECT A FOLDER'), false);
		}

		$objJSNImages = JSNISFactory::getObj('classes.jsn_is_images');
		$result = $objJSNImages->saveSyncAlbum($post['showlist_id'], $post['album_extid']);

		if ($result)
		{
			return JSNISFlex::bindObject(true, JText::_('Enable sync album feature'), true);
		}
		return JSNISFlex::bindObject(true, JText::_('Unenable sync album feature'), false);
	}
}