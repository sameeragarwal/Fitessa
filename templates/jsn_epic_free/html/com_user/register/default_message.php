<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h2 class="componentheading">
	<?php echo $this->escape($this->message->title) ; ?>
</h2>

<div class="message">
	<?php echo $this->escape($this->message->text) ; ?>
</div>
