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
@ini_set('max_execution_time', 300);
@ini_set('allow_url_fopen', 1);
class JSNISPicasa{

	var $_db 				= null;
	var $_showlistID 		= null;
	var $_configurationID 	= null;

	function &getInstance()
	{
		static $instancePicasa;

		if ($instancePicasa == null)
		{
			$instancePicasa = new JSNISPicasa();
		}
		return $instancePicasa;
	}

	function JSNISPicasa()
	{
		global $mainframe;

		if(empty($this->_db))
		{
			$this->_db = &JFactory::getDBO();
		}

		$showlistID	= JRequest::getInt('showlist_id');
		$this->setShowlistID($showlistID);
		$this->setConfigurationID();
	}

	function setShowlistID($id)
	{
		$this->_showlistID = $id;
	}

	function setConfigurationID()
	{
		$query = 'SELECT configuration_id
				  FROM #__imageshow_showlist
				  WHERE showlist_id='.$this->_showlistID;
		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();
		$this->_configurationID = $result[0];
	}

	function getPicasaUsername()
	{
		$query = 'SELECT picasa_user_name
				  FROM #__imageshow_configuration
				  WHERE configuration_id = '.(int)$this->_configurationID;
		$this->_db->setQuery($query);
		return $this->_db->loadRow();
	}

	function feedContentFile($url)
	{
		$stringDisableFunction 	= ini_get('disable_functions');
		$check 					= true;
		$result 				= explode(",", $stringDisableFunction);

		if (count($result))
		{
			foreach ($result as $value)
			{
				if ($value == 'fsockopen')
				{
					$check = false;
					break;
				}
			}
		}

		if ($check == true)
		{
			$objJSNHTTPRequest = JSNISFactory::getObj('classes.jsn_is_httprequest', null, $url);
			return $objJSNHTTPRequest->DownloadToString();
		}
		else
		{
			if (ini_get('allow_url_fopen') == 1)
			{
				return file_get_contents($url);
			}
			return false;
		}
	}

	function getValidation($username)
	{
		$rss = 'http://picasaweb.google.com/data/feed/api/user/'.$username.'/contacts?kind=user';

		if ((stristr($this->feedContentFile($rss), 'Unable to find user') !== false) or (stristr($this->feedContentFile($rss), 'Unknown user') !== false) or (stristr($this->feedContentFile($rss), 'Content has been removed for violation') !== false) or (stristr($this->feedContentFile($rss), 'Invalid request') !== false))
		{
			return false;
		}
		return true;
	}

	function getAlbumsList()
	{
		$picasaUsername = $this->getPicasaUsername();
		$rss 	= 'http://picasaweb.google.com/data/feed/api/user/'.$picasaUsername[0].'/?kind=album&access=public&alt=rss';
		$albums = array();
		$file 	= $this->feedContentFile($rss);
		$start 	= @strpos($file, "<item>");
		$end 	= @strrpos($file, "</item>");
		$substr = substr($file, $start, $end-$start+1);
		$items 	= explode("<item>", $substr);

		if ($start != false or $end != false)
		{
			if (is_array($items) and count($items)>0)
			{
				$xml = "<node label='Web Album(s)' data=''>\n";

				foreach ($items as $tmp)
				{
					if (trim($tmp) != "")
					{
						$title = $this->getTagContent($tmp, "title");
						$albumId = $this->getTagContent($tmp, "gphoto:id");
						$xml .= "<node label='{$title}' data='{$albumId}'></node>\n";
					}
				}

				$xml .= "</node>\n";
			}
			return $xml;
		}
		return false;
	}

