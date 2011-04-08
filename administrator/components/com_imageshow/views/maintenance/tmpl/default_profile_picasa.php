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
JHTML::_('behavior.tooltip');
$configurationID = JRequest::getInt('configuration_id');
?>
<script language="javascript">
	function onSubmit()
	{
		var form = document.adminForm;
		if(form.configuration_title.value == '')
		{
			alert( "<?php echo JText::_('REQUIRED FIELD PROFILE TITLE CANNOT BE LEFT BLANK', true); ?>");
			return;
		}else{
			$('submit-form').disabled=true;
			$('cancel').disabled=true;
			form.submit();
			window.top.setTimeout('window.parent.document.getElementById("sbox-window").close(); window.top.location.reload(true)', 1000);
		}
	}
</script>
<!--[if IE 7]>
	<link href="<?php echo JURI::base();?>components/com_imageshow/assets/css/fixie7.css" rel="stylesheet" type="text/css" />
<![endif]-->
<div id="jsnis-image-source-profile-details">
<form name='adminForm' id='adminForm' action="index.php" method="post" onsubmit="return false;">
<table cellspacing="0" width="100%" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td><h3 class="jsnis-element-heading"><?php echo JText::_('IMAGE SOURCE PROFILE SETTINGS'); ?></h3></td>
		</tr>
		<tr>
			<td>
				<table class="admintable configuration-picasa">
					<tr>
						<td width="25%" class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE PROFILE TITLE');?>::<?php echo JText::_('DES PROFILE TITLE'); ?>"><?php echo JText::_('TITLE PROFILE TITLE');?></span></td>
						<td><input type="text" size="84" name ="configuration_title" id ="configuration_title" value = "<?php echo @$this->profileinfo->configuration_title;?>"/></td>
					</tr>
					<tr>
						<td width="25%" class="key"><span class="editlinktip hasTip" title="<?php echo JText::_('TITLE PICASA USER');?>::<?php echo JText::_('DES PICASA USER'); ?>"><?php echo JText::_('TITLE PICASA USER'); ?></span></td>
						<td><input type="text" disabled="disabled" size="84" value="<?php echo @$this->profileinfo->picasa_user_name;?>" name="" id="" /></td>
					</tr>
				</table>
			</td>
		</tr>			
		<tr>
			<td align="center"><div class="button">
					<button type="button" onclick="return onSubmit();" id="submit-form" class="button"  title="<?php echo JText::_('OK');?>"><?php echo JText::_('OK');?></button>
					<button type="button" class="button" id="cancel" title="<?php echo JText::_('CANCEL');?>" onclick="window.top.setTimeout('window.parent.document.getElementById(\'sbox-window\').close()', 200);"><?php echo JText::_('CANCEL');?></button>
				</div></td>
		</tr>
	</tbody>
</table>
<input type="hidden" name="option" value="com_imageshow" />
<input type="hidden" name="controller" value="maintenance" />
<input type="hidden" name="task" value="saveprofile" id="task" />
<input type="hidden" name="configuration_id" value="<?php echo $configurationID; ?>" id="configuration_id" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
