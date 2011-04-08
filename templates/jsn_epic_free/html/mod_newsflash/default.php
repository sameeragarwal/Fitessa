<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php
srand((double) microtime() * 1000000);
$flashnum	= rand(0, $items -1);
$item		= $list[$flashnum];
?>
<div class="jsn-mod-newsflash">
<?php
modNewsFlashHelper::renderItem($item, $params, $access);
?>
</div>