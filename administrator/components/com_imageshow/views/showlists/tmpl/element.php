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

<form action="index.php?option=com_imageshow&controller=showlist&task=element&tmpl=component" method="post" name="adminForm" id="adminForm">
<div id="jsnis-image-source-profile-details">
	<h3 class="jsnis-element-heading"><?php echo JText::_('IMAGE SOURCE PROFILE DELETION'); ?></h3>
	<p><?php echo JText::_('FOLLOWING SHOWLISTS WILL BE RESET'); ?>:</p>
	<ul>
		<?php
			$k 		= 0;	
			for ($i=0, $n=count( $this->items ); $i < $n; $i++){
			$row 			= &$this->items[$i];	
			//$checked 		= JHTML::_('grid.id', $i, $row->showlist_id );		
		?>
		<li>
			<?php echo $this->escape($row->showlist_title); ?>		
		</li>
		<?php
			$k = 1 - $k;
		}
		?>
    </ul>
	<p><?php echo JText::_('ARE YOU SURE YOU WANT TO DELETE THIS IMAGE SOURCE PROFILE'); ?></p>
	<div class="button">
        	<button type="button" value="<?php echo JText::_('DELETE'); ?>" onclick="JSNISImageShow.ProfileDelete();"><?php echo JText::_('DELETE'); ?></button>
            <button type="button" value="<?php echo JText::_('CANCEL');?>" onclick="window.top.setTimeout('window.parent.document.getElementById(\'sbox-window\').close()', 200);"><?php echo JText::_('CANCEL');?></button>
	</div>
</div>
<input type="hidden" name="configuration_id" value="<?php echo JRequest::getInt('configuration_id'); ?>" />
<input type="hidden" name="option" value="com_imageshow" />
<input type="hidden" name="task" id="task" value="element" />
<input type="hidden" name="controller" id="controller" value="showlist" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>