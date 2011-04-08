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

class JSNISJoomga{

	var $_db = null;

	function JSNISJoomga()
	{
		if (empty($this->_db))
		{
			$this->_db = &JFactory::getDBO();
		}
	}

	function &getInstance()
	{
		static $instanceJoomga;

		if ($instanceJoomga == null)
		{
			$instanceJoomga = new JSNISJoomga();
		}
		return $instanceJoomga;
	}

	function getCategoriesTree($syncAlbum = array())
	{
		$query 	= "SELECT cid, parent, name FROM #__joomgallery_catg";

		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();

		$xml = "<node label='Image Category(ies)' data='' type='root'>\n";
		$xml .= JSNISJoomga::drawXMLCate(0, $result, $syncAlbum);
		$xml .= "</node>\n";

		return $xml;
	}

	function drawXMLCate($parentID, $items, $syncAlbum)
	{
		$tree = '';

		foreach ($items as $item)
		{
			if ($parentID == $item->parent)
			{
				$syncStatus = (in_array($item->cid, $syncAlbum)) ? ' state=\'checked\' ' : ' state=\'unchecked\' ';
				$tree .= "<node label='{$item->name}' data='{$item->cid}' {$syncStatus}>\n";
				$tree .= JSNISJoomga::drawXMLCate($item->cid, $items, $syncAlbum);
				$tree .= "</node>\n";
			}
		}

		return $tree;
	}

	function getJoomgaCatPath($catIDs)
	{
		if (is_array($catIDs) and count($catIDs))
		{
			foreach ($catIDs as $catID)
			{
				$album [] = $catID['album_extid'];
			}

			$catpath = array();

			$query = "SELECT cid, catpath
					  FROM #__joomgallery_catg
					  WHERE cid IN ('.implode(',',$album).')";

			$this->_db->setQuery($query);
			$catObjs = $this->_db->loadAssocList();

			foreach ($catObjs as $catObj)
			{
				if (empty($catObj['catpath']))
				{
					$catpath[$catObj['cid']] = '/';
				}
				else
				{
					$catpath[$catObj['cid']] = $catObj['catpath'].'/';
				}
			}
			 return $catpath;
		}
		else
		{
			$catpath = array();

			$query = 'SELECT catpath
					  FROM #__joomgallery_catg
					  WHERE cid = '.(int)$catIDs;

			$this->_db->setQuery($query);
			$catObj = $this->_db->loadObject();

			if (empty($catObj->catpath))
			{
				@$catpath[$catIDs] = '/';
			}
			else
			{
				@$catpath[$catIDs] = $catObj->catpath.'/';
			}

			return @$catpath[$catIDs];
		}

		return false;
	}

	function getImagesFromJoomgaByCatID($catID)
	{
		$query = 'SELECT *
				  FROM #__joomgallery
				  WHERE catid = '.(int) $catID.'
				  AND published = 1
				  ORDER BY ordering ASC';

		$this->_db->setQuery($query);

		$result 	= $this->_db->loadAssocList();
		$catPath 	= $this->getJoomgaCatPath($catID);
		$config 	= $this->getJoomgaConfig();
		$arrayImage = array();

		foreach ($result as $item)
		{
			$imageObj 					= new stdClass();
			$imageObj->image_title 		= $item['imgtitle'];
			$imageObj->image_extid 		= $item['id'];
			$imageObj->album_extid		= $item['catid'];
			$imageObj->image_small 		= $config->jg_paththumbs.$catPath.$item['imgthumbname'];
			$imageObj->image_medium 	= $config->jg_pathimages.$catPath.$item['imgfilename'];
			$imageObj->image_big 		= $config->jg_pathoriginalimages.$catPath.$item['imgfilename'];
			$imageObj->image_link		= JURI::root().'index.php?option=com_joomgallery&func=detail&id='.$item['id'];
			$imageObj->image_description = $item['imgtext'];
			$arrayImage[] = $imageObj;
		}

		return $arrayImage;
	}

