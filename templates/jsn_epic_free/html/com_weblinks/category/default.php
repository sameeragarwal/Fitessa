<?php defined('_JEXEC') or die('Restricted access'); ?>
<div class="com-weblink <?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<div class="category-list">
	<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
		<h2 class="componentheading">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h2>
	<?php endif; ?>
	<?php if ( @$this->category->image || @$this->category->description ) : ?>
		<div class="contentdescription clearafter">
			<?php
				if ( isset($this->category->image) ) :  echo $this->category->image; endif;
				echo $this->category->description;
			?>
		</div>
	<?php endif; ?>
	<?php echo $this->loadTemplate('items'); ?>
	<?php if ($this->params->get('show_other_cats', 1)): ?>
		<ul class="jsn-infolist">
			<?php foreach ( $this->categories as $category ) : ?>
				<li>
            		<a href="<?php echo $category->link; ?>" class="category"><?php echo $this->escape($category->title);?></a>
            		<span class="small">(<?php echo $category->numlinks;?>)</span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
    </div>
</div>
