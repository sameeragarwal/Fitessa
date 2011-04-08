<?php 
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access'); 
$act = JRequest::getCmd('act','custom');
?>
<script type='text/javascript'>
	
<?php if($act =='custom'){?>
var image_base_path = 'images/';
window.addEvent('domready', function(){		
	var str = window.parent.$('imgpanel_bg_value_first').value;
	if(str.indexOf("bg-patterns") == -1 && str.indexOf("bg-images") == -1){
		$('f_url').value = window.parent.$('imgpanel_bg_value_first').value;	
	}else{
		$('f_url').value ='';
	}
});
<?php }?>
<?php if($act =='background'){?>
var image_base_path = 'components/com_imageshow/assets/images/bg-images/';
window.addEvent('domready', function(){	
	var str = window.parent.$('imgpanel_bg_value_first').value;	
	if(str.indexOf("bg-images") != -1 && str.indexOf("bg-patterns") == -1){
		$('f_url').value = window.parent.$('imgpanel_bg_value_first').value;	
	}else{
		$('f_url').value ='';
	}	
});
<?php }?>
<?php if($act =='pattern'){?>
var image_base_path = 'plugins/<?php echo $this->_showcaseThemeType.'/'.$this->_showcaseThemeName; ?>/assets/images/bg-patterns/';
window.addEvent('domready', function(){
	var str = window.parent.$('imgpanel_bg_value_first').value;	
	if(str.indexOf("bg-patterns") != -1 && str.indexOf("bg-images") == -1){
		$('f_url').value = window.parent.$('imgpanel_bg_value_first').value;	
	}else{
		$('f_url').value ='';
	}	
});
<?php }?>
<?php if($act =='watermark'){?>

var image_base_path = 'images/';
window.addEvent('domready', function(){	
	$('f_url').value ='';
	$('f_url').value = window.parent.$('imgpanel_watermark_path').value;	
});
<?php }?>

<?php if($act =='showlist'){?>
var image_base_path = 'images/';
window.addEvent('domready', function(){	
	$('f_url').value ='';
	$('f_url').value = window.parent.$('alter_image_path').value;
});
<?php }?>	
	
</script>

<div class="jsn-predefine-bg-selection">
<h3 class="jsn-select-bg-pt">
	<?php 
	if($act =='custom'){
		echo JText::_('Select Custom Graphic'); 
	}
	if($act =='background' or $act =='pattern'){
		echo JText::_('Select Predefined Graphic'); 
	}
	if($act =='watermark'){
		echo JText::_('Select watermark'); 
	}
	if($act == 'showlist'){
		echo JText::_('Select Image');
	}	
	?>
</h3>

<fieldset class="jsn-url-properties" style="<?php echo ($act =='custom' || $act =='watermark' || $act =='showlist')? '': 'display:none;';?>">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td nowrap="nowrap">
				<form action="<?php echo JURI::base(); ?>index.php?option=com_imageshow&amp;controller=media&amp;task=upload&amp;tmpl=component&amp;event=onUpload&amp;theme=<?php echo $this->_showcaseThemeName; ?>&amp;<?php echo $this->session->getName().'='.$this->session->getId(); ?>&amp;pop_up=1&amp;<?php echo JUtility::getToken();?>=1" style="<?php echo ($act =='custom' || $act =='watermark' || $act =='showlist')? '': 'display:none;';?>" id="uploadForm" method="post" enctype="multipart/form-data">
						<label for="folder"><?php echo JText::_('Upload') ?>:&nbsp;</label>
						<input type="file" id="file-upload" name="Filedata" size="76" />
						<button type="submit" id="file-upload-submit" title="<?php echo JText::_('Start Upload'); ?>"><?php echo JText::_('Start Upload'); ?></button>
						<span id="upload-clear"></span>						

					<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_imageshow&controller=media&act='.$act.'&tmpl=component&event=loadMedia&theme='.$this->_showcaseThemeName.'&e_name='.JRequest::getCmd('e_name')); ?>" />
				</form>		
			</td>
		</tr>
	</table>
</fieldset>
<fieldset class="jsn-url-properties" style="<?php echo ($act =='custom' || $act =='watermark' || $act =='showlist')? '': 'display:none;';?>">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td nowrap="nowrap">
				<form action="index.php" id="imageForm" method="post" enctype="multipart/form-data">
					<div style="float: left; <?php echo ($act =='custom' || $act =='watermark' || $act =='showlist')? '': 'display:none;';?>">
						<label for="folder"><?php echo JText::_('Directory') ?>:&nbsp;</label>
						<?php echo $this->folderList; ?>
						&nbsp;<button type="button" id="upbutton" title="<?php echo JText::_('Directory Up') ?>"><?php echo JText::_('Up') ?></button>
					</div>		
				</form>
			</td>
		</tr>
	</table>
</fieldset>
<div class="jsn-image-folder-list">
<iframe id="imageframe" name="imageframe" src="index.php?option=com_imageshow&amp;controller=media&amp;view=imageslist&amp;act=<?php echo $act; ?>&amp;tmpl=component&amp;folder=<?php echo $this->stateFolder;?>&event=loadMediaImagesList&theme=<?php echo $this->_showcaseThemeName; ?>"></iframe>
</div>
<form action="index.php" id="imageForm" method="post" enctype="multipart/form-data">
	<div id="messages" style="display: none;">
		<span id="message"></span>
	</div>
	<fieldset class="jsn-image-url">
		<table border="0" width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td nowrap="nowrap"><label for="f_url"><?php echo JText::_('Image URL') ?>&nbsp;</label></td>
				<td width="100%"><input type="text" id="f_url" value="" readonly="readonly" /></td>		
			</tr>	
		</table>
	</fieldset>
    <div class="button">
	<button type="button" onclick="JSNISImageManager.onok('<?php echo $act?>');window.parent.document.getElementById('sbox-window').close();"><?php echo JText::_('Select') ?></button>
	<button type="button" onclick="window.parent.document.getElementById('sbox-window').close();"><?php echo JText::_('Cancel') ?></button>
	</div>
	<input type="hidden" id="dirPath" name="dirPath" />
	<input type="hidden" id="f_file" name="f_file" />
	<input type="hidden" id="tmpl" name="component" />
</form>
</div>