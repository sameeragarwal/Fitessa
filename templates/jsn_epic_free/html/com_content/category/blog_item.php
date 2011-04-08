<?php // no direct access
defined('_JEXEC') or die('Restricted access');
// Include JSN Utils
$app = & JFactory::getApplication();
$template = $app->getTemplate();
require_once JPATH_THEMES.'/'.$template.'/php/lib/jsn_utils.php';
$jsnutils = &JSNUtils::getInstance();
$canEdit = ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
$showSection = ($this->item->params->get('show_section') && $this->item->sectionid && isset($this->item->section));
$showCategory = ($this->item->params->get('show_category') && $this->item->catid);
$showURL = ($this->item->params->get('show_url') && $this->item->urls);
$showInfo = (($this->item->params->get('show_author') && $this->item->author != "") || $this->item->params->get('show_create_date'));
$showTools = ($canEdit || ($this->item->params->get('show_pdf_icon') || $this->item->params->get( 'show_print_icon' ) || $this->item->params->get('show_email_icon')));
?>
<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?>
<div class="jsn-article">
<?php if ($showSection || $showCategory || $showURL) : ?>
	<div class="jsn-article-metadata">
	<?php if ($showSection) : ?>
		<span>
			<?php if ($this->item->params->get('link_section')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->item->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->item->section; ?>
			<?php if ($this->item->params->get('link_section')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
			<?php if ($this->item->params->get('show_category')) : ?>
				<?php echo ' - '; ?>
			<?php endif; ?>
		</span>
	<?php endif; ?>
	<?php if ($showCategory) : ?>
		<span>
			<?php if ($this->item->params->get('link_category')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug, $this->item->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->item->category; ?>
			<?php if ($this->item->params->get('link_category')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
		</span>
	<?php endif; ?>
	<?php if ($showURL) : ?>
		<a href="http://<?php echo $this->item->urls ; ?>" target="_blank"><?php echo $this->item->urls; ?></a>
	<?php endif; ?>
	</div>
<?php endif; ?>
<?php if ($this->item->params->get('show_title')) : ?>
	<h2 class="contentheading">
		<?php
		if ($this->item->params->get('link_titles') && $this->item->readmore_link != '') : ?>
			<a href="<?php echo $this->item->readmore_link; ?>" class="contentpagetitle">
			<?php echo $this->escape($this->item->title) ?></a>
			<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
	</h2>
<?php endif; ?>
<?php  if (!$this->item->params->get('show_intro')) : echo $this->item->event->afterDisplayTitle; endif; ?>
<?php if ($showInfo || $showTools) : ?>
	<div class="jsn-article-toolbar">
		<?php if ($showTools) : ?>
		<ul class="jsn-article-tools">
			<?php if ($this->params->get('show_pdf_icon')) : ?>
				<li><?php echo $jsnutils->articleTools('pdf', $this->item, $this->item->params, $this->access); ?></li>
			<?php endif; ?>
			<?php if ($this->params->get( 'show_print_icon' )) : ?>
				<li><?php echo $jsnutils->articleTools('print_popup', $this->item, $this->item->params, $this->access); ?></li>
			<?php endif; ?>
			<?php if ($this->params->get('show_email_icon')) : ?>
				<li><?php echo $jsnutils->articleTools('email', $this->item, $this->item->params, $this->access); ?></li>
			<?php endif; ?>
			<?php if ($canEdit) : ?>
				<li class="jsn-article-icon-edit"><?php echo JHTML::_('icon.edit', $this->item, $this->item->params, $this->access); ?></li>
			<?php endif; ?>
		</ul>
		<?php endif; ?>
		<?php if ($showInfo) : ?>
		<div class="jsn-article-info">
			<?php if (($this->item->params->get('show_author')) && ($this->item->author != "")) : ?>
			<p class="small author">
				<?php JText::printf( 'Written by', ($this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author) ); ?>
			</p>
			<?php endif; ?>
			<?php if ($this->item->params->get('show_create_date')) : ?>
			<p class="createdate">
				<?php echo JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2')); ?>
			</p>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
<?php endif; ?>
<?php echo $this->item->event->beforeDisplayContent; ?>
<?php if (isset ($this->item->toc)) : ?>
	<?php echo $this->item->toc; ?>
<?php endif; ?>
<?php echo $this->item->text; ?>
<?php if ( intval($this->item->modified) != 0 && $this->item->params->get('show_modify_date')) : ?>
	<p class="modifydate"><?php echo JText::sprintf('LAST_UPDATED2', JHTML::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?></p>
<?php endif; ?>
<?php if ($this->item->params->get('show_readmore') && $this->item->readmore) : ?>
	<p class="readmore">
    	<a href="<?php echo $this->item->readmore_link; ?>" class="readon">
			<span>
				<?php if ($this->item->readmore_register) :
					echo JText::_('Register to read more...');
				elseif ($readmore = $this->item->params->get('readmore')) :
					echo $readmore;
				else :
					echo JText::sprintf('Read more...');
				endif; ?>
        	</span>
		</a>
	</p>
<?php endif; ?>
</div>
<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>
<span class="article_separator">&nbsp;</span>
<?php echo $this->item->event->afterDisplayContent; ?>