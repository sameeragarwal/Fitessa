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

$user 	=& JFactory::getUser();
$db 	= & JFactory::getDBO();
//Ordering allowed ?
$ordering = ($this->moduleData->lists['order'] == 'm.ordering' || $this->moduleData->lists['order'] == 'm.position');

JHTML::_('behavior.tooltip');
?>
<form action="index.php?option=com_imageshow&controller=showlist&task=modules&tmpl=component" method="post" name="adminForm">
	<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_('FILTER'); ?>:
			<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->moduleData->lists['search']);?>" class="text_area" />
			<button onclick="this.form.submit();"><?php echo JText::_('GO'); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_('RESET'); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php
			echo $this->moduleData->lists['assigned'];
			echo $this->moduleData->lists['position'];
			echo $this->moduleData->lists['type'];
			echo $this->moduleData->lists['state'];
			?>
		</td>
	</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
	<tr>
		<th width="20">
			<?php echo JText::_('NUM'); ?>
		</th>
		<th width="20" style="display:none;">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->moduleData->rows );?>);" />
		</th>
		<th class="title">
			<?php echo JHTML::_('grid.sort', 'Module Name', 'm.title', @$this->moduleData->lists['order_Dir'], @$this->moduleData->lists['order'] ); ?>
		</th>
		<th nowrap="nowrap" width="7%">
			<?php echo JHTML::_('grid.sort', 'Published', 'm.published', @$this->moduleData->lists['order_Dir'], @$this->moduleData->lists['order'] ); ?>
		</th>
		
		<th nowrap="nowrap" width="7%">
			<?php echo JHTML::_('grid.sort',   'Position', 'm.position', @$this->moduleData->lists['order_Dir'], @$this->moduleData->lists['order'] ); ?>
		</th>
		<th nowrap="nowrap" width="5%">
			<?php echo JHTML::_('grid.sort',   'Pages', 'pages', @$this->moduleData->lists['order_Dir'], @$this->moduleData->lists['order'] ); ?>
		</th>
		<th nowrap="nowrap" width="10%"  class="title">
			<?php echo JHTML::_('grid.sort',   'Type', 'm.module', @$this->moduleData->lists['order_Dir'], @$this->moduleData->lists['order'] ); ?>
		</th>
		<th nowrap="nowrap" width="1%">
			<?php echo JHTML::_('grid.sort',   'ID', 'm.id', @$this->moduleData->lists['order_Dir'], @$this->moduleData->lists['order'] ); ?>
		</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<td colspan="12">
			<?php echo $this->moduleData->pageNav->getListFooter(); ?>
		</td>
	</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->moduleData->rows ); $i < $n; $i++) {
		$row 		= &$this->moduleData->rows[$i];
		$access 	= JHTML::_('grid.access',   $row, $i );
		$checked 	= JHTML::_('grid.checkedout',   $row, $i );
		$published 	= JHTML::_('grid.published', $row, $i );
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="right">
				<?php echo $this->moduleData->pageNav->getRowOffset( $i ); ?>
			</td>
			<td width="20" style="display:none;">
				<?php echo $checked; ?>
			</td>
			<td>
			<?php
			if (  JTable::isCheckedOut($user->get ('id'), $row->checked_out ) ) {
				echo htmlspecialchars($row->title);
			} else {
				?>
				<span class="editlinktip hasTip" title="<?php echo JText::_('EDIT MODULE');?>::<?php echo htmlspecialchars($row->title); ?>">
				<span href="#" onclick="getModuleID('<?php echo $row->id;?>', <?php echo $db->quote($row->title); ?>,'<?php echo JRequest::getVar('object'); ?>');">
					<?php echo htmlspecialchars($row->title); ?></span>
				</span>
				<?php
			}
			?>
			</td>
			<td align="center">
				<?php echo ($row->published == 1) ? JText::_('PUBLISHED') : JText::_('UNPUBLISHED'); ?>
			</td>
			<td align="center">
				<?php echo $row->position; ?>
			</td>
			<td align="center">
				<?php
				if (is_null( $row->pages )) {
					echo JText::_('NONE');
				} else if ($row->pages > 0) {
					echo JText::_('VARIES');
				} else {
					echo JText::_('ALL');
				}
				?>
			</td>
			<td>
				<?php echo $row->module ? $row->module : JText::_('USER');?>
			</td>
			<td>
				<?php echo $row->id;?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>

<input type="hidden" name="option" value="com_imageshow" />
<input type="hidden" name="client" value="<?php echo $this->moduleData->client->id;?>" />
<input type="hidden" name="controller" value="showlist"/>
<input type="hidden" name="task" value="modules" />
<input type="hidden" name="object" value="<?php echo JRequest::getVar('object'); ?>"
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->moduleData->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->moduleData->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php
