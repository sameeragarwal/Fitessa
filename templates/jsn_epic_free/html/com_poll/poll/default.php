<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>

<?php JHTML::_('stylesheet', 'poll_bars.css', 'components/com_poll/assets/'); ?>
<div class="com-poll <?php echo $this->params->get('pageclass_sfx') ?>">
<form action="index.php" method="post" name="poll" id="poll">
<?php if ($this->params->get( 'show_page_title', 1)) : ?>
<h2 class="componentheading">
	<?php echo $this->escape($this->params->get('page_title')); ?>
</h2>
<?php endif; ?>
<div class="jsn-poll-selection">
	<label for="id">
		<?php echo JText::_('Select Poll'); ?>
		<?php echo $this->lists['polls']; ?>
	</label>
</div>
	<?php echo $this->loadTemplate('graph'); ?>
</form>
</div>