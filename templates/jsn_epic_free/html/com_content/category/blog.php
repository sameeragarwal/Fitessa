<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$cparams =& JComponentHelper::getParams('com_media');
?>
<div class="com-content <?php echo $this->params->get('pageclass_sfx') ?>">
<div class="category-blog">
<?php if ($this->params->get('show_page_title')) : ?>
	<h2 class="componentheading">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h2>
<?php endif; ?>
<?php if ($this->params->get('show_description', 1) || $this->params->get('show_description_image', 1)) :?>
	<div class="contentdescription clearafter">
	<?php if ($this->params->get('show_description_image') && $this->category->image) : ?>
		<img src="<?php echo $this->baseurl . '/' . $cparams->get('image_path') . '/'. $this->category->image;?>" align="<?php echo $this->category->image_position;?>" hspace="6" alt="" />
	<?php endif; ?>
	<?php if ($this->params->get('show_description') && $this->category->description) : ?>
		<?php echo $this->category->description; ?>
	<?php endif; ?>
    </div>
<?php endif; ?>
	<div class="jsn-leading">
<?php if ($this->params->get('num_leading_articles')) : ?>
	<?php for ($i = $this->pagination->limitstart; $i < ($this->pagination->limitstart + $this->params->get('num_leading_articles')); $i++) : ?>
		<?php if ($i >= $this->total) : break; endif; ?>
		<?php
			$this->item =& $this->getItem($i, $this->params);
			echo $this->loadTemplate('item');
		?>
	<?php endfor; ?>
<?php else : $i = $this->pagination->limitstart; endif; ?>
	</div>
<?php
$startIntroArticles = $this->pagination->limitstart + $this->params->get('num_leading_articles');
$numIntroArticles = $startIntroArticles + $this->params->get('num_intro_articles');
if (($numIntroArticles != $startIntroArticles) && ($i < $this->total)) :
	$divider = '';
	if ($this->params->get('multi_column_order', 0)) : // order across, like front page
		for ($z = 0; $z < $this->params->get('num_columns', 2); $z ++) :
			if ($z > 0) : $divider = " column_separator"; endif;
			$rows = (int) ($this->params->get('num_intro_articles', 4) / $this->params->get('num_columns'));
			$cols = ($this->params->get('num_intro_articles', 4) % $this->params->get('num_columns'));
			?>
			<div class="jsn-articlecols" style="width:<?php echo intval(100 / $this->params->get('num_columns')); ?>%">
				<?php
				$loop = (($z < $cols)?1:0) + $rows;
				for ($y = 0; $y < $loop; $y ++) :
					$target = $i + ($y * $this->params->get('num_columns')) + $z;
					if ($target < $this->total && $target < ($numIntroArticles)) :
						$this->item =& $this->getItem($target, $this->params);
						echo $this->loadTemplate('item');
					endif;
				endfor;
				?>
			</div>
		<?php 
		endfor;
		$i = $i + $this->params->get('num_intro_articles') ; 
	else : // otherwise, order down, same as before (default behaviour)
		for ($z = 0; $z < $this->params->get('num_columns'); $z ++) :
			if ($z > 0) : $divider = " column_separator"; endif; ?>
			<div class="jsn-articlecols" style="width:<?php echo intval(100 / $this->params->get('num_columns')); ?>%">
				<?php
				for ($y = 0; $y < ($this->params->get('num_intro_articles') / $this->params->get('num_columns')); $y ++) :
					if ($i < $this->total && $i < ($numIntroArticles)) :
						$this->item =& $this->getItem($i, $this->params);
						echo $this->loadTemplate('item');
						$i ++;
					endif;
				endfor;
			?>
			</div>
		<?php 
		endfor;
	endif;
endif;
?>
	<div class="clearbreak"></div>
<?php if ($this->params->get('num_links') && ($i < $this->total)) : ?>
	<div class="blog_more clearafter">
		<?php
			$this->links = array_splice($this->items, $i - $this->pagination->limitstart);
			echo $this->loadTemplate('links');
		?>
	</div>
<?php endif; ?>
<?php if ($this->params->get('show_pagination', 2)) : ?>
	<div class="jsn-pagination-container"><?php echo $this->pagination->getPagesLinks(); ?></div>
<?php endif; ?>
<?php if ($this->params->get('show_pagination_results', 1)) : ?>
	<p class="jsn-pageinfo"><?php echo $this->pagination->getPagesCounter(); ?></p>
<?php endif; ?>
</div>
</div>