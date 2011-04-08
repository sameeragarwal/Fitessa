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
jimport('joomla.html.pane');
$myTabs 		= & JPane::getInstance('tabs',array('startOffset'=>0));
$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
$url 			= $objJSNUtils->overrideURL();
?>
<script language="javascript" type="text/javascript">
	window.addEvent('domready', function()	{
		JSNISClassicTheme.ShowcaseChangeBg();
		JSNISClassicTheme.visualFlash();
		
		var accImage 	= new JSNISAccordions('jsn-image-panel', {multiple: true, activeClass: 'down', showFirstElement: true, durationEffect: 300});
		var accThumb 	= new JSNISAccordions('jsn-thumb-panel', {multiple: true, activeClass: 'down', showFirstElement: true, durationEffect: 300});
		var accInfo 	= new JSNISAccordions('jsn-info-panel', {multiple: true, activeClass: 'down', showFirstElement: true, durationEffect: 300});
		var accToolbar 	= new JSNISAccordions('jsn-toolbar-panel', {multiple: true, activeClass: 'down', showFirstElement: true, durationEffect: 300});
		var accSlide 	= new JSNISAccordions('jsn-slideshow-panel', {multiple: true, activeClass: 'down', showFirstElement: true, durationEffect: 300});
	});
</script>

<!--  important -->

<input type="hidden" name="theme_name" value="<?php echo strtolower($this->_showcaseThemeName); ?>"/>
<input type="hidden" name="theme_id" value="<?php echo (int) $items->theme_id; ?>" />
<!--  important -->

<div class="jsnis-dgrey-heading jsnis-dgrey-heading-style">
	<h3 class="jsnis-element-heading"><?php echo JText::_('TITLE SHOWCASE THEME SETTINGS'); ?></h3>
