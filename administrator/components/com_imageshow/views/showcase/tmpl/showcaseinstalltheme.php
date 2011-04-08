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
jimport( 'joomla.utilities.utility' );
global $mainframe;
$arrayMsg = $mainframe->getMessageQueue();
if(count($arrayMsg) > 0)
{
	foreach($arrayMsg as $msg)
	{
		if($msg['type'] == 'error')
		{
?>
			<style>
				#system-message dd.message ul {
				    display:none;
				}
				#system-message dd.error ul {
				    display:block;
				}
			</style>
<?php 		break;
		}
		else
		{
?>	
			<style>
				#system-message dd.message ul {
				    display:none;
				}
			</style>
<?php 		
		}		
	}
}
?>

<style>
#system-message dt.message,
#system-message dt.error{
	display:none;
}
#system-message dd.message ul {
    background: url() no-repeat scroll 4px center #C3D2E5;
    list-style:none;
    float:right;
}
</style>

<?php 
$installStatus = JRequest::getWord('install');
if (($installStatus) == 'true')
{
	echo "<script>
				var url = top.window.location.href;
				if(url.contains('task') == false){
					url = url+'&task=add';
				}
				var showcaseDetailsForm = window.parent.document.getElementById('adminForm');
				showcaseDetailsForm.redirectLinkTheme.value = url;
				showcaseDetailsForm.task.value = 'refreshListThemes';
				showcaseDetailsForm.submit();
				
				window.top.setTimeout('window.parent.document.getElementById(\"sbox-window\").close();', 300);	
		 </script>";
}
?>
<div id="jsnis-install-theme">
	<h3><?php echo JText::_('INSTALL THEME'); ?></h3>
	<form id="jsn-form-install-theme" enctype="multipart/form-data" action="index.php?option=com_imageshow&controller=showcase&task=showcaseinstalltheme" method="post" name="adminForm">
		<p><strong><?php echo JText::_('SELECT THEME'); ?> :</strong>&nbsp;<input class="input_box" id="install_package" name="install_package" type="file" size="57" /></p>
		<p class="jsnis-button"><button onclick="submitbutton(); return false;"><?php echo JText::_('INSTALL THEME'); ?></button></p>
		<input type="hidden" name="redirect_link" value="index.php?option=com_imageshow&controller=showcase&task=showcaseinstalltheme&tmpl=component" />
		<input type="hidden" name="controller" value="showcase" />	
		<input type="hidden" name="type" value="" />
		<input type="hidden" name="installtype" value="upload" />
		<input type="hidden" name="task" value="installShowcaseTheme" />
		<input type="hidden" name="option" value="com_imageshow" />
		<?php echo JHTML::_('form.token'); ?>
	</form>
</div>
