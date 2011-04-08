<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php /** @todo Should this be routed */ ?>
<?php if ( $this->params->get( 'show_logout_title' ) ) : ?>
<h2>
	<?php echo $this->params->get( 'header_logout' ); ?>
</h2>
<?php endif; ?>
<div class="contentdescription clearafter">
	<?php echo $this->image; ?>
	<?php
		if ($this->params->get('description_logout')) :
			echo $this->params->get('description_logout_text');
		endif;
	?>
</div>
<form action="index.php" method="post" name="login" id="login">
<input type="hidden" name="option" value="com_user" />
<input type="hidden" name="task" value="logout" />
<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
<div class="jsn-formbuttons">
	<input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'Logout' ); ?>" />
</div>
</form>