	function getImagesFromPicasaByAlbumID($albumID)
	{
		$picasaUsername = $this->getPicasaUsername();
		$rss 	= 'http://picasaweb.google.com/data/feed/api/user/'.$picasaUsername[0].'/albumid/'.$albumID.'?kind=photo&alt=rss&access=public&thumbsize=144u';
		$file 	= $this->feedContentFile($rss);
		$photos = array();
		$start 	= @strpos($file, "<item>");
		$end 	= @strrpos($file, "</item>");
		$substr = substr($file, $start, $end-$start+1);
		$items 	= explode("<item>", $substr);

		if (is_array($items) && count($items)>0)
		{
			foreach ($items as $tmp)
			{
				if (trim($tmp) != "")
				{
					$title 				= $this->getTagContent($tmp, "title");
					$photoid 			= $this->getTagContent($tmp, "gphoto:id");
					$mediagroup 		= $this->getTagContent($tmp, "media:group");
					$image				= $this->getTagContent($mediagroup, "media:content");
					$thumbnail 			= $this->getTagContent($mediagroup, "media:thumbnail");

					$photo['image_title'] 		= $title;
					$photo['image_extid'] 		= $photoid;
					$photo['image_small']   	= $thumbnail['url'];
					$photo['image_medium']   	= $thumbnail['url'];
					$photo['image_big']			= $image['url'];
					$photo['album_extid']		= $albumID;
					$photo['image_link']		= '';
					$photo['image_description'] = '';
					array_push($photos, $photo);
				}
			}
		}

		return $photos;
	}

	function getInfoPhoto($albumID, $photoID)
	{
		$picasaUsername = $this->getPicasaUsername();
		$rss 	= 'http://picasaweb.google.com/data/feed/api/user/'.$picasaUsername[0].'/albumid/'.$albumID.'/photoid/'.$photoID.'?alt=rss&thumbsize=288';
		$file 	= $this->feedContentFile($rss);

		$photo 					= array();
		$photo['title'] 		= $this->getTagContent($file, "title");
		$photo['description'] 	= $this->getTagContent($file, "description");
		$photo['url'] 			= $this->getTagContent($file, "link");

		return $photo;
	}

	function getTagContent($src, $tag)
	{
		$start = @strpos ($src, "<".$tag.">");// + strlen($tag)+2;
		if ($start === false)
		{
			$start 		= @strpos ($src, "<".$tag) + strlen($tag)+1;
			$end 		= @strpos ($src, "/>", $start)-1;
			$content 	= substr($src, $start, $end-$start+1);
			$return = array();

			$tmp = explode(' ', $content);

			if (is_array($tmp) and count($tmp)>0)
			{
				foreach ($tmp as $line)
				{
					if (trim($line)!="")
					{
						$a 				= explode("=", $line);
						$return[$a[0]] 	= @str_replace("'", "", trim($a[1]));
					}
				}
			}
		}
		else
		{
			$start	+= strlen($tag)+2;
			$end 	= @strpos ($src, "</".$tag.">")-1;
			$return = substr($src, $start, $end-$start+1);
		}

		if (count($return))
		{
			return $return;
		}

		return false;
	}

	function insertPicasaData($imgExtID, $imgSmall, $imgMedium, $imgBig, $imgTitle, $imgDescription, $imgLink, $albumID, $showListID, $customData)
	{
		if (count($imgExtID))
		{
			$objJSNImages = JSNISFactory::getObj('classes.jsn_is_images');
			$ordering = $objJSNImages->getMaxOrderingByShowlistID($showListID);

			if (count($ordering) < 0 or is_null($ordering))
			{
				$ordering = 1;
			}
			else
			{
				$ordering = $ordering[0] + 1;
			}

			$imagesTable = JTable::getInstance('images', 'Table');

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
				$imagesTable->ordering			= $ordering;
				$imagesTable->image_size 		= @$imageSize;
				$imagesTable->image_description = $imgDescription[$imgExtID[$i]];
				$imagesTable->image_link 		= $imgLink[$imgExtID[$i]];
				$imagesTable->custom_data 		= $customData[$imgExtID[$i]];

				$imagesTable->encodeURL($replaceSpace = false);
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

	function deletePicasaData($imgExtID, $showListID)
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
					$info 	= $this->getInfoPhoto($imageTable->album_extid, $imageTable->image_extid);
					$imgObj = new stdClass();
					$imgObj->image_id			= $imageTable->image_id;
					$imgObj->image_extid 		= $imageTable->image_extid;
					$imgObj->album_extid 		= $imageTable->album_extid;
					$imgObj->image_title 		= (is_array($info['title'])) ? '' : trim($info['title']);
					$imgObj->image_description 	= (is_array($info['description'])) ? '' : trim($info['description']);
					$imgObj->image_link 		= (is_array($info['url'])) ? '' : trim($info['url']);
					$imgObj->custom_data 		= 0;
					$imageRevert[] = $imgObj;
				}
			}
			return $imageRevert;
		}
		return false;
	}
}