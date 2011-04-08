<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="com-weblink <?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<div class="web-link-category-list">
	<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
		<h2 class="componentheading">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h2>
	<?php endif; ?>
	<?php if ( ($this->params->def('image', -1) != -1) || $this->params->def('show_comp_description', 1) ) : ?>
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
           		<a href="<?php echo $category->link; ?>" class="category"><?php echo $this->escape($category->title);?></a>
            	<span class="small">(<?php echo $category->numlinks;?>)</span>
			</li>
		<?php endforeach; ?>
	</ul>
    </div>
</div>
