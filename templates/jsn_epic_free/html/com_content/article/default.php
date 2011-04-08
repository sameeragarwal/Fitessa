<?php // no direct access
defined('_JEXEC') or die('Restricted access');
// Include JSN Utils
$app = & JFactory::getApplication();
$template = $app->getTemplate();
require_once JPATH_THEMES.'/'.$template.'/php/lib/jsn_utils.php';
$jsnutils = &JSNUtils::getInstance();
$canEdit = ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own'));
$showSection = ($this->params->get('show_section') && $this->article->sectionid && isset($this->article->section));
$showCategory = ($this->params->get('show_category') && $this->article->catid);
$showURL = ($this->params->get('show_url') && $this->article->urls);
$showInfo = (($this->params->get('show_author') && $this->article->author != "") || $this->params->get('show_create_date'));
$showTools = ($this->print || $canEdit || ($this->params->get('show_pdf_icon') || $this->params->get( 'show_print_icon' ) || $this->params->get('show_email_icon')));
?>
<div class="com-content <?php echo $this->params->get('pageclass_sfx') ?>">
<div class="article">
<?php if ($this->params->get('show_page_title', 1) && $this->params->get('page_title') != $this->article->title) : ?>
	<h2 class="componentheading">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</h2>
<?php endif; ?>
<?php if ($showSection || $showCategory || $showURL) : ?>
	<div class="jsn-article-metadata">
	<?php if ($showSection) : ?>
		<span>
			<?php if ($this->params->get('link_section')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getSectionRoute($this->article->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->article->section; ?>
			<?php if ($this->params->get('link_section')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
			<?php if ($this->params->get('show_category') && $this->article->catid) : ?>
				<?php echo ' - '; ?>
			<?php endif; ?>
		</span>
	<?php endif; ?>
	<?php if ($showCategory) : ?>
		<span>
			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->article->catslug, $this->article->sectionid)).'">'; ?>
			<?php endif; ?>
			<?php echo $this->article->category; ?>
			<?php if ($this->params->get('link_category')) : ?>
				<?php echo '</a>'; ?>
			<?php endif; ?>
			<?php if ($this->params->get('show_url') && $this->article->urls) : ?>
				<?php echo ' - '; ?>
			<?php endif; ?>
		</span>
	<?php endif; ?>
	<?php if ($showURL) : ?>
		<a href="http://<?php echo $this->article->urls ; ?>" target="_blank">
			<?php echo $this->article->urls; ?>
		</a>
	<?php endif; ?>
	</div>
<?php endif; ?>
<?php if ($this->params->get('show_title')) : ?>
	<h2 class="contentheading">
	<?php if ($this->params->get('link_titles') && $this->article->readmore_link != '') : ?>
		<a href="<?php echo $this->article->readmore_link; ?>" class="contentpagetitle"><?php echo $this->escape($this->article->title); ?></a>
	<?php else : ?>
		<?php echo $this->escape($this->article->title); ?>
	<?php endif; ?>
	</h2>
<?php endif; ?>
<?php  if (!$this->params->get('show_intro')) :
	echo $this->article->event->afterDisplayTitle;
	endif;
?>
<?php if ($showInfo || $showTools) : ?>
	<div class="jsn-article-toolbar">
		<?php if ($showTools) : ?>
		<ul class="jsn-article-tools">
			<?php if (!$this->print) : ?>
			<?php if ($this->params->get('show_pdf_icon')) : ?>
				<li><?php echo $jsnutils->articleTools('pdf', $this->article, $this->params, $this->access); ?></li>
			<?php endif; ?>
			<?php if ($this->params->get( 'show_print_icon' )) : ?>
				<li><?php echo $jsnutils->articleTools('print_popup', $this->article, $this->params, $this->access); ?></li>
			<?php endif; ?>
			<?php if ($this->params->get('show_email_icon')) : ?>
				<li><?php echo $jsnutils->articleTools('email', $this->article, $this->params, $this->access); ?></li>
			<?php endif; ?>
			<?php if ($canEdit) : ?>
				<li class="jsn-article-icon-edit"><?php echo JHTML::_('icon.edit', $this->article, $this->params, $this->access); ?></li>
			<?php endif; ?>
			<?php else : ?>
			<li><?php echo $jsnutils->articleTools('print_screen', $this->article, $this->params, $this->access); ?></li>
			<?php endif; ?>
		</ul>
		<?php endif; ?>
		<?php if ($showInfo) : ?>
		<div class="jsn-article-info">
			<?php if ($this->params->get('show_author') && $this->article->author != "") : ?>
			<p class="small author">
				<?php JText::printf( 'Written by', ($this->article->created_by_alias ? $this->article->created_by_alias : $this->article->author) ); ?>
			</p>
			<?php endif; ?>
			<?php if ($this->params->get('show_create_date')) : ?>
			<p class="createdate">
				<?php echo JHTML::_('date', $this->article->created, JText::_('DATE_FORMAT_LC2')) ?>
			</p>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
<?php endif; ?>
<?php echo $this->article->event->beforeDisplayContent; ?>
	<div class="jsn-article-content">
	<?php if (isset ($this->article->toc)) : ?>
			<?php echo $this->article->toc; ?>
	<?php endif; ?>
	<?php echo $this->article->text; ?>
	<?php if ( intval($this->article->modified) != 0 && $this->params->get('show_modify_date')) : ?>
		<p class="modifydate">
			<?php echo JText::sprintf('LAST_UPDATED2', JHTML::_('date', $this->article->modified, JText::_('DATE_FORMAT_LC2'))); ?>
		</p>
	<?php endif; ?>
	</div>
	<span class="article_separator">&nbsp;</span>
<?php echo $this->article->event->afterDisplayContent; ?>
</div>
</div>