<?php
/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2009 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/
	// CSS inclusion
	$this->addStylesheet($this->baseurl."/templates/system/css/system.css");
	$this->addStylesheet($this->baseurl."/templates/system/css/general.css");
	$this->addStylesheet($template_path."/css/template.css");
	
	// Load specific CSS file for template color
	$this->addStylesheet($template_path."/css/template_".$template_color.".css");
	if($template_direction == "rtl") { $this->addStylesheet($template_path."/css/template_rtl.css"); }
?>

<!--[if IE 6]>
<link href="<?php echo $template_path; ?>/css/jsn_fixie6.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $template_path; ?>/js/jsn_fixpng.js"></script>
<![endif]-->

<!--[if IE 7]>
<link href="<?php echo $template_path; ?>/css/jsn_fixie7.css" rel="stylesheet" type="text/css" />
<![endif]-->

<?php
	// Inline CSS styles for template layout
	echo '<style type="text/css">';

	// Setup template width parameter
	$twidth = 0;
	switch ($template_width) {
		case 'narrow':
			$twidth = $narrow_width;
			break;
		
		case 'wide':
			$twidth = $wide_width;
			break;

		case 'float':
			$twidth = $float_width;
			break;
	}
	
	if ($twidth > 100) {
		echo '
		#jsn-page {
			width: '.$twidth.'px;
		}
		';
	} else {
		echo '
		#jsn-page {
			width: '.$twidth.'%;
		}
		';
	}

	// Setup width of promo area
	$tw = 100;
	if ($has_promoleft) {
		$tw -= $promo_left_width;
		echo '
		#jsn-pos-promo {
			float: right;
		}
		';
	}
	if ($has_promoright) {
		$tw -= $promo_right_width;
		echo '
		#jsn-pos-promo {
			float: left;
		}
		';
	}
	echo '
	#jsn-pos-promo-left {
		float: left;
		width: '.$promo_left_width.'%;
	}
	#jsn-pos-promo {
		width: '.($tw-$ieoffset).'%;
	}
	#jsn-pos-promo-right {
		float: right;
		width: '.$promo_right_width.'%;
	}
	';

	// Setup width of content area
	$tw = 100;
	if ($has_left) {
		$tw -= $left_width;
		echo '
	#jsn-content_inner {
		right: '.(100 - $left_width).'%;
	}
	#jsn-content_inner1 {
		left: '.(100 - $left_width).'%;
	}
		';
	}
	if ($has_right) {
		$tw -= $right_width;
		echo '
	#jsn-content_inner2 {
		left: '.(100 - $right_width).'%;
	}
	#jsn-content_inner3 {
		right: '.(100 - $right_width).'%;
	}
		';
	}

	echo '
	#jsn-leftsidecontent {
		float: left;
		width: '.$left_width.'%;
		left: -'.($tw-$ieoffset).'%;
	}
	#jsn-maincontent {
		float: left;
		width: '.($tw-$ieoffset).'%;
		left: '.(($has_left)?$left_width.'%':0).';
	}
	#jsn-rightsidecontent {
		float: right;
		width: '.$right_width.'%;
	}
	';
	
	$tw = 100;
	if ($has_innerleft) {
		$tw -= $innerleft_width;
	}
	if ($has_innerright) {
		$tw -= $innerright_width;
	}

	echo '
	#jsn-pos-innerleft {
		float: left;
		width: '.$innerleft_width.'%;
		left: -'.($tw-$ieoffset).'%;
	}
	#jsn-centercol {
		float: left;
		width: '.($tw-$ieoffset).'%;
		left: '.(($has_innerleft)?$innerleft_width.'%':0).';
	}
	#jsn-pos-innerright {
		float: right;
		width: '.$innerright_width.'%;
	}
	';
	
	echo '</style>';
?>

<!-- JS Includes -->
<?php
	echo '<script type="text/javascript"><!--'."\n";
	echo 'var templatePath = "'.$template_path.'";'."\n";
	echo 'var enableRTL = '.(($template_direction == "rtl")?'true':'false').';'."\n";
	echo 'var rspAlignment = "'.$rsp_alignment.'";'."\n";
	echo 'var lspAlignment = "'.$lsp_alignment.'";'."\n";
	echo '--></script>'."\n";
	echo '<script type="text/javascript" src="'.$template_path.'/js/jsn_utils.js"></script>'."\n";
	echo '<script type="text/javascript" src="'.$template_path.'/js/jsn_template.js"></script>';
?>