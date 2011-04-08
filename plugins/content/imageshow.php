<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_factory.php');
global $mainframe;
$mainframe->registerEvent( 'onPrepareContent', 'pluginImageShow' );

function pluginImageShow( &$row, &$params )
{
	global $mainframe;
	if ($mainframe->isAdmin()) return;
	JHTML::_('behavior.modal', 'a.modal');
	JPlugin::loadLanguage( 'plg_content_imageshow' );
	$dispatcher				=& JDispatcher::getInstance();
	$objUtils				= JSNISFactory::getObj('classes.jsn_is_utils');
	$objectReadxmlDetail 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
	$objJSNShow 			= JSNISFactory::getObj('classes.jsn_is_show');
	$objJSNShowcase			= JSNISFactory::getObj('classes.jsn_is_showcase');
	$objJSNShowlist			= JSNISFactory::getObj('classes.jsn_is_showlist');
	$objJSNImages			= JSNISFactory::getObj('classes.jsn_is_images');
	$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();
	$editionVersion 		= '<!-- '.JText::_('JSN').' '.@$infoXmlDetail['realName'].' '.strtoupper(@$infoXmlDetail['edition']).' '.@$infoXmlDetail['version'].' -->';
	$URLOriginal 			= $objUtils->overrideURL();

	preg_match_all('/\{imageshow (.*)\/\}/U', $row->text, $matches, PREG_SET_ORDER);

	$paramsCom			=& $mainframe->getParams('com_imageshow');
	$parameterConfig 	= $objUtils->getParametersConfig();
	$generalSWFLibrary 	= (is_null($parameterConfig)?'0':$parameterConfig->general_swf_library);
	$language			= '';
	$hashString  		= $infoXmlDetail['hashString'];

	if ($objUtils->checkSupportLang())
	{
		$objLanguage = JFactory::getLanguage();
		$language    = $objLanguage->getTag();
	}

	$display			= false;
	$user 				=& JFactory::getUser();
	$authAvailable 		= $user->get('aid', 0);

	if (count($matches))
	{
		for ($i=0; $i < count($matches); $i++)
		{
			$random		= uniqid('').rand(1, 99);
			$data 		= explode(' ', $matches[$i][1]);
			$width 		='';
			$height 	='';
			$bgcolor 	= '';
			$html 		= '';

			foreach ($data as $values)
			{
				$value = $values;
				if (stristr($values, 'sl'))
				{
					$showListValue 	= explode('=', $values);
					$showList 		= str_replace($values, 'showlist_id='.$showListValue[1], $values);
					$showListID 	= $showListValue[1];
				}
				elseif (stristr($values, 'sc'))
				{
					$showCaseValue 	= explode('=', $values);
					$showCase 		= str_replace($values, 'showcase_id='.$showCaseValue[1], $values);
					$showCaseID 	= $showCaseValue[1];
					$showcaseInfo 	= $objJSNShowcase->getShowCaseByID($showCaseID);

					if (is_null($showcaseInfo))
					{
						$html .= JText::_('Showcase missing');
					}
				}
				elseif (stristr($values, 'w'))
				{
					$widthValue 	= explode('=', $values);
					$width 			= str_replace($values, $widthValue[1], $values);
				}
				elseif (stristr($values, 'h'))
				{
					$heightValue 	= explode('=', $values);
					$height 		= str_replace($values, $heightValue[1], $values);
				}
			}

			$showlistInfo = $objJSNShowlist->getShowListByID($showListID);

			$URL 		  = $URLOriginal.'plugins/jsnimageshow/'.@$showcaseInfo->theme_name.'/assets/swf/';

			if ($width !='' and $height !='')
			{
				$width  = $width;
				$height = $height;
			}
			else
			{
				$width 	= @$showcaseInfo->general_overall_width;
				$height = @$showcaseInfo->general_overall_height;
			}

			if (@$showcaseInfo->background_color =='')
			{
				$bgcolor = '#ffffff';
			}
			else
			{
				$bgcolor = @$showcaseInfo->background_color;
			}

			$articleAlternate 	= $objJSNShow->getArticleAlternate($showListID);
			$moduleAlternate 	= $objJSNShow->getModuleAlternate($showListID);
			$articleAuth 		= $objJSNShow->getArticleAuth($showListID);
			$moduleSEO			= $objJSNShow->getModuleSEO($showListID);
			$articleSEO			= $objJSNShow->getArticleSEO($showListID);
			$imagesData 		= $objJSNImages->getImagesByShowlistID($showListID);

			// showlist which sync images feature is enabled
			$syncData = $objJSNImages->getSyncImagesByShowlistID($showListID);

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
			$html 	.= $editionVersion;
			$html	.='<div class="jsnis-container">';
			$html 	.='<div class="jsnis-gallery">';

			if ($generalSWFLibrary == 1) // enable SWFObject
			{
				$html .= "<script type='text/javascript'>
							swfobject.embedSWF(
								'".$URL."Gallery.swf',
								'jsn-imageshow-".$i."',
								'".$width."',
								'".$height."',
								'9.0.45',
								'".$URL."assets/js/expressInstall.swf',
								{
									baseurl:'".$URL."',
									showcase:'".$URLOriginal."index.php?option=com_imageshow%26view=show%26format=showcase%26".$showCase."',
									showlist:'".$URLOriginal."index.php?option=com_imageshow%26view=show%26format=showlist%26".$showList."',
									language:'".$language."',
									hashstring:'".$hashString."'
								},
								{
									wmode: 'opaque',
									bgcolor: '".$bgcolor."',
									menu: 'false',
									allowFullScreen:'true'
								});
						</script>";
				$html .='<div id="jsn-imageshow-'.$i.'"></div>';
			}
			else // disable SWFObject
			{
				$html .='<object height="'.$height.'" width="'.$width.'" class="jsnis-flash-object" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" id="jsn-imageshow-'.$i.'">
					<param name="bgcolor" value="'.$bgcolor.'"/>
					<param name="menu" value="false"/>
					<param name="wmode" value="opaque"/>
					<param name="allowFullScreen" value="true"/>
					<param name="allowScriptAccess" value="sameDomain" />
					<param name="movie" value="'.$URL.'Gallery.swf"/>
					<param name="flashvars" value="baseurl='.$URL.'&amp;showcase='.$URLOriginal.'index.php?option=com_imageshow%26view=show%26'.$showCase.'%26format=showcase&amp;showlist='.$URLOriginal.'index.php?option=com_imageshow%26view=show%26'.$showList.'%26format=showlist&amp;language='.$language.'&amp;hashstring='.$hashString.'"/>';
				$html .='<embed src="'.$URL.'Gallery.swf" menu="false" bgcolor="'.$bgcolor.'" width="'.$width.'" height="'.$height.'" name="jsn-imageshow-'.$i.'" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" wmode="opaque" flashvars="baseurl='.$URL.'&amp;showcase='.$URLOriginal.'index.php?option=com_imageshow%26view=show%26'.$showCase.'%26format=showcase&amp;showlist='.$URLOriginal.'index.php?option=com_imageshow%26view=show%26'.$showList.'%26format=showlist&amp;language='.$language.'&amp;hashstring='.$hashString.'"/></object>';
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
				$id 		  = 'jsnis-alternative-pimage-'.$random;
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

			$html .='</div>';
			//ALTERNATIVE CONTENT END

			//SEO CONTENT BEGIN
			$html .="<div class=\"jsnis-seocontent\">";

			if ($showlistInfo['seo_status'] == 0)
			{
				if (count( $imagesData ))
				{
					$html .= '<div>';
					$html .= '<p>'.$showlistInfo['showlist_title'].'</p>';
					$html .= '<p>'.$showlistInfo['description'].'</p>';
					$html .= '<ul>';

					for ($z=0, $n=count($imagesData); $z < $n; $z++)
					{
						$rows = $imagesData[$z];
						$html .= '<li>';

						if ($rows->image_title !='')
						{
							$html .= '<p>'.$rows->image_title.'</p>';
						}

						if ($rows->image_description !='')
						{
							$html .= '<p>'.$rows->image_description.'</p>';
						}

						if ($rows->image_link !='')
						{
							$html .= '<p><a href="'.$rows->image_link.'">'.$rows->image_link.'</a></p>';
						}

						$html .= '</li>';
					}
					$html .= '</ul></div>';
				}
			}

			if ($showlistInfo['seo_status'] == 1)
			{
				$html .= "<div>".$articleSEO['introtext'].$articleSEO['fulltext']."</div>";
			}

			if ($showlistInfo['seo_status'] == 2)
			{
				if ($moduleSEO['published'] == 1 && $moduleSEO['module'] != 'mod_imageshow')
				{
					$module = $objJSNShow->getModuleByID($moduleSEO['id']);
					$html .= JModuleHelper::renderModule($module);
				}
			}

			$html .="</div>";
			//SEO CONTENT END
			$html .='</div>';
			if ($display == true)
			{
				$row->text = str_replace("{imageshow ".$matches[$i][1]."/}", $html, $row->text);
			}
			else
			{
				if ($showlistInfo['authorization_status'] == 1)
				{
					$row->text = str_replace("{imageshow ".$matches[$i][1]."/}", '<div>'.$articleAuth['introtext'].$articleAuth['fulltext'].'</div>', $row->text);
				}
				else
				{
					$row->text = str_replace("{imageshow ".$matches[$i][1]."/}", '&nbsp;', $row->text);
				}
			}
		}
	}

	preg_match_all('/\{imageshow (.*)\}(.*)\{\/imageshow\}/U', $row->text, $matchesLink, PREG_SET_ORDER);

	if (count($matchesLink))
	{
		for ($z=0; $z < count($matchesLink); $z++)
		{
			$dataLink 	= explode(' ', $matchesLink[$z][1]);
			$width 		='';
			$height 	='';
			$showCaseID	= 0;
			$showListID = 0;
			foreach ($dataLink as $values)
			{
				$value = $values;
				if (stristr($values, 'sl'))
				{
					$showListValue 	= explode('=', $values);
					$showList 		= str_replace($values, 'showlist_id='.$showListValue[1], $values);
					$showListID 	= $showListValue[1];
				}
				elseif (stristr($values, 'sc'))
				{
					$showCaseValue 	= explode('=', $values);
					$showCase 		= str_replace($values, 'showcase_id='.$showCaseValue[1], $values);
					$showCaseID 	= $showCaseValue[1];
				}
				elseif (stristr($values, 'w'))
				{
					$widthValue 	= explode('=', $values);
					$width 			= str_replace($values, $widthValue[1], $values);
				}
				elseif (stristr($values, 'h'))
				{
					$heightValue 	= explode('=', $values);
					$height 		= str_replace($values, $heightValue[1], $values);
				}
			}

			$showlistInfo 	= $objJSNShowlist->getShowListByID($showListID);
			$showcaseInfo 	= $objJSNShowcase->getShowCaseByID($showCaseID, true,  'loadAssoc');

			if ($width !='' and $height !='')
			{
				$width  = $width;
				$height = $height;
			}
			else
			{
				$width = $showcaseInfo['general_overall_width'];
				$height = $showcaseInfo['general_overall_height'];
			}

			if (strpos($width, '%'))
			{
				$width = '650';
			}

			$width 	= (int) $width;
			$height = (int) $height;
			$link='index.php?option=com_imageshow&amp;tmpl=component&amp;view=show&amp;'.$showList.'&amp;'.$showCase.'&amp;w='.$width.'&amp;h='.$height;
			$html = '<a rel="{handler: \'iframe\', size: {x: '.($width).', y: '.($height).'}}" href="'.$link.'" class="modal">'.$matchesLink[$z][2].'</a>';
			$row->text = str_replace("{imageshow ".$matchesLink[$z][1]."}".$matchesLink[$z][2]."{/imageshow}", $html, $row->text);
		}
	}
	return true;
}
?>