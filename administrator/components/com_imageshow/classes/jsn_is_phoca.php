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

class JSNISPhoca{

	var $_db = null;

	function JSNISPhoca()
	{
		if(empty($this->_db)){
			$this->_db = &JFactory::getDBO();
		}
	}

	function &getInstance()
	{
		static $instancePhoca;

		if ($instancePhoca == null)
		{
			$instancePhoca = new JSNISPhoca();
		}
		return $instancePhoca;
	}

	function getCategoriesTree($syncAlbum = array())
	{
		$query = "SELECT id, parent_id, title FROM #__phocagallery_categories";
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();

		$xml = "<node label='Image Category(ies)' data='' type='root'>\n";
		$xml .= JSNISPhoca::drawXMLCategory(0, $result, $syncAlbum);
		$xml .= "</node>\n";

		return $xml;
	}

	function drawXMLCategory($parentID, $items, $syncAlbum)
	{
		$tree = '';

		foreach ($items as $item)
		{
			if ($parentID == $item->parent_id)
			{
				$syncStatus = (in_array($item->id, $syncAlbum)) ? ' state=\'checked\' ' : ' state=\'unchecked\' ';
				$tree .= "<node label='{$item->title}' data='{$item->id}' {$syncStatus}>\n";
				$tree .= JSNISPhoca::drawXMLCategory($item->id, $items, $syncAlbum);
				$tree .= "</node>\n";
			}
		}

		return $tree;
	}

	function getImagesFromPhocaByCatID($CatID)
	{
		$query = 'SELECT *
				  FROM #__phocagallery
				  WHERE catid = "'.(int) $CatID.'"
				  AND published = 1
				  ORDER BY ordering ASC';

		$this->_db->setQuery($query);
		$result 		= $this->_db->loadAssocList();
		$arrayImages 	= array();

		foreach ($result as $item)
		{
			$imageObj = new stdClass();
			$imageObj->image_title 	= $item['title'];
			$imageObj->image_extid 	= $item['id'];
			$imageObj->album_extid 	= $item['catid'];
			$imageObj->image_small 	= JSNISPhoca::getThumbImage($item['filename'],'s');
			$imageObj->image_medium = JSNISPhoca::getThumbImage($item['filename'],'m');
			$imageObj->image_big 	= 'images/phocagallery/'.$item['filename'];
			$imageObj->image_link 	= JURI::root().'index.php?option=com_phocagallery&view=detail&catid='.$item['catid'].'&id='.$item['id'];
			$imageObj->image_description = $item['description'];

			$arrayImages[] = $imageObj;
		}
		return $arrayImages;
	}

	function getThumbImage($fileName, $type = 'm')
	{
		$fileName 			= str_replace('//', '/', $fileName);
		$fileName			= str_replace(DS, '/', $fileName);
		$folderArray		= explode('/', $fileName);// Explode the filename (folder and file name)
		$countFolderArray	= count($folderArray);// Count this array
		$lastArrayValue 	= $countFolderArray - 1;// The last array value is (Count array - 1)
		$getFileName 		= $folderArray[$lastArrayValue];

		if ($type == 'l')
		{
			$fileNameThumb 	= 'phoca_thumb_'.$type.'_'. $getFileName;
		}
		else
		{
			$fileNameThumb 	= 'phoca_thumb_'.$type.'_'. $getFileName;
		}

		$thumbName	= str_replace ($getFileName, 'thumbs/' . $fileNameThumb, 'images/phocagallery/' . $fileName);
		return $thumbName;
	}

