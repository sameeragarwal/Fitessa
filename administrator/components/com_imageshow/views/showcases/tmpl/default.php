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
JHTML::_('behavior.tooltip');
JToolBarHelper::title( JText::_('JSN IMAGESHOW').': '.JText::_('SHOWCASES MANAGER'), 'showcase' );
JToolBarHelper::publishList();
JToolBarHelper::unpublishList();
JToolBarHelper::divider();
JToolBarHelper::deleteList();
JToolBarHelper::editList();
JToolBarHelper::addNew();
JToolBarHelper::custom( 'copy', 'copy.png', 'copy_f2.png', 'Copy', true );
JToolBarHelper::divider();
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$ordering	= true;
//echo $ordering;
?>
<?php
	$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
	echo $objJSNMsg->displayMessage('SHOWCASES');
?>
<form action="index.php?option=com_imageshow&controller=showcase" method="post" name="adminForm">
<table border="0">

	<tr>
		<td align="left" width="100%">
			<?php echo JText::_('FILTER'); ?> :
			<input type="text" name="showcase_title" id="showcase_title" value="<?php echo $this->lists['showcaseTitle'];?>" class="text_area"/>&nbsp;
			<button onclick="this.form.submit();"><?php echo JText::_('GO'); ?></button>
			<button onclick="document.getElementById('filter_state').value=''; document.getElementById('showcase_title').value=''; this.form.submit();"><?php echo JText::_('RESET'); ?></button>
		</td>
	 	<td>
	 		<?php echo $this->lists['state'];?>
	 	</td>
	 
	</tr>
</table>
<table class="adminlist" border="0">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_('NUM'); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th class="title" nowrap="nowrap" width="80%">			
				<?php echo JHTML::_('grid.sort',  JText::_('TITLE'), 'showcase_title', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
			</th>
			<th class="title" nowrap="nowrap" width="5%">
				<?php echo JText::_('Published'); ?>
			</th>			
			<th width="100" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Order', 'ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				<?php 
					//echo JHTML::_('grid.order',  $this->items );
					if ($ordering) echo JHTML::_('grid.order',  $this->items ); 
				?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JText::_('ID'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="7" align="center">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row 			= &$this->items[$i];
		$link 			= JRoute::_( 'index.php?option=com_imageshow&controller=showcase&task=edit&cid[]='. $row->showcase_id );		
		$checked 		= JHTML::_('grid.id', $i, $row->showcase_id );
		$published 		= JHTML::_('grid.published', $row, $i );
		
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<span class="editlinktip hasTip" title="<?php echo JText::_('Edit Showcase');?>::<?php echo $this->escape($row->showcase_title); ?>">
					<a href="<?php echo $link; ?>">
					<?php echo $this->escape($row->showcase_title); ?>
					</a>
				</span>
			</td>
			<td align="center">			
				<?php
					echo $published; 
				?>
			</td>			
			<td class="list-order" align="center">
				<p>
                	<span><?php echo $this->pagination->orderUpIcon( $i, true, 'orderup', 'Move Up', $ordering ); ?></span>
					<span><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'orderdown', 'Move Down', $ordering ); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
				</p>
            </td>
			<td align="center">
				<?php
					echo $row->showcase_id; 
				?>
			</td>		
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>
	<table class="note">
		<tbody>
			<tr>
				<td><?php echo JHTML::_( 'image.administrator', 'images/tick.png','');  ?></td>
				<td><?php echo JText::_('PUBLISHED');?><span class="line"> | </span></td>
				<td><?php echo JHTML::_( 'image.administrator', 'images/publish_x.png','');  ?></td>
				<td><?php echo JText::_('UNPUBLISHED');?><span class="line"></span></td>
			</tr>
			<tr>
				<td colspan="4" align="center"><?php echo JText::_('CLICK ON ICON TO TOGGLE STATE');?></td>
			</tr>
		</tbody>
	</table>	
	<input type="hidden" name="option" value="com_imageshow" />
	<input type="hidden" name="controller" value="showcase" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php'); ?>