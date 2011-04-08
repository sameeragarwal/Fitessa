<?php // no direct acces
defined('_JEXEC') or die('Restricted access'); ?>
<?php
		$lang = &JFactory::getLanguage();
		$myrtl =$this->newsfeed->rtl;
		 if ($lang->isRTL() && $myrtl==0){
		   $direction= "direction:rtl !important;";
		   $align= "text-align:right !important;";
		   }
		 else if ($lang->isRTL() && $myrtl==1){
		   $direction= "direction:ltr !important;";
		   $align= "text-align:left !important;";
		   }
		  else if ($lang->isRTL() && $myrtl==2){
		   $direction= "direction:rtl !important;";
		   $align= "text-align:right !important;";
		   }

		else if ($myrtl==0) {
		$direction= "direction:ltr !important;";
		   $align= "text-align:left !important;";
		   }
		else if ($myrtl==1) {
		$direction= "direction:ltr !important;";
		   $align= "text-align:left !important;";
		   }
		else if ($myrtl==2) {
		   $direction= "direction:rtl !important;";
		   $align= "text-align:right !important;";
		   }

?>
<div class="com-newsfeed <?php echo $this->params->get( 'pageclass_sfx' ); ?>" style="<?php echo $direction; ?><?php echo $align; ?>">
<div class="news-feed">
<?php if ($this->params->get('show_page_title', 1)) : ?>
	<h2 class="componentheading" style="<?php echo $direction; ?><?php echo $align; ?>"><?php echo $this->escape($this->params->get('page_title')); ?></h2>
<?php endif; ?>
	<h2 class="componentheading" style="<?php echo $direction; ?><?php echo $align; ?>">
		<a href="<?php echo $this->newsfeed->channel['link']; ?>" target="_blank">
			<?php echo str_replace('&apos;', "'", $this->newsfeed->channel['title']); ?>
        </a>
    </h2>
	<?php if ( $this->params->get( 'show_feed_description' ) ) : ?>
    	<div class="contentdescription clearafter">
			<?php echo str_replace('&apos;', "'", $this->newsfeed->channel['description']); ?>
        </div>
	<?php endif; ?>
	<?php if ( isset($this->newsfeed->image['url']) && isset($this->newsfeed->image['title']) && $this->params->get( 'show_feed_image' ) ) : ?>
    	<img src="<?php echo $this->newsfeed->image['url']; ?>" alt="<?php echo $this->newsfeed->image['title']; ?>" />
	<?php endif; ?>
	<ul>
		<?php foreach ( $this->newsfeed->items as $item ) :  ?>
			<li>
			<?php if ( !is_null( $item->get_link() ) ) : ?>
				<a href="<?php echo $item->get_link(); ?>" target="_blank">
					<?php echo $item->get_title(); ?></a>
			<?php endif; ?>
			<?php if ( $this->params->get( 'show_item_description' ) && $item->get_description()) : ?>
				<br />
				<?php $text = $this->limitText($item->get_description(), $this->params->get( 'feed_word_count' ));
					echo str_replace('&apos;', "'", $text);
				?>
				<br />
				<br />
			<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div></div>
