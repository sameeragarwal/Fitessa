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
global $mainframe;
include_once('steps.php');
if($this->model->checkThemePlugin())
{
	$mainframe->redirect('index.php?option=com_imageshow&controller=installer&task=installsuccessfully');
}
?>

<div class="jsnis-installation-container">
	<div class="jsnis-installation-step<?php echo $step1; ?> first"><?php echo JText::_('CP INSTALL CORE'); ?></div>
	<div class="jsnis-installation-step<?php echo $step2; ?>"><?php echo JText::_('CP INSTALL THEME'); ?></div>
	<div class="jsnis-installation-finish">
		<form enctype="multipart/form-data" action="index.php?option=com_imageshow&controller=installer&task=installtheme" method="post" name="adminForm">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td colspan="2"><?php echo JText::_('YOU MUST INSTALL AT LEAST 1 THEME TO PRESENT YOUR GALLERY'); ?></td>
				</tr>
				<tr>
					<td width="120"><strong>
						<label for="install_package"><?php echo JText::_('SELECT THEME'); ?>:</label>
						</strong></td>
					<td><input class="input_box" id="install_package" name="install_package" type="file" size="57" /></td>
				</tr>
			</table>
			<div class="jsnis-button">
				<button type="button" value="<?php echo JText::_('NEXT'); ?>" onclick="submitbutton()"><?php echo JText::_('NEXT'); ?></button>
			</div>
			<input type="hidden" name="controller" value="installer" />
			<input type="hidden" name="type" value="" />
			<input type="hidden" name="installtype" value="upload" />
			<input type="hidden" name="task" value="doInstall" />
			<input type="hidden" name="option" value="com_imageshow" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
	<div class="jsnis-installation-step<?php echo $step3; ?>"><?php echo JText::_('CP FINISH INSTALLATION'); ?></div>
</div>