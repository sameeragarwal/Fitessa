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

class JSNISGenericImageLib
{
	function &getInstance()
	{
		static $instanceGenericImageLib;
		if ($instanceGenericImageLib == null)
		{
			$instanceGenericImageLib = new JSNISGenericImageLib();
		}
		return $instanceGenericImageLib;
	}

    function resizeImage($fileIn, $fileOut = null, $width = null, $height = null, $crop = null)
    {
        return false;
    }

    function detect()
    {
        return false;
    }
}

class JSNISGD2 extends JSNISGenericImageLib
{
	function detect()
	{
        $GDfuncList = get_extension_funcs('gd');
        if ( $GDfuncList )
        {
            if (in_array('imagegd2', $GDfuncList) )
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else return false;
	}

	function resizeImage($fileIn, $fileOut = null, $width = null, $height = null, $crop = null)
	{
		$jfile_thumbs		= 1;
		$jpeg_quality		= (int) 85;

		if ($fileIn !== '' && JFile::exists($fileIn))
		{
	        if(!$this->setMemoryForImage($fileIn))
	        {
				return false;
			}
			list($w, $h, $type) = getimagesize($fileIn);

	        if ($w > 0 && $h > 0)
			{
		        if ($width == null || $width == 0)
		        {
		            $width = $w;
		        }
				else if ($height == null || $height == 0)
				{
		            $height = $width;
		        }
				if ($height == null || $height == 0)
				{
		            $height = $h;
		        }
		        if (!$crop)
		        {
		            $scale = (($width / $w) < ($height / $h)) ? ($width / $w) : ($height / $h);
		            $src = array(0,0, $w, $h);
		            $dst = array(0,0, floor($w*$scale), floor($h*$scale));
		        }
		        else
		        {
		            $scale = (($width / $w) > ($height / $h)) ? ($width / $w) : ($height / $h);
		            $newW = $width/$scale;
		            $newH = $height/$scale;

		            if (($w - $newW) > ($h - $newH))
		            {
		                $src = array(floor(($w - $newW)/2), 0, floor($newW), $h);
		            }
		            else
		            {
		                $src = array(0, floor(($h - $newH)/2), $w, floor($newH));
		            }
		            $dst = array(0,0, floor($width), floor($height));
		        }

			}
	        switch ($type)
	        {
	            case IMAGETYPE_JPEG:
					if (!function_exists('imagecreatefromjpeg'))
					{
						return false;
					}
					$image1 = imagecreatefromjpeg($fileIn);
					break;
	            case IMAGETYPE_PNG :
					if (!function_exists('imagecreatefrompng'))
					{
						return false;
					}
					$image1 = imagecreatefrompng($fileIn);
					break;
	            case IMAGETYPE_GIF :
					if (!function_exists('imagecreatefromgif'))
					{
						return false;
					}
					$image1 = imagecreatefromgif($fileIn);
					break;
	            case IMAGETYPE_WBMP:
					if (!function_exists('imagecreatefromwbmp'))
					{
						return false;
					}
					$image1 = imagecreatefromwbmp($fileIn);
					break;
	            default:
					return false;
					break;
	        }
			if ($image1)
			{
				$image2 = @imagecreatetruecolor($dst[2], $dst[3]);
				if (!$image2)
				{
					return false;
				}

				switch($type)
				{
					case IMAGETYPE_PNG:
						@imagealphablending($image2, false);
						@imagesavealpha($image2, true);
					break;
				}
				imagecopyresampled($image2, $image1, $dst[0],$dst[1], $src[0],$src[1], $dst[2],$dst[3], $src[2],$src[3]);
		        $typeOut = ($type == IMAGETYPE_WBMP) ? IMAGETYPE_PNG : $type;
				switch($typeOut)
				{
		            case IMAGETYPE_JPEG:
						if (!function_exists('imagejpeg'))
						{
							return false;
						}

						if ($jfile_thumbs == 1)
						{
							ob_start();
							if (!@imagejpeg($image2, NULL, $jpeg_quality))
							{
								ob_end_clean();
								return false;
							}
							$imgJPEGToWrite = ob_get_contents();
							ob_end_clean();
							if(!JFile::write( $fileOut, $imgJPEGToWrite))
							{
								return false;
							}
						}
						else
						{
							if (!@imagejpeg($image2, $fileOut, $jpeg_quality))
							{
								return false;
							}
						}
					break;
					case IMAGETYPE_PNG :
						if (!function_exists('imagepng'))
						{
							return false;
						}
						if ($jfile_thumbs == 1)
						{
							ob_start();
							if (!@imagepng($image2, NULL))
							{
								ob_end_clean();
								return false;
							}
							$imgPNGToWrite = ob_get_contents();
							ob_end_clean();
							if(!JFile::write( $fileOut, $imgPNGToWrite))
							{
								return false;
							}
						}
						else
						{
							if (!@imagepng($image2, $fileOut))
							{
								return false;
							}
						}
					break;
					case IMAGETYPE_GIF :
						if (!function_exists('imagegif'))
						{
							return false;
						}
						if ($jfile_thumbs == 1)
						{
							ob_start();
							if (!@imagegif($image2, NULL))
							{
								ob_end_clean();
								return false;
							}
							$imgGIFToWrite = ob_get_contents();
							ob_end_clean();
							if(!JFile::write( $fileOut, $imgGIFToWrite))
							{
								return false;
							}
						}
						else
						{
							if (!@imagegif($image2, $fileOut))
							{
								return false;
							}
						}
					break;
					case IMAGETYPE_WBMP :
						if (!function_exists('imagewbmp'))
						{
							return false;
						}
						if ($jfile_thumbs == 1)
						{
							ob_start();
							if (!@imagewbmp($image2, NULL))
							{
								ob_end_clean();
								return false;
							}
							$imgWBMPToWrite = ob_get_contents();
							ob_end_clean();
							if(!JFile::write( $fileOut, $imgWBMPToWrite))
							{
								return false;
							}
						}
						else
						{
							if (!@imagewbmp($image2, $fileOut))
							{
								return false;
							}
						}
					break;
					default:
						return false;
					break;
				}
				imagedestroy($image1);
	            imagedestroy($image2);
				if (isset($waterImage1))
				{
					imagedestroy($waterImage1);
				}
				 return true;
	        }
	        else
	        {
				return false;
			}
	    }
		return false;
	}

	function setMemoryForImage($filename)
	{
		$imageInfo 		= getimagesize($filename);
	    $MB 			= Pow(1024, 2);
	    $K64 			= Pow(2, 16);
	    $TWEAKFACTOR 	= 1.5;
	    if (!isset($imageInfo['channels']))
	    {
	    	$imageInfo['channels'] = 3;
	    }
	    $memoryNeeded 	= round(($imageInfo[0]*$imageInfo[1]*$imageInfo['bits']*$imageInfo['channels']/8+$K64)*$TWEAKFACTOR);
	    $memoryLimit 	= 8 * $MB;
	    $memoryLimitMB 	= (integer) ini_get('memory_limit');
	    if (function_exists('memory_get_usage') && memory_get_usage() + $memoryNeeded > $memoryLimit)
	    {

	    	$newLimit 	= $memoryLimitMB + ceil((memory_get_usage() + $memoryNeeded - $memoryLimit)/$MB);
	    	$result 	= ini_set('memory_limit', $newLimit.'M');
	    	if (!$result)
	    	{
	    		return false;
	    	}

	    }
	    return true;
	}
}
?>