	function getInfoPhoto($albumID, $imageID)
	{
		$query = 'SELECT
						id, imgtitle, imgtext,
						imgfilename
				  FROM #__joomgallery
				  WHERE catid = "'.(int) $albumID.'"
				  AND id = "'.$imageID.'"';

		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function insertJoomgaData($imgExtID, $imgSmall, $imgMedium, $imgBig, $imgTitle, $imgDescription, $imgLink, $albumID, $showListID, $customData)
	{
		if (count($imgExtID))
		{
			$objJSNImages    = JSNISFactory::getObj('classes.jsn_is_images');
			$ordering 	 	 = $objJSNImages->getMaxOrderingByShowlistID($showListID);
			$imagesTable     =& JTable::getInstance('images', 'Table');

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

	function getPhotosAlbum($albumIDs)
	{
		if (count($albumIDs))
		{
			foreach ($albumIDs as $albumID)
			{
				$album [] = $albumID['album_extid'];
			}

			$photosList = array();
			$query 		= 'SELECT id
						   FROM #__joomgallery
						   WHERE catid IN ('.implode(',',$album).')
						   AND published = 1';

			$this->_db->setQuery($query);

			if (count($this->_db->loadAssocList()))
			{
				foreach ($this->_db->loadAssocList() as $photo)
				{
					$photosList[] = $photo['id'];;
				}
				return $photosList;
			}
			return false;
		}
		return false;
	}

	function deleteJoomgaData($imgExtID, $showListID)
	{
		if (is_array($imgExtID) and count($imgExtID))
		{
			for ($i = 0 ; $i < count($imgExtID); $i++)
			{
				$query = 'DELETE FROM #__imageshow_images
						  WHERE image_extid = "'.$imgExtID[$i].'"
						  AND showlist_id = '.$showListID;

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

	function getJoomgaConfig()
	{
		$query = 'SELECT * FROM #__joomgallery_config';
		$this->_db->setQuery($query);
		$config = $this->_db->loadObject();
		return $config;
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
					$info 	= $this->getInfoPhoto($imageTable->album_extid, $imageTable->image_extid);
					$imgObj = new stdClass();
					$imgObj->image_id			= $imageTable->image_id;
					$imgObj->image_extid 		= $imageTable->image_extid;
					$imgObj->album_extid 		= $imageTable->album_extid;
					$imgObj->image_title 		= $info[1];
					$imgObj->image_description 	= $info[2];
					$imgObj->image_link 		= JURI::root().'index.php?option=com_joomgallery&func=detail&id='.$info[0];
					$imgObj->custom_data 		= 0;
					$imageRevert[] = $imgObj;
				}
			}
			return $imageRevert;
		}
		return false;
	}

	function _getListPhotoID($showListID)
	{
		$arrayID 	= array();
		$query		= 'SELECT image_extid
					   FROM #__imageshow_images
					   WHERE sync = 0 AND showlist_id='.(int)$showListID;

		$this->_db->setQuery($query);

		$items = $this->_db->loadAssocList();

		if (count($items))
		{
			foreach ($items as $value)
			{
				$arrayID [] = $value['image_extid'];
			}
		}

		return $arrayID;

	}

	function autoUpdateJoomgaImages($showListID)
	{
		$arrayImageIDLocal 	= $this->_getListPhotoID($showListID);
		$uri	        	=& JURI::getInstance();
		$base['prefix'] 	= $uri->toString( array('scheme', 'host', 'port'));
		$base['path']   	=  rtrim(dirname(str_replace(array('"', '<', '>', "'",'administrator'), '', $_SERVER["PHP_SELF"])), '/\\');
		$realURL 			=  $base['prefix'].$base['path'].'/';
		$joomgaConfig 		= $this->getJoomgaConfig();

		if (count($arrayImageIDLocal))
		{
			$imageIDLocal = implode(',', $arrayImageIDLocal);

			$query 		  = 'SELECT
								img.id AS imgid, img.catid AS catid,
								CONCAT(cat.catpath,"/", img.imgfilename) AS path
							 FROM #__joomgallery img
							 INNER JOIN #__joomgallery_catg cat ON img.catid=cat.cid
							 WHERE img.published = 1 AND img.id IN ('.$imageIDLocal.')';

			$this->_db->setQuery($query);
			$result = $this->_db->loadAssocList();

			if (count($result))
			{
				foreach ($result as $value)
				{
					$queryUpdate = 'UPDATE
										#__imageshow_images
									SET
										album_extid = "'.$value["catid"].'",
										image_small = "'.$joomgaConfig->jg_paththumbs.$value['path'].'",
										image_medium = "'.$joomgaConfig->jg_pathimages.$value['path'].'",
										image_big = "'.$joomgaConfig->jg_pathoriginalimages.$value['path'].'"
									WHERE image_extid = '.$value["imgid"].'
									AND showlist_id='.(int)$showListID;

					$this->_db->setQuery($queryUpdate);
					$this->_db->query();

					$imageLink= $realURL.'index.php?option=com_joomgallery&func=detail&id='.$value["imgid"];

					$queryUpdateLink = 'UPDATE #__imageshow_images
										SET image_link = "'.$imageLink.'"
										WHERE custom_data = 0
										AND image_extid ='.$value["imgid"].'
										AND showlist_id='.(int)$showListID;

					$this->_db->setQuery($queryUpdateLink);
					$this->_db->query();
				}
			}
		}
		return true;
	}

	function getOriginalPhotoInfoOfJoomgaByImageID($imageID)
	{
		$query = 'SELECT imgtitle, imgtext FROM #__joomgallery WHERE id = "'.$imageID.'"';
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}

	function getSyncImages($showlistID, $limitEdition = true)
	{
		$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');

		$query = 'SELECT j.*
				  FROM #__imageshow_images as i
				  INNER JOIN #__joomgallery as j ON j.catid = i.album_extid
				  INNER JOIN #__imageshow_showlist as sl ON sl.showlist_id = i.showlist_id
				  WHERE i.sync = 1
				  AND sl.showlist_source = 5
				  AND sl.published = 1
				  AND i.showlist_id = '.(int)$showlistID .'
				  GROUP BY j.id
				  ORDER BY i.image_id';

		$this->_db->setQuery($query);

		$result  	= $this->_db->loadObjectList();

		$config 	= $this->getJoomgaConfig();
		$images		= array();
		$catID 		= null;
		$hashString = $objJSNUtils->checkHashString();

		if (count($result) > 0)
		{
			foreach ($result as $item)
			{
				if ($catID != $item->catid)
				{
					$catPath = $this->getJoomgaCatPath($item->catid);
				}
				$catID = $item->catid;

				$imageObj 					= new stdClass();
				$imageObj->image_title 		= $item->imgtitle;
				$imageObj->image_extid 		= $item->id;
				$imageObj->album_extid		= $item->catid;
				$imageObj->image_small 		= $config->jg_paththumbs.$catPath.$item->imgthumbname;
				$imageObj->image_medium 	= $config->jg_pathimages.$catPath.$item->imgfilename;
				$imageObj->image_big 		= $config->jg_pathoriginalimages.$catPath.$item->imgfilename;
				$imageObj->image_link		= JURI::root().'index.php?option=com_joomgallery&func=detail&id='.$item->id;
				$imageObj->image_description = $item->imgtext;

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