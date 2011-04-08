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
<script>
function submitform()
{
	document.getElementById('frm_themes').submit();
} 
</script>

<div id="jsnis-main-content">
	<div id="jsnis-themes-manager">
		<form action="index.php?option=com_imageshow&controller=maintenance&type=themes" method="POST" name="adminForm" id="frm_themes">
			<table border="0" width="100%">
				<tr>
					<td align="left" width="100%"><?php echo JText::_('TITLE'); ?> :
						<input type="text" name="plugin_name" id="plugin_name" value="<?php echo $this->filterPluginName; ?>" class="text_area"/>&nbsp;
						<button onclick="this.form.submit();"><?php echo JText::_('GO'); ?></button>
						<button onclick="document.getElementById('filter_state').selectedIndex=0;document.getElementById('plugin_name').value=''; this.form.submit();"><?php echo JText::_('RESET'); ?></button>
					</td>
				</tr>
			</table>
			<table class="adminlist" border="0">
				<thead>
					<tr>
						<th width="20" style="display:none;"> <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->listJSNPlugins);?>);" /> </th>
						<th class="title" nowrap="nowrap" width="70%"> <?php echo JText::_('THEME NAME'); ?> </th>
						<th width="15%"> <?php echo JText::_('THEME VERSION'); ?> </th>
						<th width="6%" nowrap="nowrap"> <?php echo JText::_('ACTION'); ?> </th>
					</tr>
				</thead>
				<tbody>
					<?php
				$objJSNPlugins = JSNISFactory::getObj('classes.jsn_is_plugins');
				$k 	= 0;
				$n	= count($this->listJSNPlugins);
				for ($i=0 ; $i < $n; $i++)
				{
					$row 		= &$this->listJSNPlugins[$i];
					$checked 	= JHTML::_('grid.checkedout', $row, $i);
					$infoXML	= $objJSNPlugins->getXmlFile($row);						
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td><?php echo $this->escape($row->name); ?></td>
						<td align="center"><?php
								if (isset($infoXML->version))
								{
									echo $infoXML->version;
								}
							?></td>
						<td align="center" class="actionprofile"><?php 
						if($n > 1)
						{
						?>
							<a href="<?php echo JRoute::_('index.php?option=com_imageshow&controller=maintenance&type=themes&task=deleteTheme&themeID='.$row->id);?>" class="action-delete"> </a>
							<?php
						}
						else 
						{
						?>
							<a class="action-delete" title="<?php echo JText::_('YOU CAN NOT DELETE THE ONLY THEME IN THE LIST'); ?>"></a>
							<?php }?></td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" align="center"><?php echo $this->pagination->getListFooter(); ?></td>
					</tr>
				</tfoot>
			</table>
			<input type="hidden" name="option" value="com_imageshow" />
			<input type="hidden" name="controller" value="maintenance" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
			<?php echo JHTML::_('form.token'); ?>
		</form>
		<form enctype="multipart/form-data" action="index.php?option=com_imageshow&controller=maintenance&task=installPluginManager" method="post" name="installForm">
			<table class="adminform">
				<tr>
					<th colspan="2"><?php echo JText::_('INSTALL NEW THEME'); ?></th>
				</tr>
				<tr>
					<td width="120"><label for="install_package"><?php echo JText::_('PACKAGE FILE'); ?>:</label></td>
					<td><input class="input_box" id="install_package" name="install_package" type="file" size="57" />
						<input class="button" type="submit" value="<?php echo JText::_('UPLOAD FILE'); ?> &amp; <?php echo JText::_('INSTALL'); ?>" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="redirect_link" value="<?php echo 'index.php?option=com_imageshow&controller=maintenance&type=themes';?>"/>
			<input type="hidden" name="controller" value="maintenance" />
			<input type="hidden" name="type" value="" />
			<input type="hidden" name="installtype" value="upload" />
			<input type="hidden" name="task" value="installPluginManager" />
			<input type="hidden" name="option" value="com_imageshow" />
			<?php echo JHTML::_('form.token'); ?>
		</form>
	</div>
</div>
