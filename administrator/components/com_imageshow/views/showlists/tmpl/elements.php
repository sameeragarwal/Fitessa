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

<form action="index.php?option=com_imageshow&controller=showlist&task=elements&tmpl=component" method="post" name="adminForm" id="adminForm">
<div id="jsnis-image-source-profile-details">
<h3 class="jsnis-element-heading"><?php echo JText::_('IMAGE SOURCE PROFILE SHOWLISTS'); ?></h3>
<table class="adminlist">
	<thead>
		<tr>
			<th width="10">#</th>
			<th width="75%">
				<?php echo JText::_('TITLE'); ?>		
			</th>
			<th width="20" nowrap="nowrap">
				<?php echo JText::_('HITS'); ?>
			</th>
			<th width="20" nowrap="nowrap">
				<?php echo JText::_('ID'); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$k 		= 0;	
	for ($i=0, $n=count( $this->items ); $i < $n; $i++){
		$row 			= &$this->items[$i];				
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			
			<td>				
				<?php echo $this->escape($row->showlist_title); ?>		
			</td>
			<td align="center"><?php echo $row->hits;?></td>
			<td align="center"><?php echo $row->showlist_id;?></td>
		</tr>
	<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
</table>
</div>
<input type="hidden" name="option" value="com_imageshow" />
<input type="hidden" name="task" value="elements" />
<input type="hidden" name="controller" value="showlist" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>