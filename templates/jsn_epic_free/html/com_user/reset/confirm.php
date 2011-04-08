<?php defined('_JEXEC') or die; ?>
<div class="reset-confirm">
<h2 class="componentheading">
	<?php echo JText::_('Confirm your Account'); ?>
</h2>
<p class="contentdescription clearafter"><?php echo JText::_('RESET_PASSWORD_CONFIRM_DESCRIPTION'); ?></p>
<form action="<?php echo JRoute::_( 'index.php?option=com_user&task=confirmreset' ); ?>" method="post" class="josForm form-validate">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tbody>
	<tr>
		<td width="15%">
			<p><label for="username" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_USERNAME_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_USERNAME_TIP_TEXT'); ?>"><?php echo JText::_('User Name'); ?>:</label></p>
		</td>
		<td><input id="username" name="username" type="text" class="required" size="36" /></td>
	</tr>
	<tr>
		<td>
			<p><label for="token" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_TOKEN_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_TOKEN_TIP_TEXT'); ?>">
				<?php echo JText::_('Token'); ?>:
			</label></p>
		</td>
		<td>
			<input id="token" name="token" type="text" class="required" size="36" />
		</td>
	</tr>
	</tbody>
	</table>
	<p><button type="submit" class="validate"><?php echo JText::_('Submit'); ?></button></p>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>