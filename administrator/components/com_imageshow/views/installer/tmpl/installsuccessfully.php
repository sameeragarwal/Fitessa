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
include_once('steps.php');
?>

<div class="jsnis-installation-container">
	<div class="jsnis-installation-step<?php echo $step1; ?> first"><?php echo JText::_('CP INSTALL CORE'); ?></div>
	<div class="jsnis-installation-step<?php echo $step2; ?>"><?php echo JText::_('CP INSTALL THEME'); ?></div>
	<div class="jsnis-installation-step<?php echo $step3; ?>"><?php echo JText::_('CP FINISH INSTALLATION'); ?></div>
	<div class="jsnis-installation-finish">
		<p class="jsnis-installation-success"><?php echo JText::_('INSTALLATION CONGRATULATION'); ?></p>
		<div class="jsnis-button">
			<button name="close" value="Finish" type="button" onclick="redirectToImageShowPage();"><?php echo JText::_('FINISH'); ?></button>
		</div>
	</div>
</div>