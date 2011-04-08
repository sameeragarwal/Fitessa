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
<script language="javascript">
	function backup(){
		document.getElementById('frm_backup').submit();
	}
	function restore(){
		if (document.getElementById('file-upload').value == ""){
			alert( "<?php echo JText::_('YOU MUST SELECT A FILE BEFORE IMPORTING', true); ?>" );
			return false;
		}else {
			document.getElementById('frm_restore').submit();
		}		
	}	
</script>
<?php $pane =& JPane::getInstance('tabs',array('startOffset'=>0)); ?>

<div id="jsnis-main-content">
	<div id="jsn-data-maintenance"> <?php echo $pane->startPane('pane'); ?> <?php echo $pane->startPanel( JText::_('DATA BACKUP'), 'panel1' ); ?>
		<form action="index.php?option=com_imageshow&controller=maintenance" method="POST" name="adminForm" id="frm_backup">
			<div id="jsnis-data-backup">
				<p class="item-title"><?php echo JText::_('BACKUP OPTIONS'); ?>:</p>
				<p>
					<input type="checkbox" name="showlists"  id="showlist" value="1" />
					<label for="showlist"><?php echo JText::_('BACKUP SHOWLISTS'); ?></label>
				</p>
				<p>
					<input type="checkbox" name="showcases" id="showcases" value="1" />
					<label for="showcases"><?php echo JText::_('BACKUP SHOWCASES'); ?></label>
				</p>
				<p class="item-title"><?php echo JText::_('BACKUP FILENAME'); ?>:</p>
				<p>
					<input type="text" id="filename" name="filename" />
				</p>
				<p>
					<input type="checkbox" name="timestamp" id="timestamp" value="1" />
					<label for="timestamp"><?php echo JText::_('ATTACH TIMESTAMP TO FILENAME'); ?></label>
				</p>
				<input type="hidden" name="option" value="com_imageshow" />
				<input type="hidden" name="controller" value="maintenance" />
				<input type="hidden" name="task" value="backup" />
				<?php echo JHTML::_( 'form.token' ); ?> </div>
			<div class="jsnis-button">
				<button type="button" value="<?php echo JText::_('BACKUP');?>" onclick="backup();"><?php echo JText::_('BACKUP');?></button>
			</div>
		</form>
		<?php echo $pane->endPanel(); ?> <?php echo $pane->startPanel( JText::_('DATA RESTORE'), 'panel2' ); ?>
		<form action="index.php?option=com_imageshow&controller=maintenance" method="POST" name="adminForm" enctype="multipart/form-data" id="frm_restore">
			<div id="jsnis-data-restore">
				<p class="item-title"><?php echo JText::_('BACKUP FILE'); ?>:</p>
				<p>
					<input type="file" id="file-upload" name="filedata" size="100" />
				</p>
				<input type="hidden" name="option" value="com_imageshow" />
				<input type="hidden" name="controller" value="maintenance" />
				<input type="hidden" name="task" value="restore" />
				<?php echo JHTML::_( 'form.token' ); ?> </div>
			<div class="jsnis-button">
				<button type="button" value="<?php echo JText::_('RESTORE'); ?>" onclick="return restore();"><?php echo JText::_('RESTORE'); ?></button>
			</div>
		</form>
		<?php echo $pane->endPanel(); ?> <?php echo $pane->endPane(); ?> </div>
</div>
