<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form action="<?php echo JRoute::_('index.php?view=category&id='.$this->category->slug); ?>" method="post" name="adminForm">
<?php if ($this->params->get('show_limit')) : ?>
<div class="jsn-infofilter">
	<?php
		echo JText::_('Display Num') .'&nbsp;';
		echo $this->pagination->getLimitBox();
	?>
</div>
<?php endif; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="jsn-infotable">
<?php if ( $this->params->get( 'show_headings' ) ) : ?>
<tr class="jsn-table-header">
	<td class="sectiontableheader" align="right" width="5">
		<?php echo JText::_('Num'); ?>
	</td>
	<?php if ( $this->params->get( 'show_name' ) ) : ?>
	<td height="20" width="90%" class="sectiontableheader">
		<?php echo JText::_( 'Feed Name' ); ?>
	</td>
	<?php endif; ?>
	<?php if ( $this->params->get( 'show_articles' ) ) : ?>
	<td height="20" width="10%" class="sectiontableheader" align="center" nowrap="nowrap">
		<?php echo JText::_( 'Num Articles' ); ?>
	</td>
	<?php endif; ?>
 </tr>
<?php endif; ?>
<?php foreach ($this->items as $item) : ?>
<tr class="sectiontableentry<?php echo $item->odd + 1; ?>">
	<td align="right" width="5">
		<?php echo $item->count + 1; ?>
	</td>
	<td height="20" width="90%">
		<a href="<?php echo $item->link; ?>" class="category">
			<?php echo $item->name; ?></a>
	</td>
	<?php if ( $this->params->get( 'show_articles' ) ) : ?>
	<td height="20" width="10%" align="center">
		<?php echo $item->numarticles; ?>
	</td>
	<?php endif; ?>
</tr>
<?php endforeach; ?>
</table>
	<div class="jsn-pagination-container"><?php echo $this->pagination->getPagesLinks(); ?></div>
	<p class="jsn-pageinfo"><?php echo $this->pagination->getPagesCounter(); ?></p>
</form>