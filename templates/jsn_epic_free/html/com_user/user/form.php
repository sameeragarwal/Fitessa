<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">
<!--
	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
	});
// -->
</script>
<div class="com-user <?php echo $this->params->get('pageclass_sfx') ?>">
	<div class="user-form">
		<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
		<h2 class="componentheading">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h2>
		<?php endif; ?>
		<form action="<?php echo JRoute::_( 'index.php' ); ?>" method="post" name="userform" autocomplete="off" class="form-validate">
			<table cellpadding="5" cellspacing="0" border="0" width="100%" class="jsn-formtable paramlist">
			<tr>
				<td class="paramlist_key" width="40%">
					<label for="username">
						<?php echo JText::_( 'User Name' ); ?>:
					</label>
				</td>
				<td class="paramlist_value">
					<span><?php echo $this->user->get('username');?></span>
				</td>
			</tr>
			<tr>
				<td class="paramlist_key">
					<label for="name">
						<?php echo JText::_( 'Your Name' ); ?>:
					</label>
				</td>
				<td class="paramlist_value">
					<input class="inputbox required" type="text" id="name" name="name" value="<?php echo $this->user->get('name');?>" size="40" />
				</td>
			</tr>
			<tr>
				<td class="paramlist_key">
					<label for="email">
						<?php echo JText::_( 'email' ); ?>:
					</label>
				</td>
				<td class="paramlist_value">
					<input class="inputbox required validate-email" type="text" id="email" name="email" value="<?php echo $this->user->get('email');?>" size="40" />
				</td>
			</tr>
			<?php if($this->user->get('password')) : ?>
			<tr>
				<td class="paramlist_key">
					<label for="password">
						<?php echo JText::_( 'Password' ); ?>:
					</label>
				</td>
				<td class="paramlist_value">
					<input class="inputbox validate-password" type="password" id="password" name="password" value="" size="40" />
				</td>
			</tr>
			<tr>
				<td class="paramlist_key">
					<label for="password2">
						<?php echo JText::_( 'Verify Password' ); ?>:
					</label>
				</td>
				<td class="paramlist_value">
					<input class="inputbox validate-passverify" type="password" id="password2" name="password2" size="40" />
				</td>
			</tr>
			<?php endif; ?>
			</table>
			<?php if(isset($this->params)) :  echo $this->params->render( 'params' ); endif; ?>
			<button class="button validate" type="submit" onclick="submitbutton( this.form );return false;"><?php echo JText::_('Save'); ?></button>
			<input type="hidden" name="username" value="<?php echo $this->user->get('username');?>" />
			<input type="hidden" name="id" value="<?php echo $this->user->get('id');?>" />
			<input type="hidden" name="gid" value="<?php echo $this->user->get('gid');?>" />
			<input type="hidden" name="option" value="com_user" />
			<input type="hidden" name="task" value="save" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
</div>