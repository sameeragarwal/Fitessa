<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
?>

<div id="jsnis-main-content">
	<form action="index.php?option=com_imageshow&controller=maintenance&type=lang" method="POST" name="adminForm" id="frm_lang">
		<div id="jsnis-languages">
			<p class="item-title"><?php echo JText::_('SELECT LANGUAGE TO BE INSTALLED'); ?></p>
			<?php
					$spanErrorOpen = '<span class="ins-lang-error">';
					$spanErrorClose = '</span>';
					$spanInstalledOpen = '<span class="ins-lang-installed">';
					$spanInstalledClose = '</span>';
					$langSite 	= ' ('.JText::_('SITE').')';
					$langAdmin 	= ' ('.JText::_('ADMINISTRATOR').')';
								
					foreach ($this->arrayFolder as $key=>$value)
					{
						if(isset($this->arrayFolder[$key]['site']) && $this->arrayFolder[$key]['site'] != 1){
							///$strErrorSite = $spanErrorOpen.' - '.JText::_('CAN NOT BE INSTALLED').$spanErrorClose.'. <strong><span class="editlinktip hasTip" title="::'.JText::_('This language not installation').'">[?]</span></strong>';
						//}else{
							if ($this->arrayFolder[$key]['site'] == 2){
								$strErrorSite = $spanErrorOpen.' - '.JText::_('CAN NOT BE INSTALLED').$spanErrorClose.'&nbsp; <strong><span class="editlinktip hasTip" title="::'.JText::sprintf('FOLDER %s MUST HAVE WRITABLE PERMISSION', 'language/'.$key).'">[?]</span></strong>';
							}elseif ($this->arrayFolder[$key]['site'] == 4){
								$strErrorSite = $spanErrorOpen.' - '.JText::_('NOT SUPPORTED YET').$spanErrorClose.'&nbsp; <a href="http://www.joomlashine.com/partnership.html" class="link-translate-lang" target="_blank">'.JText::_('TRANSLATE').'</a>';
							}elseif ($this->arrayFolder[$key]['site'] == 3){
								$strErrorSite = $spanInstalledOpen.' - '.JText::_('INSTALLED').$spanInstalledClose;
							}else{
								$strErrorSite='';
							}							
						}
						
						if(isset($this->arrayFolder[$key]['admin']) && $this->arrayFolder[$key]['admin'] != 1){
							//$strErrorAdmin = $spanErrorOpen.' - '.JText::_('CAN NOT BE INSTALLED').$spanErrorClose.'. <strong><span class="editlinktip hasTip" title="::'.JText::_('This language not installation').'">[?]</span></strong>';
						//}else{
							if ($this->arrayFolder[$key]['admin'] == 2){
								$strErrorAdmin = $spanErrorOpen.' - '.JText::_('CAN NOT BE INSTALLED').$spanErrorClose.'&nbsp; <strong><span class="editlinktip hasTip" title="::'.JText::sprintf('FOLDER %s MUST HAVE WRITABLE PERMISSION', 'administrator/language/'.$key).'">[?]</span></strong>';
							}elseif ( $this->arrayFolder[$key]['admin'] == 4){
								$strErrorAdmin = $spanErrorOpen.' - '.JText::_('NOT SUPPORTED YET').$spanErrorClose.'&nbsp; <a href="http://www.joomlashine.com/partnership.html" class="link-translate-lang" target="_blank">'.JText::_('TRANSLATE').'</a>';
							}elseif ( $this->arrayFolder[$key]['admin'] == 3){
								$strErrorAdmin = $spanInstalledOpen.' - '.JText::_('INSTALLED').$spanInstalledClose;
							}else{
								$strErrorAdmin='';
							}							
						}
				?>
			<?php if((isset($this->arrayFolder[$key]['admin']) && $this->arrayFolder[$key]['admin'] != 1) || (isset($this->arrayFolder[$key]['site']) && $this->arrayFolder[$key]['site'] != 1)){ ?>
			<div class="jsnis-language-item <?php echo $key; ?>">
				<?php } ?>
				<?php if(isset($this->arrayFolder[$key]['admin']) && $this->arrayFolder[$key]['admin'] != 1){ ?>
				<label>
					<input type="checkbox" name="<?php echo ($this->arrayFolder[$key]['admin'] == 2 || $this->arrayFolder[$key]['admin'] == 3 || $this->arrayFolder[$key]['admin'] == 4)?'':'lang_array_BO[]';?>" value="<?php echo $key; ?>" <?php echo (isset($this->arrayFolder[$key]['admin']) && $this->arrayFolder[$key]['admin'] == 5)?'':'disabled="disabled"';?> <?php echo (isset($this->arrayFolder[$key]['admin']) && $this->arrayFolder[$key]['admin'] == 3)?'checked="checked"':'';?>/>
					<span><?php echo $key.$langAdmin; ?></span><?php echo $strErrorAdmin; ?> </label>
				<?php } ?>
				<?php if(isset($this->arrayFolder[$key]['site']) && $this->arrayFolder[$key]['site'] != 1){ ?>
				<label>
					<input type="checkbox" name="<?php echo ($this->arrayFolder[$key]['site'] == 2 || $this->arrayFolder[$key]['site'] == 3 || $this->arrayFolder[$key]['site'] == 4 )?'':'lang_array_FO[]';?>" value="<?php echo $key; ?>" <?php echo (isset($this->arrayFolder[$key]['site']) && $this->arrayFolder[$key]['site'] == 5)?'':'disabled="disabled"';?> <?php echo (isset($this->arrayFolder[$key]['site']) && $this->arrayFolder[$key]['site'] == 3)?'checked="checked"':'';?> />
					<span><?php echo $key.$langSite; ?></span><?php echo $strErrorSite; ?> </label>
				<?php } ?>
				<?php if((isset($this->arrayFolder[$key]['admin']) && $this->arrayFolder[$key]['admin'] != 1) || (isset($this->arrayFolder[$key]['site']) && $this->arrayFolder[$key]['site'] != 1)){ ?>
			</div>
			<?php } ?>
			<?php } ?>
			<input type="hidden" name="option" value="com_imageshow" />
			<input type="hidden" name="controller" value="maintenance" />
			<input type="hidden" name="task" value="reinstalllang" />
			<?php echo JHTML::_( 'form.token' ); ?> </div>
		<div class="jsnis-button">
			<button type="submit" value="<?php echo JText::_('SAVE'); ?>"><?php echo JText::_('SAVE'); ?></button>
		</div>
	</form>
</div>
