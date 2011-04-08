<?php defined('_JEXEC') or die; ?>
<div class="com-user <?php echo $this->params->get('pageclass_sfx') ?>">
	<div class="default-reset">
		<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
		<h2 class="componentheading">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h2>
		<?php endif; ?>
		<p class="contentdescription clearafter"><?php echo JText::_('RESET_PASSWORD_REQUEST_DESCRIPTION'); ?></p>
		<form action="<?php echo JRoute::_( 'index.php?option=com_user&task=requestreset' ); ?>" method="post" class="josForm form-validate">
			<table cellpadding="5" cellspacing="0" border="0" width="100%" class="jsn-formtable paramlist">
			<tr>
				<td class="paramlist_key" width="40%">
					<label for="email" class="hasTip" title="<?php echo JText::_('RESET_PASSWORD_EMAIL_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_EMAIL_TIP_TEXT'); ?>">
						<?php echo JText::_('Email Address'); ?>:
					</label>
				</td>
				<td class="paramlist_value">
					<input id="email" name="email" type="text" class="required validate-email" size="40" />
				</td>
			</tr>
			<tr>
				<td>
					<button type="submit" class="validate"><?php echo JText::_('Submit'); ?></button>
				</td>
				<td>
					<?php echo JHTML::_( 'form.token' ); ?>
				</td>
			</tr>
			</table>
		</form>
	</div>
</div>