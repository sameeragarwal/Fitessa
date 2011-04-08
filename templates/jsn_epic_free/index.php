<?php
/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2009 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/

	// no direct access
	defined( '_JEXEC' ) or die( 'Restricted index access' );
	define( 'YOURBASEPATH', dirname(__FILE__) );

	// template setup
	require_once(YOURBASEPATH.'/php/lib/jsn_utils.php');
	require_once(YOURBASEPATH.'/php/lib/jsn_mobile.php');
	require_once(YOURBASEPATH.'/php/jsn_setup.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- <?php echo $template_details->name; ?> <?php echo $template_details->version; ?> -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language ?>" dir="<?php echo $template_direction; ?>">
<head>
<jdoc:include type="head" />
<?php require_once(YOURBASEPATH.'/php/jsn_head.php'); ?>
</head>
<body id="jsn-master" class="jsn-textstyle-<?php echo $template_textstyle; ?> jsn-textsize-<?php echo $template_textsize; ?> jsn-color-<?php echo $template_color; ?> jsn-direction-<?php echo $template_direction; ?><?php echo ($pageclass != "")?" ".$pageclass:""; ?>">
<div id="jsn-page">
	<?php if ($this->countModules( 'stickleft' ) > 0) { ?>
		<div id="jsn-pos-stickleft">
			<jdoc:include type="modules" name="stickleft" style="jsnxhtml" />
		</div>
	<?php } ?>
	<?php if ($this->countModules( 'stickright' ) > 0) { ?>
		<div id="jsn-pos-stickright">
			<jdoc:include type="modules" name="stickright" style="jsnxhtml" />
		</div>
	<?php } ?>
	<div id="jsn-header">
		<div id="jsn-pos-logo">
				<?php if ($this->countModules( 'logo' ) > 0) { ?>
					<jdoc:include type="modules" name="logo" style="jsnxhtml" />
				<?php } else { ?>
				<?php if ($logo_link != "") echo '<a href="'.$logo_link.'" title="'.$logo_slogan.'">'; ?>
					<img src="<?php echo $template_path."/images/".($enable_colored_logo?$template_color."/":""); ?>logo.png" alt="<?php echo $logo_slogan; ?>" />
				<?php if ($logo_link != "") echo '</a>'; ?>
			<?php } ?>
		</div>
		<?php if ($this->countModules( 'top' ) > 0) { ?>
			<div id="jsn-pos-top">
				<jdoc:include type="modules" name="top" style="jsnxhtml" />
			</div>
		<?php } ?>
	</div>
	<div id="jsn-body">
		<?php if ($jsnutils->countPositions($this, array('mainmenu', 'toolbar'))) { ?>
			<div id="jsn-menu" class="clearafter">
				<?php if ($this->countModules( 'mainmenu' ) > 0) { ?>
					<div id="jsn-pos-mainmenu">
						<jdoc:include type="modules" name="mainmenu" style="jsnxhtml" />
					</div>
				<?php } ?>
				<?php if ($this->countModules( 'toolbar' ) > 0) { ?>
					<div id="jsn-pos-toolbar">
						<jdoc:include type="modules" name="toolbar" style="jsnxhtml" />
					</div>
				<?php } ?>
			</div>
		<?php } ?>
		<?php if($jsnutils->countPositions($this, array('promo-left', 'promo', 'promo-right', 'content-top'))) { ?>
			<div id="jsn-featured">
				<div id="jsn-featured-top" class="clearafter">
				<?php if ($this->countModules( 'promo-left' ) > 0) { ?>
                    <div id="jsn-pos-promo-left">
						<jdoc:include type="modules" name="promo-left" style="jsntrio" />
                    </div>
                <?php } ?>
                <?php if ($this->countModules( 'promo' ) > 0) { ?>
                    <div id="jsn-pos-promo">
                        <jdoc:include type="modules" name="promo" style="jsntrio" />
                    </div>
                <?php } ?>
                <?php if ($this->countModules( 'promo-right' ) > 0) { ?>
                    <div id="jsn-pos-promo-right">
						<jdoc:include type="modules" name="promo-right" style="jsntrio" />
                    </div>
                <?php } ?>
				</div>
				<?php if ($this->countModules( 'content-top' ) > 0) { ?>
					<div id="jsn-featured-bottom">
						<div id="jsn-pos-content-top" class="jsn-modulescontainer jsn-modulescontainer<?php echo $this->countModules( 'content-top' ); ?> clearafter">
							<jdoc:include type="modules" name="content-top" style="jsntrio" />
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>       
		<div id="jsn-content" class="<?php echo (($has_left)?'jsn-hasleft ':'') ?><?php echo (($has_right)?'jsn-hasright ':'') ?><?php echo (($has_innerleft)?'jsn-hasinnerleft ':'') ?><?php echo (($has_innerright)?'jsn-hasinnerright ':'') ?>">
			<div id="jsn-content_inner"><div id="jsn-content_inner1"><div id="jsn-content_inner2"><div id="jsn-content_inner3"><div id="jsn-content_inner4"><div id="jsn-content_inner5"><div id="jsn-content_inner6"><div id="jsn-content_inner7" class="clearafter">
			<div id="jsn-maincontent" class="clearafter">
				<div id="jsn-centercol">
					<div id="jsn-centercol_inner">
						<?php if ($this->countModules( 'breadcrumbs' ) > 0) { ?>
							<div id="jsn-pos-breadcrumbs">
								<jdoc:include type="modules" name="breadcrumbs" />
							</div>
						<?php } ?>
						<jdoc:include type="message" />
						<?php if ($this->countModules( 'user-top' ) > 0) { ?>
							<div id="jsn-pos-user-top" class="jsn-modulescontainer jsn-modulescontainer<?php echo $this->countModules( 'user-top' ); ?> clearafter">
								<jdoc:include type="modules" name="user-top" style="jsntrio" />
							</div>
						<?php } ?>
						<?php
							$positionCount = $jsnutils->countPositions($this, array('user1', 'user2'));
							if($positionCount){
							$grid_suffix = $positionCount;
						?>
							<div id="jsn-usermodules1" class="jsn-modulescontainer jsn-modulescontainer<?php echo $grid_suffix; ?>">
								<div id="jsn-usermodules1_inner_grid<?php echo $grid_suffix; ?>">
									<?php if ($this->countModules( 'user1' ) > 0) { ?>
										<div id="jsn-pos-user1">
											<jdoc:include type="modules" name="user1" style="jsntrio" />
										</div>
									<?php } ?>
									<?php if ($this->countModules( 'user2' ) > 0) { ?>
										<div id="jsn-pos-user2">
											<jdoc:include type="modules" name="user2" style="jsntrio" />
										</div>
									<?php } ?>
									<div class="clearbreak"></div>
								</div>
							</div>
						<?php } ?>
						<div id="jsn-mainbody-content">
							<?php if ($this->countModules( 'mainbody-top' ) > 0) { ?>
								<div id="jsn-pos-mainbody-top" class="jsn-modulescontainer jsn-modulescontainer<?php echo $this->countModules( 'mainbody-top' ); ?> clearafter">
									<jdoc:include type="modules" name="mainbody-top" style="jsntrio" />
								</div>
							<?php } ?>
							<?php if ($show_frontpage) { ?>
								<div id="jsn-mainbody">
									<jdoc:include type="component" />
								</div>
							<?php } ?>
							<?php if ($this->countModules( 'mainbody-bottom' ) > 0) { ?>
								<div id="jsn-pos-mainbody-bottom" class="jsn-modulescontainer jsn-modulescontainer<?php echo $this->countModules( 'mainbody-bottom' ); ?> clearafter">
									<jdoc:include type="modules" name="mainbody-bottom" style="jsntrio" />
								</div>
							<?php } ?>
						</div>
						<?php
							$positionCount = $jsnutils->countPositions($this, array('user3', 'user4'));
							if($positionCount){
							$grid_suffix = $positionCount;
						?>
							<div id="jsn-usermodules2" class="jsn-modulescontainer jsn-modulescontainer<?php echo $grid_suffix; ?>">
								<div id="jsn-usermodules2_inner_grid<?php echo $grid_suffix; ?>">
									<?php if ($this->countModules( 'user3' ) > 0) { ?>
										<div id="jsn-pos-user3">	
											<jdoc:include type="modules" name="user3" style="jsntrio" />
										</div>
									<?php } ?>
									<?php if ($this->countModules( 'user4' ) > 0) { ?>
										<div id="jsn-pos-user4">	
											<jdoc:include type="modules" name="user4" style="jsntrio" />
										</div>
									<?php } ?>
									<div class="clearbreak"></div>
								</div>
							</div>
						<?php } ?>
						<?php if ($this->countModules( 'user-bottom' ) > 0) { ?>
							<div id="jsn-pos-user-bottom" class="jsn-modulescontainer jsn-modulescontainer<?php echo $this->countModules( 'user-bottom' ); ?> clearafter">
								<jdoc:include type="modules" name="user-bottom" style="jsntrio" />
							</div>
						<?php } ?>
						<?php if ($this->countModules( 'banner' ) > 0) { ?>
							<div id="jsn-pos-banner">
								<jdoc:include type="modules" name="banner" style="jsntrio" />
							</div>
						<?php } ?>
					</div>
				</div>
				<?php if ($this->countModules( 'innerleft' ) > 0) { ?>
					<div id="jsn-pos-innerleft">
						<div id="jsn-pos-innerleft_inner">
							<jdoc:include type="modules" name="innerleft" style="jsntrio" />
						</div>
					</div>
				<?php } ?>
				<?php if ($this->countModules( 'innerright' ) > 0) { ?>
					<div id="jsn-pos-innerright">
						<div id="jsn-pos-innerright_inner">
							<jdoc:include type="modules" name="innerright" style="jsntrio" />
						</div>
					</div>
				<?php } ?>
			</div>
			<?php if($jsnutils->countPositions($this, array('left-top', 'left-2', 'left', 'left-bottom'))) { ?>
				<div id="jsn-leftsidecontent">
					<div id="jsn-leftsidecontent_inner">
						<?php if ($this->countModules( 'left-top' ) > 0) { ?>
                            <div id="jsn-pos-left-top">
                            	<jdoc:include type="modules" name="left-top" style="jsntrio" />
                            </div>
                        <?php } ?>
						<?php
							$positionCount = $jsnutils->countPositions($this, array('left-2', 'left'));
							if($positionCount){
						?>
							<div id="jsn-leftside-middle" class="jsn-positionscontainer jsn-positionscontainer<?php echo $positionCount; ?>">
							<?php if ($this->countModules( 'left-2' ) > 0) { ?>
                                <div id="jsn-pos-left-2">
                                	<jdoc:include type="modules" name="left-2" style="jsntrio" />
                                </div>
                            <?php } ?>
                            <?php if ($this->countModules( 'left' ) > 0) { ?>
                                <div id="jsn-pos-left">
                                	<jdoc:include type="modules" name="left" style="jsntrio" />
                                </div>
                            <?php } ?>
                            <div class="clearbreak"></div>
							</div>
						<?php } ?>
						<?php if ($this->countModules( 'left-bottom' ) > 0) { ?>
                            <div id="jsn-pos-left-bottom">
                            	<jdoc:include type="modules" name="left-bottom" style="jsntrio" />
                            </div>
                        <?php } ?>                        
					</div>
				</div>
			<?php } ?>
			<?php if($jsnutils->countPositions($this, array('right-top', 'right', 'right-2', 'right-bottom'))) { ?>
				<div id="jsn-rightsidecontent">
					<div id="jsn-rightsidecontent_inner">
						<?php if ($this->countModules( 'right-top' ) > 0) { ?>
                            <div id="jsn-pos-right-top">
                            	<jdoc:include type="modules" name="right-top" style="jsntrio" />
                            </div>
                        <?php } ?>
						<?php
							$positionCount = $jsnutils->countPositions($this, array('right', 'right-2'));
							if($positionCount){
						?>
							<div id="jsn-rightside-middle" class="jsn-positionscontainer jsn-positionscontainer<?php echo $positionCount; ?>">
							<?php if ($this->countModules( 'right' ) > 0) { ?>
                                <div id="jsn-pos-right">
                                	<jdoc:include type="modules" name="right" style="jsntrio" />
                                </div>
                            <?php } ?>
                            <?php if ($this->countModules( 'right-2' ) > 0) { ?>
                                <div id="jsn-pos-right-2">
                                	<jdoc:include type="modules" name="right-2" style="jsntrio" />
                                </div>
                            <?php } ?>
                            <div class="clearbreak"></div>
							</div>
						<?php } ?>
						<?php if ($this->countModules( 'right-bottom' ) > 0) { ?>
                            <div id="jsn-pos-right-bottom">
                            	<jdoc:include type="modules" name="right-bottom" style="jsntrio" />
                            </div>
                        <?php } ?>                        
					</div>
				</div>
			<?php } ?>
			</div></div></div></div></div></div></div></div>
		</div>
		<?php if($jsnutils->countPositions($this, array('content-bottom', 'user5', 'user6', 'user7'))) { ?>
			<div id="jsn-content-bottom">
				<?php if ($this->countModules( 'content-bottom' ) > 0) { ?>
                    <div id="jsn-pos-content-bottom" class="jsn-modulescontainer jsn-modulescontainer<?php echo $this->countModules( 'content-bottom' ); ?> clearafter">
                        <jdoc:include type="modules" name="content-bottom" style="jsntrio" />
                    </div>
                <?php } ?>
				<?php
					$positionCount = $jsnutils->countPositions($this, array('user5', 'user6', 'user7'));
					if($positionCount){
					$grid_suffix = $positionCount;
				?>
					<div id="jsn-usermodules3" class="jsn-modulescontainer jsn-modulescontainer<?php echo $grid_suffix; ?>">
						<?php if ($this->countModules( 'user5' ) > 0) { ?>
							<div id="jsn-pos-user5">	
								<jdoc:include type="modules" name="user5" style="jsntrio" />
							</div>
						<?php } ?>
						<?php if ($this->countModules( 'user6' ) > 0) { ?>
							<div id="jsn-pos-user6">	
								<jdoc:include type="modules" name="user6" style="jsntrio" />
							</div>
						<?php } ?>
						<?php if ($this->countModules( 'user7' ) > 0) { ?>
							<div id="jsn-pos-user7">	
								<jdoc:include type="modules" name="user7" style="jsntrio" />
							</div>
						<?php } ?>
						<div class="clearbreak"></div>
					</div>
				<?php } ?>
            </div>
		<?php } ?>
	</div>
	<?php
		$positionCount = $jsnutils->countPositions($this, array('footer', 'bottom'));
		if($positionCount){
	?>
		<div id="jsn-footer" class="jsn-positionscontainer jsn-positionscontainer<?php echo $positionCount; ?> clearafter">
			<?php if ($this->countModules( 'footer' ) > 0) { ?>
				<div id="jsn-pos-footer">
					<jdoc:include type="modules" name="footer" style="jsnxhtml" />
				</div>
			<?php } ?>
			<?php if ($this->countModules( 'bottom' ) > 0) { ?>
				<div id="jsn-pos-bottom">
					<jdoc:include type="modules" name="bottom" style="jsnxhtml" />
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
<div id="jsn-copyright"><a href="http://www.joomlashine.com" target="_blank" title="Free Joomla Templates by JoomlaShine.com">Free Joomla Templates by JoomlaShine.com</a></div>
<jdoc:include type="modules" name="debug" />
</body>
</html>