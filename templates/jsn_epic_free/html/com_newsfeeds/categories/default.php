<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="com-newsfeed <?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<div class="category-list">
	<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
		<h2 class="componentheading">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h2>
	<?php endif; ?>
	<?php if ( ($this->params->get('image') != -1) || $this->params->get('show_comp_description') ) : ?>
		<div class="contentdescription clearafter">
			<?php
				if ( isset($this->image) ) :  echo $this->image; endif;
				echo $this->params->get('comp_description');
			?>
		</div>
	<?php endif; ?>
    <ul class="jsn-infolist">
	<?php foreach ( $this->categories as $category ) : ?>
		<li>
			<a href="<?php echo $category->link ?>" class="category"><?php echo $category->title;?></a>
			<?php if ( $this->params->get( 'show_cat_items' ) ) : ?><span class="small">(<?php echo $category->numlinks;?>)</span><?php endif; ?>
			<?php if ( $this->params->get( 'show_cat_description' ) && $category->description ) : ?><br /><?php echo $category->description; ?><?php endif; ?>
		</li>
	<?php endforeach; ?>
	</ul>
</div>
</div>