	function getInfoPhoto($imageID)
	{
		$query = 'SELECT id, catid, title, description, filename
				  FROM #__phocagallery
				  WHERE id = "'.$imageID.'"';

		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function getPhotosAlbum()
	{
		$photosList = array();
		$query = 'SELECT id FROM #__phocagallery WHERE published = 1';

		$this->_db->setQuery($query);

		if (count($this->_db->loadAssocList()))
		{
			foreach ($this->_db->loadAssocList() as $photo)
			{
				$photosList[] = $photo['id'];
			}

			return $photosList;
		}

		return $photosList;
	}

	function insertPhocaData($imgExtID, $imgSmall, $imgMedium, $imgBig, $imgTitle, $imageDescription, $imageLink, $albumID, $showListID, $customData)
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
				$imagesTable->showlist_id 		= @$showListID;
				$imagesTable->image_extid 		= @$imgExtID[$i];
				$imagesTable->album_extid 		= @$albumID[$imgExtID[$i]];
				$imagesTable->image_small 		= @$imgSmall[$imgExtID[$i]];
				$imagesTable->image_medium 		= @$imgMedium[$imgExtID[$i]];
				$imagesTable->image_big			= @$imgBig[$imgExtID[$i]];
				$imagesTable->image_title   	= @$imgTitle[$imgExtID[$i]];
				$imagesTable->image_description = @$imageDescription[$imgExtID[$i]];
				$imagesTable->image_link 		= @$imageLink[$imgExtID[$i]];
				$imagesTable->custom_data 		= @$customData[$imgExtID[$i]];
				$imagesTable->ordering			= @$ordering;
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

	function deletePhocaData($imgExtID, $showListID)
	{
		if (is_array($imgExtID) and count($imgExtID))
		{
			for ($i = 0 ; $i < count($imgExtID); $i++)
			{
				$query = 'DELETE FROM #__imageshow_images
						  WHERE image_extid="'.$imgExtID[$i].'"
						  AND showlist_id='.$showListID;

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
		if (count( $arrayImageID ))
		{
			$imageTable 	= JTable::getInstance('images','Table');
			$imageRevert  	= array();

			foreach ($arrayImageID as $ID)
			{
				if ($imageTable->load((int)$ID))
				{
					$info 	= $this->getInfoPhoto($imageTable->image_extid);
					$imgObj = new stdClass();
					$imgObj->image_id			= $imageTable->image_id;
					$imgObj->image_extid 		= $imageTable->image_extid;
					$imgObj->album_extid 		= $imageTable->album_extid;
					$imgObj->image_title 		= $info[2];
					$imgObj->image_description 	= $info[3];
					$imgObj->image_link 		= JURI::root().'index.php?option=com_phocagallery&view=detail&catid='.$info[1].'&id='.$info[0];;
					$imgObj->custom_data 		= 0;
					$imageRevert[] = $imgObj;
				}
			}
			return $imageRevert;
		}
		return false;
	}

	function getOriginalPhotoInfoOfPhocaByImageID($imageID)
	{
		$query = 'SELECT title, description FROM #__phocagallery WHERE id = "'.$imageID.'"';
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}

	function getSyncImages($showlistID, $limitEdition = true)
	{
		$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');

		$query = 'SELECT p.*
				  FROM #__imageshow_images as i
				  INNER JOIN #__phocagallery as p ON p.catid = i.album_extid
				  INNER JOIN #__imageshow_showlist as sl ON sl.showlist_id = i.showlist_id
				  WHERE i.sync = 1
				  AND sl.showlist_source = 4
				  AND sl.published = 1
				  AND i.showlist_id = '.(int)$showlistID.'
				  ORDER BY i.image_id';

		$this->_db->setQuery($query);

		$result  	= $this->_db->loadObjectList();
		$images		= array();
		$hashString = $objJSNUtils->checkHashString();

		if (count($result) > 0)
		{
			foreach ($result as $item)
			{
				$imageObj = new stdClass();
				$imageObj->image_title 	= $item->title;
				$imageObj->image_extid 	= $item->id;
				$imageObj->album_extid 	= $item->catid;
				$imageObj->image_small 	= JSNISPhoca::getThumbImage($item->filename, 's');
				$imageObj->image_medium = JSNISPhoca::getThumbImage($item->filename, 'm');
				$imageObj->image_big 	= 'images/phocagallery/'.$item->filename;
				$imageObj->image_link 	= JURI::root().'index.php?option=com_phocagallery&view=detail&catid='.$item->catid.'&id='.$item->id;
				$imageObj->image_description = $item->description;
				$images[] = $imageObj;

				if (count($images) > 10 && $hashString == false && $limitEdition == true)
				{
					break;
				}
			}
		}
		return $images;
	}
}
