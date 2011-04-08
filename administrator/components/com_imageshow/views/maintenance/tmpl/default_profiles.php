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
<script type="text/javascript">
	window.addEvent('domready', function() {
		$$('a.actionprofile-delete').each(function(el) {
			el.addEvent('click', function(e) {
				new Event(e).stop();
				SqueezeBox.fromElement(el);
			});
		});
	});
</script>

<div id="jsnis-main-content">
	<div id="jsnis-image-source-profiles">
		<form action="index.php?option=com_imageshow&controller=maintenance&type=profiles" method="POST" name="adminForm" id="frm_profile">
			<table border="0" width="100%">
				<tr>
					<td align="left" width="100%"><?php echo JText::_('TITLE'); ?> :
						<input type="text" name="config_title" id="config_title" value="<?php echo $this->lists['profileTitle'];?>" class="text_area"/>&nbsp;
						<button onclick="this.form.submit();"><?php echo JText::_('GO'); ?></button>
						<button onclick="document.getElementById('img_source').value=0;document.getElementById('config_title').value=''; this.form.submit();"><?php echo JText::_('RESET'); ?></button>
					</td>
					<td><?php echo $this->lists['imgSource'];?></td>
				</tr>
			</table>
			<table class="adminlist" border="0">
				<thead>
					<tr>
						<th width="10"> <?php echo JText::_('NUM'); ?> </th>
						<th class="title" nowrap="nowrap" width="85%"> <?php echo JText::_('TITLE'); ?> </th>
						<th><?php echo JText::_( 'Showlists' ); ?></th>
						<th width="5%" nowrap="nowrap"> <?php echo JText::_('SOURCE TYPE'); ?> </th>
						<th width="8%" nowrap="nowrap"> <?php echo JText::_('ACTION'); ?> </th>
					</tr>
				</thead>
				<tbody>
					<?php
				$k = 0;
				for ($i=0, $n=count( $this->profiles ); $i < $n; $i++)
				{
					$row = &$this->profiles[$i];	
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td><?php echo $i + 1; ?></td>
						<td><?php echo $this->escape($row->configuration_title); ?></td>
						<td align="center"><a class="modal" rel="{handler: 'iframe', size: {x: 600, y: 350}}" href="index.php?option=com_imageshow&controller=showlist&task=elements&tmpl=component&limit=0&configuration_id=<?php echo $row->configuration_id; ?>"> <?php echo $row->totalshowlist; ?></a></td>
						<td align="center">
							<?php
								if($row->source_type == 1){
									echo JText::_('FOLDER');
								}elseif ($row->source_type == 2){
									echo JText::_('FLICKR');
								}elseif ($row->source_type == 3){
									echo JText::_('PICASA');
								}
							?>
						</td>
						<td align="center" class="actionprofile"><?php if($row->source_type == 2){ ?>
							<a rel="{handler: 'iframe', size: {x: 600, y: 250}}" href="index.php?option=com_imageshow&controller=maintenance&type=editprofile&source_type=<?php echo $row->source_type; ?>&tmpl=component&configuration_id=<?php echo $row->configuration_id?>" class="action-edit modal" title="<?php echo JText::_('EDIT')?>"></a>
							<?php }elseif ($row->source_type == 3){?>
							<a rel="{handler: 'iframe', size: {x: 600, y: 170}}" href="index.php?option=com_imageshow&controller=maintenance&type=editprofile&source_type=<?php echo $row->source_type; ?>&tmpl=component&configuration_id=<?php echo $row->configuration_id?>" class="action-edit modal" title="<?php echo JText::_('EDIT')?>"></a>
							<?php } ?>
							<a rel="{handler: 'iframe', size: {x: 600, y: 300}}" href="index.php?option=com_imageshow&controller=showlist&task=element&tmpl=component&limit=0&configuration_id=<?php echo $row->configuration_id?>" class="action-delete modal" title="<?php echo JText::_('DELETE')?>"></a>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="option" value="com_imageshow" />
			<input type="hidden" name="controller" value="maintenance" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="task" value="" id="task" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
</div>
