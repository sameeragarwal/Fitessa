<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="com-newsfeed <?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<div class="category">
	<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
		<h2 class="componentheading"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
	<?php endif; ?>
	<?php if ( @$this->image || @$this->category->description ) : ?>
	<div class="contentdescription clearafter">
		<?php
			if ( isset($this->image) ) :  echo $this->image; endif;
			echo $this->category->description;
		?>
	</div>
	<?php endif; ?>
	<?php echo $this->loadTemplate('items'); ?>
</div></div>
