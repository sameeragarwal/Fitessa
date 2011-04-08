<?php
/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2009 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/

	// no direct access
	defined('_JEXEC') or die('Restricted access');

	// Include MooTools libs
	JHTML::_('behavior.mootools');
	
	// Include JSN Utils
	$jsnutils = &JSNUtils::getInstance();
	
	/****************************************************************/
	/* PUBLIC TEMPLATE PARAMETERS */
	/****************************************************************/

	/* Path to logo image starting from the Joomla! root folder (! without preceding slash !). */
	$enable_colored_logo = ($this->params->get("enableColoredLogo", 0) == 1)?true:false;

	/* URL where logo image should link to (! without preceding slash !).
	 * Leave this box empty if you want your logo to be clickable. */
	$logo_link = $this->params->get("logoLink", "");
	if (strpos($logo_link, "http")=== false && $logo_link != '')
	{ 
	  $logo_link = $this->baseurl."/".$logo_link;
	}

	/* Slogan text to be attached to the logo image ALT text for SEO purpose. */
	$logo_slogan = $this->params->get("logoSlogan", "");

	/* Overall template width. */
	$template_width = $this->params->get("templateWidth", "narrow");

	/* Define custom width for template in narrow mode */
	$narrow_width = intval($this->params->get("narrowWidth", "960"));
	
	/* Define custom width for template in wide mode */
	$wide_width = intval($this->params->get("wideWidth", "1150"));
	
	/* Define custom width for template in float mode */
	$float_width = intval($this->params->get("floatWidth", "90"));
	$float_width = ($float_width > 100)?100:$float_width;

	/* Promo left column width specified in percentage.
	   Only whole number is allowed, for example 25% - correct, 25.5% - incorrect */
	$promo_left_width = intval($this->params->get("promoLeftWidth", "23"));
	
	/* Promo right column width specified in percentage.
	   Only whole number is allowed, for example 25% - correct, 25.5% - incorrect */
	$promo_right_width = intval($this->params->get("promoRightWidth", "23"));
	
	/* Left column width specified in percentage.
	   Only whole number is allowed, for example 25% - correct, 25.5% - incorrect */
	$left_width = intval($this->params->get("leftWidth", "23"));

	/* Right column width specified in percentage.
	   Only whole number is allowed, for example 25% - correct, 25.5% - incorrect */
	$right_width = intval($this->params->get("rightWidth", "23"));

	/* InnerLeft column width specified in percentage.
	   Only whole number is allowed, for example 25% - correct, 25.5% - incorrect */
	$innerleft_width = intval($this->params->get("innerleftWidth", "23"));

	/* InnerRight column width specified in percentage.
	   Only whole number is allowed, for example 25% - correct, 25.5% - incorrect */
	$innerright_width = intval($this->params->get("innerrightWidth", "23"));

	/* Define vertical position of position "stickleft" */
	$lsp_alignment = $this->params->get("lspAlignment", "middle");

	/* Define vertical position of position "stickright" */
	$rsp_alignment = $this->params->get("rspAlignment", "middle");

	/* Definition whether to show mainbody on frontpage page or not */
	$show_frontpage = ($this->params->get("showFrontpage", 1) == 1)?true:false;

	/* Template color */
	// blue | red | green | violet | orange | grey
	$template_color = $this->params->get("templateColor", "blue");

	/* Template text style */
	// 1 - Business / Corporation | 2 - Personal / Blog | 3 - News / Magazines;
	$template_textstyle = $this->params->get("templateTextStyle", "business");

	/* Template text size */
	$template_textsize = $this->params->get("templateTextSize", "medium");
	/****************************************************************/
	/* PRIVATE TEMPLATE PARAMETERS */
	/****************************************************************/

	//Global Variable
	global $jsn_richmenu_separator;

	/*Definition separate menu*/
	$jsn_richmenu_separator = '(=)';

	// Template attributes
	$jsn_template_attrs = array(
		'width' => array ('narrow', 'wide', 'float'),
		'direction' => array ('ltr', 'rtl'),
		'leftwidth' => 'integer',
		'rightwidth' => 'integer',
		'innerleftwidth' => 'integer',
		'innerrightwidth' => 'integer'
	);	

	/* Get browser Info */
	$brower_info = $jsnutils->getBrowserInfo(null);
	$ieoffset = (@$brower_info['browser'] == 'msie' && ((int) @$brower_info['version'] == 6 || (int) @$brower_info['version'] == 7))?0.1:0;

	/* Get template details */
	$template_details = $jsnutils->getTemplateDetails(YOURBASEPATH, $this->template);
	$template_prefix = $template_details->name.'-';
	$template_path = $this->baseurl.'/templates/'.$this->template;
	$template_direction = $this->direction;
	
	$has_right = ($this->countModules('right-top') || $this->countModules('right') || $this->countModules('right-2') || $this->countModules('right-bottom'));
	$has_left = ($this->countModules('left-top') || $this->countModules('left-2') || $this->countModules('left') || $this->countModules('left-bottom'));
	$has_promoleft = $this->countModules('promo-left');
	$has_promoright = $this->countModules('promo-right');
	$has_innerleft = $this->countModules('innerleft');
	$has_innerright = $this->countModules('innerright');

	$pageclass = '';
	$not_homepage = true;
	$menus = &JSite::getMenu();
	$menu = $menus->getActive();
	if (is_object($menu)) {

		// Set page class suffix
		$params = new JParameter( $menu->params );
		$pageclass = $params->get( 'pageclass_sfx', '');
		
		// Set homepage flag
		$not_homepage = ($menu != $menus->getDefault());		
	}
	
	// Template attributes setup
	$tattrs = $jsnutils->getTemplateAttributes($jsn_template_attrs, $template_prefix, $pageclass);
	if ($tattrs['width'] != null) $template_width = $tattrs['width'];
	if ($tattrs['direction'] != null) $template_direction = $tattrs['direction'];
	if ($tattrs['leftwidth'] != null) $left_width = $tattrs['leftwidth'];
	if ($tattrs['rightwidth'] != null) $right_width = $tattrs['rightwidth'];
	if ($tattrs['innerleftwidth'] != null) $innerleft_width = $tattrs['innerleftwidth'];
	if ($tattrs['innerrightwidth'] != null) $innerright_width = $tattrs['innerrightwidth'];
	// Define to show main body on homepage or not
	if($show_frontpage == false) {
		$show_frontpage = $not_homepage;
	}

	/*Check if have user-agent that it is mobile */
	//$jsnmobile =& JSNMobile::getInstance(); 
	//$jsnmobile->runCheckMobile();
?>