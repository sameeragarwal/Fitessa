<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="com-user <?php echo $this->params->get('pageclass_sfx') ?>">
<div class="default-user">
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<h2 class="componentheading">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h2>
<?php endif; ?>
<p><?php echo nl2br($this->params->get('welcome_desc', JText::_( 'WELCOME_DESC' ))); ?></p>
</div></div>