</div>
<table width="100%" class="jsnis-showcase-theme-settings" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign="top"><div class="jsnis-showcase-theme-detail"> <?php echo $myTabs->startPane('jsn-showcase-tabs'); ?>
				<?php
					echo $myTabs->endPanel();
					echo $myTabs->startPanel(JText::_('IMAGE PANEL'),'image-panel');
				?>
				<div id="jsn-image-panel" class="jsn-accordion">
					<div class="jsn-accordion-control"> <span><?php echo JText::_('EXPAND ALL');?></span>&nbsp;&nbsp;|&nbsp; <span><?php echo JText::_('COLLAPSE ALL');?></span> </div>
					<div class="jsn-accordion-title" id="image-panel-image-presentation">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('IMAGE PRESENTATION'); ?> </div>
					<div class="jsn-accordion-pane" id="acc-image-presentation">
						<table class="admintable" width="100%" >
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE DEFAUT PRESENTATION MOD');?>::<?php echo JText::_('DES DEFAUT PRESENTATION MOD'); ?>"><?php echo JText::_('TITLE DEFAUT PRESENTATION MOD'); ?></span></td>
									<td><?php echo $lists['imgPanelPresentationMode']; ?></td>
								</tr>
								<tr>
									<td class="key" valign="top"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE PRESENTATION MODE CONFIGURATION');?>::<?php echo JText::_('DES PRESENTATION MODE CONFIGURATION'); ?>"><?php echo JText::_('TITLE PRESENTATION MODE CONFIGURATION'); ?></span></td>
									<td><div id="tabs">
											<ul id="primary" class="clearafter">
												<li><a href="javascript:JSNISClassicTheme.ShowcaseFitin();" id="fit"><?php echo JText::_('FIT IN'); ?></a></li>
												<li><a href="javascript:JSNISClassicTheme.ShowcaseExpand();" id="expand"><?php echo JText::_('EXPAND OUT'); ?></a></li>
											</ul>
										</div>
										<div id="tabs-main">
											<div id="item1">
												<table class="admintable" width="100%">
													<tbody>
														<tr>
															<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE IMAGE TRANSITION TYPE');?>::<?php echo JText::_('DES IMAGE TRANSITION TYPE'); ?>"><?php echo JText::_('TITLE IMAGE TRANSITION TYPE'); ?></span></td>
															<td><?php echo $lists['imgPanelImgTransitionTypeFit']; ?></td>
														</tr>
														<tr>
															<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE IMAGE TRANSITION TIMING');?>::<?php echo JText::_('DES IMAGE TRANSITION TIMING'); ?>"><?php echo JText::_('TITLE IMAGE TRANSITION TIMING'); ?></span></td>
															<td><input class="<?php echo $classImagePanel;?>" type="text" size="5" name="imgpanel_img_transition_timing_fit" value="<?php echo ($items->imgpanel_img_transition_timing_fit!='')?$items->imgpanel_img_transition_timing_fit:2; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
														</tr>
														<tr>
															<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE IMAGE CLICK ACTION');?>::<?php echo JText::_('DES IMAGE CLICK ACTION'); ?>"><?php echo JText::_('TITLE IMAGE CLICK ACTION'); ?></span></td>
															<td><?php echo $lists['imgPanelImgClickActionFit']; ?></td>
														</tr>
													</tbody>
												</table>
											</div>
											<div id="item2" style="display: none;">
												<table class="admintable" width="100%">
													<tbody>
														<tr>
															<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE IMAGE TRANSITION TYPE EXPAND');?>::<?php echo JText::_('DES IMAGE TRANSITION TYPE EXPAND'); ?>"><?php echo JText::_('TITLE IMAGE TRANSITION TYPE EXPAND'); ?></span></td>
															<td><?php echo $lists['imgPanelImgTransitionTypeExpand']; ?></td>
														</tr>
														<tr>
															<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE IMAGE TRANSITION TIMING');?>::<?php echo JText::_('DES IMAGE TRANSITION TIMING'); ?>"><?php echo JText::_('TITLE IMAGE TRANSITION TIMING'); ?></span></td>
															<td><input type="text" name="imgpanel_img_transition_timing_expand" size="5" value="<?php echo ($items->imgpanel_img_transition_timing_expand!='')?$items->imgpanel_img_transition_timing_expand:1; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
														</tr>
														<tr>
															<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE IMAGE MOTION TYPE');?>::<?php echo JText::_('DES IMAGE MOTION TYPE'); ?>"><?php echo JText::_('TITLE IMAGE MOTION TYPE'); ?></span></td>
															<td><?php echo $lists['imgPanelImgMotionTypeExpand']; ?></td>
														</tr>
														<tr>
															<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE IMAGE MOTION TIMING');?>::<?php echo JText::_('DES IMAGE MOTION TIMING'); ?>"><?php echo JText::_('TITLE IMAGE MOTION TIMING'); ?></span></td>
															<td><input type="text" size="5" name="imgpanel_img_motion_timing_expand" value="<?php echo ($items->imgpanel_img_motion_timing_expand!='')?$items->imgpanel_img_motion_timing_expand:2; ?>" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
														</tr>
														<tr>
															<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE IMAGE CLICK ACTION');?>::<?php echo JText::_('DES IMAGE CLICK ACTION'); ?>"><?php echo JText::_('TITLE IMAGE CLICK ACTION'); ?></span></td>
															<td><?php echo $lists['imgPanelImgClickActionExpand']; ?></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="image-panel-background">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('BACKGROUND'); ?> </div>
					<div id="acc-background" class="jsn-accordion-pane">
						<table class="admintable" border="0" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE BACKGROUND TYPE');?>::<?php echo JText::_('DES BACKGROUND TYPE'); ?>"><?php echo JText::_('TITLE BACKGROUND TYPE'); ?></span></td>
									<td><?php echo $lists['imgPanelBgType']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE BACKGROUND VALUE');?>::<?php echo JText::_('DES BACKGROUND VALUE'); ?>"><?php echo JText::_('TITLE BACKGROUND VALUE'); ?></span></td>
									<td class="showcase-input-field"><?php $imgpanel_bg_value = explode(',', $items->imgpanel_bg_value); ?>
										<div id="imgpanel-bg-input">
											<input class="<?php echo $classImagePanel; ?>" type="text" style="<?php echo ($items->imgpanel_bg_type == 2 or $items->imgpanel_bg_type == 3 or $items->imgpanel_bg_type == 1)?'width: 50px;':'width: 100%;'; ?>;" value="<?php echo @$imgpanel_bg_value[0]; ?>" name="imgpanel_bg_value[]" id="imgpanel_bg_value_first" onchange="JSNISClassicTheme.changeValueFlash('imagePanel', this);" readonly="readonly"/>
											<input class="<?php echo $classImagePanel; ?>" type="text" value="<?php echo @$imgpanel_bg_value[1]; ?>" name="imgpanel_bg_value[]" id="imgpanel_bg_value_last" readonly="readonly" style='<?php echo ($items->imgpanel_bg_type == 2 or $items->imgpanel_bg_type == 3)?'display:"";width:50px; ':'display: none'; ?>;' onchange="JSNISClassicTheme.changeValueFlash('imagePanel', this);"/>
											<div id="wrap-color" style="<?php echo ($items->imgpanel_bg_type == 2 or $items->imgpanel_bg_type == 3 or $items->imgpanel_bg_type == 1)?'display:"";':'display:none'; ?>">
												<p id="solid_value" style="<?php echo ($items->imgpanel_bg_type == 1)?'display:"";':'display: none'; ?>;"> <a href="" id="solid_link"><span id="span_solidpanel_bg_value_first" class="jsnis-icon-view-color" style='<?php echo 'background-color:'.$imgpanel_bg_value[0];?>;'></span><span class="color-selection"><?php echo JText::_('SELECT COLOR')?></span></a> </p>
												<p id="gradient_value" style="<?php echo ($items->imgpanel_bg_type == 2 or $items->imgpanel_bg_type == 3)?'display:"";':'display: none'; ?>;"> <a href="" id="gradient_link_1"><span id="span_imgpanel_bg_value_first" class="jsnis-icon-view-color" style='<?php echo 'background-color:'.@$imgpanel_bg_value[0];?>;<?php echo ($items->imgpanel_bg_type == 2 or $items->imgpanel_bg_type == 3)?'display:""':'display: none'; ?>;'></span><span class="color-selection"><?php echo JText::_('SELECT COLOR')?></span></a> <a href="" id="gradient_link_2"><span id="span_imgpanel_bg_value_last" class="jsnis-icon-view-color" style='<?php echo 'background-color:'.@$imgpanel_bg_value[1]; ?>;<?php echo ($items->imgpanel_bg_type == 2 or $items->imgpanel_bg_type == 3)?'display:""':'display: none'; ?>;'></span><span class="color-selection"><?php echo JText::_('SELECT COLOR')?></span></a> </p>
											</div>
										</div></td>
									<td nowrap="nowrap" width="5"><p id="pattern_title" style="<?php echo ($items->imgpanel_bg_type == 4)?'display:"";':'display: none'; ?>;"><?php echo JText::_('SELECT PATTERN')?>:&nbsp; <span id="pattern_value" style="<?php echo ($items->imgpanel_bg_type == 4)?'display:"";':'display: none'; ?>;"> <a class="modal" rel="{handler: 'iframe', size: {x: 590, y: 320}}" href="index.php?option=com_imageshow&controller=media&tmpl=component&act=pattern&e_name=text&event=loadMedia&theme=<?php echo $this->_showcaseThemeName; ?>"><?php echo JText::_('PREDEFINED')?></a>&nbsp;&nbsp;-&nbsp; <a class="modal" rel="{handler: 'iframe', size: {x: 590, y: 420}}" href="index.php?option=com_imageshow&controller=media&tmpl=component&act=custom&e_name=text&event=loadMedia&theme=<?php echo $this->_showcaseThemeName; ?>"><?php echo JText::_('CUSTOM')?></a> </span> </p>
										<p id="image_title" style="<?php echo ($items->imgpanel_bg_type == 5)?'display:"";':'display: none'; ?>;"> <span id="background_value" style="<?php echo ($items->imgpanel_bg_type == 5)?'display:"";':'display: none'; ?>;"> <a class="modal" rel="{handler: 'iframe', size: {x: 590, y: 420}}" href="index.php?option=com_imageshow&controller=media&tmpl=component&act=custom&e_name=text&event=loadMedia&theme=<?php echo $this->_showcaseThemeName; ?>"> <?php echo JText::_('SELECT IMAGE')?> </a> </span> </p></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="image-panel-watermark">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('WATERMARK'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" border="0" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW WATERMARK');?>::<?php echo JText::_('DES SHOW WATERMARK'); ?>"><?php echo JText::_('TITLE SHOW WATERMARK'); ?></span></td>
									<td><?php echo $lists['imgPanelShowWatermark']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE WATERMARK PATH');?>::<?php echo JText::_('DES WATERMARK PATH'); ?>"><?php echo JText::_('TITLE WATERMARK PATH'); ?></span></td>
									<td><div id="images-graphic-watermark">
											<input class="<?php echo $classImagePanel; ?>" type="text" size="50" value="<?php echo $items->imgpanel_watermark_path; ?>" name="imgpanel_watermark_path" readonly="readonly" id="imgpanel_watermark_path" onchange="JSNISClassicTheme.changeValueFlash('imagePanel', this);" />
										</div></td>
									<td width="5" nowrap="nowrap"><p id="watermark-title"><a class="modal" rel="{handler: 'iframe', size: {x: 590, y: 420}}" href="index.php?option=com_imageshow&controller=media&tmpl=component&act=watermark&e_name=text&event=loadMedia&theme=<?php echo $this->_showcaseThemeName; ?>"><?php echo JText::_('SELECT WATERMARK')?></a></p></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE WATERMARK POSITION');?>::<?php echo JText::_('DES WATERMARK POSITION'); ?>"><?php echo JText::_('TITLE WATERMARK POSITION'); ?></span></td>
									<td><?php echo $lists['imgPanelWatermarkPosition']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE WATERMARK OFFSET');?>::<?php echo JText::_('DES WATERMARK OFFSET'); ?>"><?php echo JText::_('TITLE WATERMARK OFFSET'); ?></span></td>
									<td><input class="<?php echo $classImagePanel; ?>" type="text" size="5" value="<?php echo ($items->imgpanel_watermark_offset!='')?$items->imgpanel_watermark_offset:10; ?>" name="imgpanel_watermark_offset" id="imgpanel_watermark_offset" <?php echo ($items->imgpanel_watermark_position =='center')?'disabled="disabled"':''; ?> onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE WATERMARK OPACITY');?>::<?php echo JText::_('DES WATERMARK OPACITY'); ?>"><?php echo JText::_('TITLE WATERMARK OPACITY'); ?></span></td>
									<td><input class="<?php echo $classImagePanel; ?>" type="text" size="5" value="<?php echo ($items->imgpanel_watermark_opacity!='')?$items->imgpanel_watermark_opacity:75; ?>" name="imgpanel_watermark_opacity" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="image-panel-overlay-effect">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('OVERLAY EFFECT'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW IMGP OVERLAY EFFECT');?>::<?php echo JText::_('DES SHOW IMGP OVERLAY EFFECT'); ?>"><?php echo JText::_('TITLE SHOW IMGP OVERLAY EFFECT');?></span></td>
									<td><?php echo $lists['imgPanelShowOverlayEffect']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE IMGP OVERLAY EFFECT TYPE');?>::<?php echo JText::_('DES IMGP OVERLAY EFFECT TYPE'); ?>"><?php echo JText::_('TITLE IMGP OVERLAY EFFECT TYPE');?></span></td>
									<td><?php echo $lists['imgPanelOverlayEffectType']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="image-panel-inner-shadow">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('INNER SHADOW'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW INNER SHADOW');?>::<?php echo JText::_('DES SHOW INNER SHADOW'); ?>"><?php echo JText::_('TITLE SHOW INNER SHADOW');?></span></td>
									<td><?php echo $lists['imgPanelShowInnerShawdow']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE INNER SHADOW COLOR');?>::<?php echo JText::_('DES INNER SHADOW COLOR'); ?>"><?php echo JText::_('TITLE INNER SHADOW COLOR');?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classImagePanel; ?>" type="text" size="15" value="<?php echo (!empty($items->imgpanel_inner_shawdow_color))?$items->imgpanel_inner_shawdow_color:'#000000'; ?>" readonly="readonly" name="imgpanel_inner_shawdow_color" id="imgpanel_inner_shawdow_color" onchange="JSNISClassicTheme.changeValueFlash('imagePanel', this);"/>
										<a href="" id="imgpanel_inner_shawdow_color_link"> <span id="span_imgpanel_inner_shawdow_color" class="jsnis-icon-view-color" style="<?php echo ($items->imgpanel_inner_shawdow_color!='')?'background:'.$items->imgpanel_inner_shawdow_color.';':'background:#000000;'; ?>"></span> <span class="color-selection"><?php echo JText::_('SELECT COLOR')?></span> </a></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
					echo $myTabs->endPanel();
					echo $myTabs->startPanel(JText::_('THUMBNAIL PANEL'),'thumb-panel');
			?>
				<div id="jsn-thumb-panel" class="jsn-accordion">
					<div class="jsn-accordion-control"> <span><?php echo JText::_('EXPAND ALL');?></span>&nbsp;&nbsp;|&nbsp; <span><?php echo JText::_('COLLAPSE ALL');?></span> </div>
					<div class="jsn-accordion-title" id="thumb-panel-general">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('GENERAL'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" border="0" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW THUMBNAIL PANEL');?>::<?php echo JText::_('DES SHOW THUMBNAIL PANEL'); ?>"><?php echo JText::_('TITLE SHOW THUMBNAIL PANEL'); ?></span></td>
									<td><?php echo $lists['thumbPanelShowPanel']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE THUMBNAIL PANEL POSITION');?>::<?php echo JText::_('DES THUMBNAIL PANEL POSITION'); ?>"><?php echo JText::_('TITLE THUMBNAIL PANEL POSITION'); ?></span></td>
									<td><?php echo $lists['thumbPanelPanelPosition']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE COLLAPSIBLE THUMBNAIL PANEL');?>::<?php echo JText::_('DES COLLAPSIBLE THUMBNAIL PANEL'); ?>"><?php echo JText::_('TITLE COLLAPSIBLE THUMBNAIL PANEL'); ?></span></td>
									<td><?php echo $lists['thumbPanelCollapsiblePosition']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE PANEL BACKGROUND COLOR');?>::<?php echo JText::_('DES PANEL BACKGROUND COLOR'); ?>"><?php echo JText::_('TITLE PANEL BACKGROUND COLOR'); ?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classThumbPanel; ?>" type="text" size="15" value="<?php echo (!empty($items->thumbpanel_thumnail_panel_color))?$items->thumbpanel_thumnail_panel_color:'#000000'; ?>" readonly="readonly" name="thumbpanel_thumnail_panel_color" id="thumbpanel_thumnail_panel_color" onchange="JSNISClassicTheme.changeValueFlash('thumbnailPanel', this);"/>
										<a href="" id="thumnail_panel_color"><span id="span_thumnail_panel_color" class="jsnis-icon-view-color" style="<?php echo (!empty($items->thumbpanel_thumnail_panel_color))?'background:'.$items->thumbpanel_thumnail_panel_color.';':'background:#000000;'; ?>"></span><span class="color-selection"><?php echo JText::_('SELECT COLOR')?></span></a></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE THUMBNAIL NORMAL STATE COLOR');?>::<?php echo JText::_('DES THUMBNAIL NORMAL STATE COLOR'); ?>"><?php echo JText::_('TITLE THUMBNAIL NORMAL STATE COLOR'); ?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classThumbPanel; ?>" type="text" size="15" value="<?php echo (!empty($items->thumbpanel_thumnail_normal_state))?$items->thumbpanel_thumnail_normal_state:'#ffffff'; ?>" readonly="readonly" name="thumbpanel_thumnail_normal_state" id="thumbpanel_thumnail_normal_state" onchange="JSNISClassicTheme.changeValueFlash('thumbnailPanel', this);"/>
										<a href="" id="thumnail_normal_state"><span id="span_thumnail_normal_state" class="jsnis-icon-view-color" style="<?php echo (!empty($items->thumbpanel_thumnail_normal_state))?'background:'.$items->thumbpanel_thumnail_normal_state.';':'background:#ffffff;'; ?>"></span><span class="color-selection"><?php echo JText::_('SELECT COLOR')?></span></a></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE ACTIVE STATE COLOR');?>::<?php echo JText::_('DES ACTIVE STATE COLOR'); ?>"><?php echo JText::_('TITLE ACTIVE STATE COLOR'); ?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classThumbPanel; ?>" type="text" size="15" value="<?php echo (!empty($items->thumbpanel_active_state_color))?$items->thumbpanel_active_state_color:'#ff6200'; ?>" readonly="readonly" name="thumbpanel_active_state_color" id="thumbpanel_active_state_color" onchange="JSNISClassicTheme.changeValueFlash('thumbnailPanel', this);"/>
										<a href="" id="active_state_color"><span id="span_thumbpanel_active_state_color" class="jsnis-icon-view-color" style="<?php echo (!empty($items->thumbpanel_active_state_color))?'background:'.$items->thumbpanel_active_state_color.';':'background:#ff6200;'; ?>"></span><span class="color-selection"><?php echo JText::_('SELECT COLOR')?></span></a></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="thumb-panel-thumbnail">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('THUMBNAIL'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" border="0" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW THUMBNAILS STATUS');?>::<?php echo JText::_('DES SHOW THUMBNAILS STATUS'); ?>"><?php echo JText::_('TITLE SHOW THUMBNAILS STATUS'); ?></span></td>
									<td><?php echo $lists['thumbPanelShowThumbStatus']; ?></td>
								</tr>
								<tr>
									<td class="key" nowrap="nowrap" ><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE THUMBNAILS PRESENTATION MODE');?>::<?php echo JText::_('DES THUMBNAILS PRESENTATION MODE'); ?>"><?php echo JText::_('TITLE THUMBNAILS PRESENTATION MODE');?></span></td>
									<td><?php echo $lists['thumbPanelPresentationMode']; ?></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE THUMBNAILS BROWSING MODE');?>::<?php echo JText::_('DES THUMBNAILS BROWSING MODE'); ?>"><?php echo JText::_('TITLE THUMBNAILS BROWSING MODE'); ?></span></td>
									<td><?php echo $lists['thumbPanelThumbBrowsingMode']; ?></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE THUMBNAIL ROW');?>::<?php echo JText::_('DES THUMBNAIL ROW'); ?>"><?php echo JText::_('TITLE THUMBNAIL ROW'); ?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_thumb_row!='')?$items->thumbpanel_thumb_row:1; ?>" name="thumbpanel_thumb_row" id="thumbpanel_thumb_row" <?php echo ($items->thumbpanel_thumb_browsing_mode =='sliding')?'readonly="readonlye"':''; ?> onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE THUMBNAIL WIDTH');?>::<?php echo JText::_('DES THUMBNAIL WIDTH'); ?>"><?php echo JText::_('TITLE THUMBNAIL WIDTH'); ?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_thumb_width!='')?$items->thumbpanel_thumb_width:50; ?>" name="thumbpanel_thumb_width" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE THUMBNAIL HEIGHT');?>::<?php echo JText::_('DES THUMBNAIL HEIGHT'); ?>"><?php echo JText::_('TITLE THUMBNAIL HEIGHT'); ?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_thumb_height!='')?$items->thumbpanel_thumb_height:40; ?>" name="thumbpanel_thumb_height" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE THUMBNAIL BORDER');?>::<?php echo JText::_('DES THUMBNAIL BORDER'); ?>"><?php echo JText::_('TITLE THUMBNAIL BORDER');?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_border!='')?$items->thumbpanel_border:1; ?>" name="thumbpanel_border" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="thumb-panel-big-thumbnail">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('BIG THUMBNAIL'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE ENABLE BIG THUMBNAIL');?>::<?php echo JText::_('DES ENABLE BIG THUMBNAIL'); ?>"><?php echo JText::_('TITLE ENABLE BIG THUMBNAIL'); ?></span></td>
									<td><?php echo $lists['thumbPanelEnableBigThumb']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE BIG THUMBNAIL SIZE');?>::<?php echo JText::_('DES BIG THUMBNAIL SIZE'); ?>"><?php echo JText::_('TITLE BIG THUMBNAIL SIZE'); ?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="5" value="<?php echo ($items->thumbpanel_big_thumb_size!='')?$items->thumbpanel_big_thumb_size:150; ?>" name="thumbpanel_big_thumb_size" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE BIG THUMBNAIL BORDER');?>::<?php echo JText::_('DES BIG THUMBNAIL BORDER'); ?>"><?php echo JText::_('TITLE BIG THUMBNAIL BORDER'); ?></span></td>
									<td><input class="<?php echo $classThumbPanel; ?>" type="text" size="15" value="<?php echo ($items->thumbpanel_thumb_border!='')?$items->thumbpanel_thumb_border:2; ?>" name="thumbpanel_thumb_border" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE BIG THUMBNAIL COLOR');?>::<?php echo JText::_('DES BIG THUMBNAIL COLOR'); ?>"><?php echo JText::_('TITLE BIG THUMBNAIL COLOR'); ?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classThumbPanel; ?>" type="text" size="15" value="<?php echo (!empty($items->thumbpanel_big_thumb_color))?$items->thumbpanel_big_thumb_color:'#ffffff'; ?>" readonly="readonly" name="thumbpanel_big_thumb_color" id="thumbpanel_big_thumb_color" onchange="JSNISClassicTheme.changeValueFlash('thumbnailPanel', this);"/>
										<a href="" id="big_thumb_color"><span id="span_thumbpanel_big_thumb_color" class="jsnis-icon-view-color" style="<?php echo (!empty($items->thumbpanel_big_thumb_color))?'background:'.$items->thumbpanel_big_thumb_color.';':'background:#ffffff;'; ?>"></span><span class="color-selection"><?php echo JText::_('SELECT COLOR')?></span></a></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
					echo $myTabs->endPanel();
					echo $myTabs->startPanel(JText::_('INFOMATION PANEL'),'info-panel');
			?>
				<div id="jsn-info-panel" class="jsn-accordion">
					<div class="jsn-accordion-control"> <span><?php echo JText::_('EXPAND ALL');?></span>&nbsp;&nbsp;|&nbsp; <span><?php echo JText::_('COLLAPSE ALL');?></span> </div>
					<div class="jsn-accordion-title" id="info-panel-general">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('GENERAL'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE INFO PANEL PRESENTATION');?>::<?php echo JText::_('DES INFO PANEL PRESENTATION'); ?>"><?php echo JText::_('TITLE INFO PANEL PRESENTATION'); ?></span></td>
									<td><?php echo $lists['infoPanelPresentation']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE INFO PANEL POSITION');?>::<?php echo JText::_('DES INFO PANEL POSITION'); ?>"><?php echo JText::_('TITLE INFO PANEL POSITION'); ?></span></td>
									<td><?php echo $lists['infoPanelPanelPosition']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE PANEL BACKGROUND COLOR');?>::<?php echo JText::_('DES PANEL BACKGROUND COLOR'); ?>"><?php echo JText::_('TITLE PANEL BACKGROUND COLOR'); ?></span></td>
									<td class="showcase-input-field"><input class="<?php echo $classInfoPanel; ?>" type="text" size="15" value="<?php echo (!empty($items->infopanel_bg_color_fill))?$items->infopanel_bg_color_fill:'#000000'; ?>" readonly="readonly" name="infopanel_bg_color_fill" id="infopanel_bg_color_fill" onchange="JSNISClassicTheme.changeValueFlash('informationPanel', this);"/>
										<a href="" id="bg_color_fill"><span id="span_bg_color_fill" class="jsnis-icon-view-color" style="<?php echo (!empty($items->infopanel_bg_color_fill))?'background:'.$items->infopanel_bg_color_fill.';':'background:#000000;'; ?>"></span><span class="color-selection"><?php echo JText::_('SELECT COLOR')?></span></a></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE PANEL CLICK ACTION');?>::<?php echo JText::_('DES PANEL CLICK ACTION'); ?>"><?php echo JText::_('TITLE PANEL CLICK ACTION'); ?></span></td>
									<td><?php echo $lists['infoPanelPanelClickAction']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="info-panel-title">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('TITLE'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW TITLE');?>::<?php echo JText::_('DES SHOW TITLE'); ?>"><?php echo JText::_('TITLE SHOW TITLE'); ?></span></td>
									<td><?php echo $lists['infoPanelShowTitle']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE TITLE CSS');?>::<?php echo JText::_('DES TITLE CSS'); ?>"><?php echo JText::_('TITLE TITLE CSS'); ?></span></td>
									<td><textarea  class="<?php echo $classInfoPanel; ?>" cols="37" rows="5" name="infopanel_title_css"><?php echo $items->infopanel_title_css; ?></textarea></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="info-panel-description">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('DESCRIPTION'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW DESCRIPTION');?>::<?php echo JText::_('DES SHOW DESCRIPTION'); ?>"><?php echo JText::_('TITLE SHOW DESCRIPTION'); ?></span></td>
									<td><?php echo $lists['infoPanelShowDes']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE DESCRIPTION LENGTH LIMITATION');?>::<?php echo JText::_('DES DESCRIPTION LENGTH LIMITATION'); ?>"><?php echo JText::_('TITLE DESCRIPTION LENGTH LIMITATION'); ?></span></td>
									<td><input  class="<?php echo $classInfoPanel; ?>" type="text" size="5" value="<?php echo ($items->infopanel_des_lenght_limitation!='')?$items->infopanel_des_lenght_limitation:50; ?>" name="infopanel_des_lenght_limitation" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE DESCRIPTION CSS');?>::<?php echo JText::_('DES DESCRIPTION CSS'); ?>"><?php echo JText::_('TITLE DESCRIPTION CSS'); ?></span></td>
									<td><textarea class="<?php echo $classInfoPanel; ?>" cols="37" rows="5" name="infopanel_des_css"><?php echo $items->infopanel_des_css; ?></textarea></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="info-panel-link">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('LINK'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW LINK');?>::<?php echo JText::_('DES SHOW LINK'); ?>"><?php echo JText::_('TITLE SHOW LINK'); ?></span></td>
									<td><?php echo $lists['infoPanelShowLink']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE LINK CSS');?>::<?php echo JText::_('DES LINK CSS'); ?>"><?php echo JText::_('TITLE LINK CSS'); ?></span></td>
									<td><textarea  class="<?php echo $classInfoPanel; ?>" cols="40" rows="5" name="infopanel_link_css"><?php echo $items->infopanel_link_css; ?></textarea></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
					echo $myTabs->endPanel();
					echo $myTabs->startPanel(JText::_('TOOLBAR PANEL'),'toolbar-panel');
			?>
				<div id="jsn-toolbar-panel" class="jsn-accordion">
					<div class="jsn-accordion-control"> <span><?php echo JText::_('EXPAND ALL');?></span>&nbsp;&nbsp;|&nbsp; <span><?php echo JText::_('COLLAPSE ALL');?></span> </div>
					<div class="jsn-accordion-title" id="toolbar-panel-general">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('GENERAL'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE TOOLBAR PANEL PRESENTATION');?>::<?php echo JText::_('DES PTOOLBAR ANEL PRESENTATION'); ?>"><?php echo JText::_('TITLE TOOLBAR PANEL PRESENTATION');?></span></td>
									<td><?php echo $lists['toolBarPanelPresentation']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE TOOLBAR PANEL POSITION');?>::<?php echo JText::_('DES TOOLBAR PANEL POSITION'); ?>"><?php echo JText::_('TITLE TOOLBAR PANEL POSITION');?></span></td>
									<td><?php echo $lists['toolBarPanelPanelPosition']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE TOOLBAR PANEL SHOW TOOLTIP');?>::<?php echo JText::_('DES TOOLBAR PANEL SHOW TOOLTIP'); ?>"><?php echo JText::_('TITLE TOOLBAR PANEL SHOW TOOLTIP');?></span></td>
									<td><?php echo $lists['toolBarPanelShowTooltip']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="toolbar-panel-functions">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('FUNCTIONS'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW IMAGE NAVIGATION');?>::<?php echo JText::_('DES SHOW IMAGE NAVIGATION'); ?>"><?php echo JText::_('TITLE SHOW IMAGE NAVIGATION'); ?></span></td>
									<td><?php echo $lists['toolBarPanelShowImageNavigation']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW SLIDESHOW PLAYER');?>::<?php echo JText::_('DES SHOW SLIDESHOW PLAYER'); ?>"><?php echo JText::_('TITLE SHOW SLIDESHOW PLAYER');?></span></td>
									<td><?php echo $lists['toolBarPanelSlideShowPlayer']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW FULLSCREEN SWITCHER');?>::<?php echo JText::_('DES SHOW FULLSCREEN SWITCHER'); ?>"><?php echo JText::_('TITLE SHOW FULLSCREEN SWITCHER');?></span></td>
									<td><?php echo $lists['toolBarPanelShowFullscreenSwitcher'];?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
					echo $myTabs->endPanel();	
					echo $myTabs->startPanel(JText::_('SLIDESHOW'),'slideshow-panel');
			?>
				<div id="jsn-slideshow-panel" class="jsn-accordion">
					<div class="jsn-accordion-control"> <span><?php echo JText::_('EXPAND ALL');?></span>&nbsp;&nbsp;|&nbsp; <span><?php echo JText::_('COLLAPSE ALL');?></span> </div>
					<div class="jsn-accordion-title" id="slideshow-panel-slideshow-presentation">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('SLIDESHOW PRESENTATION'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SLIDE IMAGE PRESENTATION MODE');?>::<?php echo JText::_('DES SLIDE IMAGE PRESENTATION MODE'); ?>"><?php echo JText::_('TITLE SLIDE IMAGE PRESENTATION MODE');?></span></td>
									<td><?php echo $lists['slideShowPresentationMode']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE KENBURN');?>::<?php echo JText::_('DES KENBURN'); ?>"><?php echo JText::_('TITLE KENBURN');?></span></td>
									<td><?php echo $lists['slideShowEnableKenBurnEffect']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOWSTATUS');?>::<?php echo JText::_('DES SHOWSTATUS'); ?>"><?php echo JText::_('TITLE SHOWSTATUS');?></span></td>
									<td><?php echo $lists['slideShowShowStatus']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="slideshow-panel-element-presentation">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('ELEMENT PRESENTATION'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable" width="100%">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SILDE SHOW THUMBNAIL PANEL');?>::<?php echo JText::_('DES SILDE SHOW THUMBNAIL PANEL'); ?>"><?php echo JText::_('TITLE SILDE SHOW THUMBNAIL PANEL'); ?></span></td>
									<td><?php echo $lists['slideShowShowThumbPanel']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SLIDE SHOW IMAGE NAVIGATION');?>::<?php echo JText::_('DES SLIDE SHOW IMAGE NAVIGATION'); ?>"><?php echo JText::_('TITLE SLIDE SHOW IMAGE NAVIGATION');?></span></td>
									<td><?php echo $lists['slideShowShowImageNavigation']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SLIDE SHOW WATERMARK');?>::<?php echo JText::_('DES SLIDE SHOW WATERMARK'); ?>"><?php echo JText::_('TITLE SLIDE SHOW WATERMARK');?></span></td>
									<td><?php echo $lists['slideShowShowWaterMark']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SHOW OVERLAY EFFECT');?>::<?php echo JText::_('DES SHOW OVERLAY EFFECT'); ?>"><?php echo JText::_('TITLE SHOW OVERLAY EFFECT');?></span></td>
									<td><?php echo $lists['slideShowShowOverlayEffect']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="jsn-accordion-title" id="slideshow-panel-slideshow-process">
						<div class="jsn-accordion-button"></div>
						<?php echo JText::_('SLIDESHOW PROCESS'); ?> </div>
					<div class="jsn-accordion-pane">
						<table class="admintable">
							<tbody>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SLIDE TIMING');?>::<?php echo JText::_('DES SLIDE TIMING'); ?>"><?php echo JText::_('TITLE SLIDE TIMING');?></span></td>
									<td><input class="<?php echo $classSlideShowPanel; ?>" type="text" size="5" value="<?php echo ($items->slideshow_slide_timing!='')?$items->slideshow_slide_timing:8; ?>" name="slideshow_slide_timing" onchange="checkInputValue(this, 0);" onfocus="getInputValue(this);" /></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE AUTO PLAY');?>::<?php echo JText::_('DES AUTO PLAY'); ?>"><?php echo JText::_('TITLE AUTO PLAY');?></span></td>
									<td><?php echo $lists['slideShowProcess']; ?></td>
								</tr>
								<tr>
									<td class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE SLIDESHOW LOOPING');?>::<?php echo JText::_('DES SLIDESHOW LOOPING'); ?>"><?php echo JText::_('TITLE SLIDESHOW LOOPING');?></span></td>
									<td><?php echo $lists['slideShowLooping']; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
					echo $myTabs->endPanel();					
				echo $myTabs->endPane();
			?>
			</div></td>
		<td valign="top" style="width:571px;"><div class="jsn-preview-wrapper">
				<?php include dirname(__FILE__).DS.'preview.php'; ?>
			</div></td>
	</tr>
</table>
<div style="clear:both;"></div>
