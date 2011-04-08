<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<?php
require_once ( "templates/" . $this->template . "/menu.php");
 
if ( $this->countModules('left + user1 + advert1') == 0) $a = '-noleft';	
if ( $this->countModules('right + user2 + advert2') == 0) $b = '-noright';	

?>
<jdoc:include type="head" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/template_css.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/horizontal.css" type="text/css" />
<!--[if IE 6]>
<link href="templates/<?php echo $this->template ?>/css/template_ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript" language="javascript" src="templates/<?php echo $this->template ?>/js/animation.js"></script>
<script type="text/javascript" language="javascript" src="templates/<?php echo $this->template ?>/js/cssmenus.js"></script>
<link rel="shortcut icon" href="templates/<?php echo $this->template ?>/images/favicon.ico"/>


</head>
<body>
<div id="bg">
	<div align="center">
		<div id="header">
			<div id="header_l"><div id="header_r"><div id="header_in">
						<div id="logo_box">
							<div id="logo_in"><div id="logo">
								<a href="<?php echo $mainframe->getCfg('live_site')?>"><img src="templates/<?php echo $this->template ?>/images/logo_transparent.png" alt="logo" border="0"/></a>
							</div></div>
						</div>
						<div id="topmod_box">
							<div id="topmod"><jdoc:include type="modules" name="top" style="xhtml"/></div>
						</div>
			</div></div></div>
			<div id="top_l"><div id="top_r"><div id="top">
				<div id="menu_box"><div id="menu"><?php mosShowListMenu('topmenu'); ?></div></div>
				<div id="search_box"><div id="search"><jdoc:include type="modules" name="user4" style="xhtml"/></div></div>
			</div></div></div>
			<div id="pathway_box">
				<div id="path_text">Znajdujesz sie w:</div>
				<div id="path"><jdoc:include type="modules" name="breadcrumb" style="html"/></div>	
			</div>
		</div><!--header-->		
		<div id="container" class="clearfix">
			<div id="left_col<?php echo $a; ?>">
				<div id="left_mod">
					<jdoc:include type="modules" name="left" style="rounded"/>
					<jdoc:include type="modules" name="user1" style="rounded"/>
					<jdoc:include type="modules" name="advert1" style="rounded"/>		
				</div>
			</div><!--left_col-->
			<div id="right_col<?php echo $a; ?>">
				<div id="content<?php echo $a; ?><?php echo $b; ?>"><div id="content_in<?php echo $a; ?><?php echo $b; ?>">
					<div id="mainbody">
						<jdoc:include type="component" style="html"/>
					</div>
				</div></div>
				<div id="right_box<?php echo $b; ?>">
					<div id="right_mod">
						<jdoc:include type="modules" name="right" style="rounded"/>
						<jdoc:include type="modules" name="user2" style="rounded"/>
						<jdoc:include type="modules" name="advert2" style="rounded"/>
					</div>
				</div>
			</div><!--right_col-->
		</div><!--container-->
		<div id="foot_l"><div id="foot_r"><div id="foot">
			<div id="license_box"><div id="license" align="left"><?php include_once('includes/footer.php'); ?></div></div>
			<div id="copyright_box"><div id="copyright">Design by: <a href="http://www.design-joomla.pl" target="_blank" title="Professional Joomla templates">Joomla and Mambo Templates</a></div></div>
		</div></div></div>
	</div>
</div>
</body>
</html>







