<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jsn-mod-newsflash jsn-horizontal-container">
	<?php $column_width = 99.9/count($list); ?>
	<?php foreach ($list as $item) : ?>
		<div class="jsn-article-container" style="float: left; width: <?php echo $column_width;?>%;">
			<?php modNewsFlashHelper::renderItem($item, $params, $access); ?>
		</div>
	<?php endforeach; ?>
	<div style="clear: left"></div>
</div>