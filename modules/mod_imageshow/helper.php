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
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_factory.php');
class modImageShowHelper
{
	function render(&$params)
	{
		global $mainframe;
		$dispatcher				=& JDispatcher::getInstance();
		$objUtils				= JSNISFactory::getObj('classes.jsn_is_utils');
		$URLOriginal 			= $objUtils->overrideURL();
		$objectReadxmlDetail 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();
		$paramsCom				=& $mainframe->getParams('com_imageshow');
		$parameterConfig 		= $objUtils->getParametersConfig();
		$generalSWFLibrary 		= (is_null($parameterConfig)?'0':$parameterConfig->general_swf_library);
		$language				= '';
		$hashString				= $infoXmlDetail['hashString'];
		$random			        = uniqid('').rand(1, 99);

		if ($objUtils->checkSupportLang())
		{
			$objLanguage = JFactory::getLanguage();
			$language    = $objLanguage->getTag();
		}

		$display			= false;
		$user 				=& JFactory::getUser();
		$authAvailable 		= $user->get('aid', 0);
		$showcaseID 		= $params->get('showcase_id');
		$showlistID 		= $params->get('showlist_id');
		$objJSNShow			= JSNISFactory::getObj('classes.jsn_is_show');
		$objJSNShowcase		= JSNISFactory::getObj('classes.jsn_is_showcase');
		$objJSNShowlist		= JSNISFactory::getObj('classes.jsn_is_showlist');
		$randomString 		= $objUtils->randSTR(5);
		$articleAlternate 	= $objJSNShow->getArticleAlternate($showlistID);
		$moduleAlternate 	= $objJSNShow->getModuleAlternate($showlistID);
		$seoModule			= $objJSNShow->getModuleSEO($showlistID);
		$seoArticle			= $objJSNShow->getArticleSEO($showlistID);
		$articleAuth 		= $objJSNShow->getArticleAuth($showlistID);
		$showlistInfo 		= $objJSNShowlist->getShowListByID($showlistID);
		$showcaseInfo 		= $objJSNShowcase->getShowCaseByID($showcaseID);
		$html 				= '';

		if (is_null($showcaseInfo))
		{
			$html .= JText::_('Showcase missing');
		}

		$URL = $URLOriginal.'plugins/jsnimageshow/'.@$showcaseInfo->theme_name.'/assets/swf/';

		if ($params->get('width') !='')
		{
			$width  = $params->get('width');
		}
		else
		{
			$width = @$showcaseInfo->general_overall_width;
		}

		if ($params->get('height') !='')
		{
			$height = $params->get('height');
		}
		else
		{
			$height = @$showcaseInfo->general_overall_height;
		}

		if (@$showcaseInfo->background_color =='')
		{
			$bgcolor = '#ffffff';
		}
		else
		{
			$bgcolor =  @$showcaseInfo->background_color;
		}

		$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
		$objJSNImages 	= JSNISFactory::getObj('classes.jsn_is_images');
		$imagesData 	= $objJSNImages->getImagesByShowlistID($showlistID);

		// showlist which sync images feature is enabled
		$syncData = $objJSNImages->getSyncImagesByShowlistID($showlistID);

		if (!empty($syncData))
		{
			$imagesData = $syncData;
		}
		// end sync images

		if ($showlistInfo['access'] == 0)
		{
			$display = true;
		}
		else if ($authAvailable == 2)
		{
			$display = true;
		}
		else if ($showlistInfo['access'] == $authAvailable)
		{
			$display = true;
		}
		else
		{
			$display = false;
		}

		if ($generalSWFLibrary == '')
		{
			$generalSWFLibrary = 0;
		}

		if ($width =='')
		{
			$width = '100%';
		}

		if ($height == '')
		{
			$height = '100';
		}

		$posPercentageWidth = strpos($width, '%');

		if ($posPercentageWidth)
		{
			$width = substr($width, 0, $posPercentageWidth + 1);
		}
		else
		{
			$width = (int) $width;
		}

		$height = (int) $height;
		$html .='<!-- '.JText::_('JSN').' '.@$infoXmlDetail['realName'].' '.strtoupper(@$infoXmlDetail['edition']).' '.@$infoXmlDetail['version'].' -->';

		$html.='<div class="jsnis-container">';
		$html.='<div class="jsnis-gallery">';

		if ($generalSWFLibrary == 1)
		{
			$html .= "<script type='text/javascript'>
						swfobject.embedSWF('".$URL."Gallery.swf', 'jsn-imageshow-".$randomString."', '".$width."', '".$height."', '9.0.45', '".$URL."assets/js/expressInstall.swf', {baseurl:'".$URL."', showcase:'".$URLOriginal."index.php?option=com_imageshow%26view=show%26format=showcase%26showcase_id=".$showcaseID."',showlist:'".$URLOriginal."index.php?option=com_imageshow%26view=show%26format=showlist%26showlist_id=".$showlistID."', language:'".$language."', hashstring:'".$hashString."'}, {wmode: 'opaque',bgcolor: '".$bgcolor."', menu: 'false', allowFullScreen:'true'});
					</script>";
			$html .='<div id="jsn-imageshow-'.$randomString.'" ></div>';
		}
		else
		{
			$html .='<object height="'.$height.'" width="'.$width.'" class="jsnis-flash-object" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" id="jsn-imageshow-'.$randomString.'">
				<param name="bgcolor" value="'.$bgcolor.'"/>
				<param name="menu" value="false"/>
				<param name="wmode" value="opaque"/>
				<param name="allowFullScreen" value="true"/>
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="movie" value="'.$URL.'Gallery.swf"/>
				<param name="flashvars" value="baseurl='.$URL.'&amp;showcase='.$URLOriginal.'index.php?option=com_imageshow%26view=show%26showcase_id='.$showcaseID.'%26format=showcase&amp;showlist='.$URLOriginal.'index.php?option=com_imageshow%26view=show%26showlist_id='.$showlistID.'%26format=showlist&amp;language='.$language.'&amp;hashstring='.$hashString.'"/>';
				$html .='<embed src="'.$URL.'Gallery.swf" menu="false" bgcolor="'.$bgcolor.'" width="'.$width.'" height="'.$height.'" name="jsn-imageshow-'.$randomString.'" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" wmode="opaque" flashvars="baseurl='.$URL.'&amp;showcase='.$URLOriginal.'index.php?option=com_imageshow%26view=show%26showcase_id='.$showcaseID.'%26format=showcase&amp;showlist='.$URLOriginal.'index.php?option=com_imageshow%26view=show%26showlist_id='.$showlistID.'%26format=showlist&amp;language='.$language.'&amp;hashstring='.$hashString.'" /></object>';
		}
		$html .='</div>';
		// ALTERNATIVE CONTENT BEGIN
		$width = (preg_match('/%/', $width)) ? $width : $width.'px';
		$html .='<div class="jsnis-altcontent" style="width:'.$width.'; height:'.$height.'px;">';

		if ($showlistInfo['alternative_status'] == 0)
		{
			$html .= '<div>
					  	<p>'.JText::_('YOU NEED FLASH PLAYER').'!</p>
						<p>
							<a href="http://www.adobe.com/go/getflashplayer">'.JText::_('GET FLASH PLAYER').'</a>
						</p>
					 </div>';
		}

		if ($showlistInfo['alternative_status'] == 1)
		{
			if ($moduleAlternate['published'] == 1 && $moduleAlternate['module'] != 'mod_imageshow')
			{
				$module = $objJSNShow->getModuleByID($moduleAlternate['id']);
				$html .= JModuleHelper::renderModule($module);
			}
		}

		if ($showlistInfo['alternative_status'] == 2)
		{
			$html .='<div>'.$articleAlternate['introtext'].$articleAlternate['fulltext'].'</div>';
		}

		if ($showlistInfo['alternative_status'] == 3)
		{
			$id 		  = 'jsnis-alternative-mimage-'.$random;
			$dimension    = $objJSNShow->renderAlternativeImage($showlistInfo['alter_image_path']);

			if (count($dimension))
			{
				$html .= '<script type="text/javascript">
							window.addEvent("domready", function(){
								JSNISImageShow.scaleResize('.$dimension['width'].','.$dimension['height'].', "'.$id.'");
							});
							window.addEvent("load", function(){
								JSNISImageShow.scaleResize('.$dimension['width'].','.$dimension['height'].', "'.$id.'");
							});
						</script>'."\n";
				$html .= '<img id="'.$id.'" style="display:none; position: absolute;" src="'.$URLOriginal.$showlistInfo['alter_image_path'].'" />';
			}
		}

		if ($showlistInfo['alternative_status'] == 4)
		{
			if (isset($showcaseInfo->theme_name))
			{
				JPluginHelper::importPlugin('jsnimageshow', @$showcaseInfo->theme_name);
				$results 	= $dispatcher->trigger('onLoadJSGallery', array(&$showcaseInfo, &$showlistInfo, &$imagesData, $width, $height));

				if (count($results))
				{
					$html .= $results[0];
				}
				else
				{
					$html .= JText::sprintf('The %s theme do not supports JS Gallery', @$showcaseInfo->theme_name);
				}
			}
		}
		$html .="</div>";
		//ALTERNATIVE CONTENT END

		// SEO CONTENT BEGIN
		$html .= "<div class=\"jsnis-seocontent\">";

		if ($showlistInfo['seo_status'] == 0)
		{
			if (count( $imagesData ))
			{
				$html .= '<div>';
				$html .= '<p>'.$showlistInfo['showlist_title'].'</p>';
				$html .= '<p>'.$showlistInfo['description'].'</p>';
				$html .= '<ul>';
				for ($i=0, $n=count( $imagesData ); $i < $n; $i++)
				{
					$row = &$imagesData[$i];
					$html .= '<li>';

					if ($row->image_title !='')
					{
						$html .= '<p>'.$row->image_title.'</p>';
					}

					if ($row->image_description !='')
					{
						$html .= '<p>'.$row->image_description.'</p>';
					}

					if ($row->image_link !='')
					{
						$html .= '<p><a href="'.$row->image_link.'">'.$row->image_link.'</a></p>';
					}
					$html .= '</li>';
				}
				$html .= '</ul></div>';
			}
		}

		if ($showlistInfo['seo_status'] == 1)
		{
			$html .= "<div>".$seoArticle['introtext'].$seoArticle['fulltext']."</div>";
		}

		if ($showlistInfo['seo_status'] == 2)
		{
			if ($seoModule['published'] == 1 && $seoModule['module'] != 'mod_imageshow')
			{
				$module = $objJSNShow->getModuleByID($seoModule['id']);
				$html .= JModuleHelper::renderModule($module);
			}
		}

		$html .= "</div>";
		// SEO CONTENT END

		$html .="</div>";

		if ($display == true)
		{
			echo $html;
		}
		else
		{
			if ($showlistInfo['authorization_status'] == 1)
			{
				echo '<div>'.$articleAuth['introtext'].$articleAuth['fulltext'].'</div>';
			}
			else
			{
				echo '&nbsp;';
			}
		}
	}
}