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
jimport( 'joomla.application.component.view');
class ImageShowViewShow extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;
		JHTML::_('behavior.mootools');
		$objectReadxmlDetail 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();
		$showCaseID 			= JRequest::getInt('showcase_id', 0);
		$pageclassSFX 			= '';
		$titleWillShow 			= '';

		if ($showCaseID == 0)
		{
			$menu 			=& JSite::getMenu();
			$item 			= $menu->getActive();
			$params 		=& $menu->getParams($item->id);
			$showcase_id 	= $params->get('showcase_id', 0);
			$menu_params 	= new JParameter($item->params );
			$pageclassSFX 	= $menu_params->get('pageclass_sfx');
			$showPageTitle 	= $menu_params->get('show_page_title');
			$pageTitle 		= $menu_params->get('page_title');

			if (!empty($showPageTitle))
			{
				if (!empty($pageTitle))
				{
					$titleWillShow = $pageTitle;
				}
				else if (!empty($item->name))
				{
					$titleWillShow = $item->name;
				}
			}

		}
		else
		{
			$showcase_id = $showCaseID;
		}

		$showListID 		= JRequest::getInt('showlist_id', 0);

		if ($showListID == 0)
		{
			$showlist_id 		= $params->get('showlist_id', 0);
		}
		else
		{
			$showlist_id = $showListID;
		}

		$model = $this->getModel();
		$objJSNShow				= JSNISFactory::getObj('classes.jsn_is_show');
		$objUtils				= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNShowlist         = JSNISFactory::getObj('classes.jsn_is_showlist');
		$objJSNShowcase         = JSNISFactory::getObj('classes.jsn_is_showcase');
		$objJSNImages			= JSNISFactory::getObj('classes.jsn_is_images');

		$paramsCom				=& $mainframe->getParams('com_imageshow');
		$parameterConfig 		= $objUtils->getParametersConfig();
		$generalSWFLibrary 		= (is_null($parameterConfig)?'0':$parameterConfig->general_swf_library);

		$randomNumber 			= $objUtils->randSTR(5);
		$showlistInfo 			= $objJSNShowlist->getShowListByID($showlist_id);
		$articleAlternate 		= $objJSNShow->getArticleAlternate($showlist_id);
		$articleAuth 			= $objJSNShow->getArticleAuth($showlist_id);
		$moduleAlternate		= $objJSNShow->getModuleAlternate($showlist_id);
		$seoModule				= $objJSNShow->getModuleSEO($showlist_id);
		$seoArticle				= $objJSNShow->getArticleSEO($showlist_id);
		$row 					= $objJSNShowcase->getShowCaseByID($showcase_id);
		$imagesData 			= $objJSNImages->getImagesByShowlistID($showlist_id);

		// showlist which sync images feature is enabled
		$syncData = $objJSNImages->getSyncImagesByShowlistID($showlist_id);

		if (!empty($syncData))
		{
			$imagesData = $syncData;
		}
		// end sync images

		$this->assignRef('titleWillShow', $titleWillShow);
		$this->assignRef('showcaseInfo', $row);
		$this->assignRef('randomNumber', $randomNumber);
		$this->assignRef('imagesData', $imagesData);
		$this->assignRef('showlistInfo', $showlistInfo);
		$this->assignRef('articleAlternate', $articleAlternate);
		$this->assignRef('moduleAlternate', $moduleAlternate);
		$this->assignRef('articleAuth', $articleAuth);
		$this->assignRef('seoModule', $seoModule);
		$this->assignRef('seoArticle', $seoArticle);
		$this->assignRef('generalSWFLibrary', $generalSWFLibrary);
		$this->assignRef('pageclassSFX', $pageclassSFX);
		$this->assignRef('infoXmlDetail', $infoXmlDetail);
		$this->assignRef('model', $model);
		$this->assignRef('objUtils', $objUtils);
		parent::display($tpl);
	}
}