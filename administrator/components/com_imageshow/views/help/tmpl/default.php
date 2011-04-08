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
JToolBarHelper::title( JText::_('JSN IMAGESHOW').': '.JText::_( 'HELP & SUPPORT' ), 'help' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
echo $objJSNMsg->displayMessage('HELP & SUPPORT');
$hasString = $objJSNUtils->checkHashString();
$objJSNXML = JSNISFactory::getObj('classes.jsn_is_readxmldetails');
$xmlDetails = $objJSNXML->parserXMLDetails();
?>

<div id="jsn-imageshow-help">
	<div class="jsn-column first-col">
		<div class="padding">
			<div class="jsnis-dgrey-heading-style jsnis-dgrey-heading">
				<h3 class="jsnis-element-heading "><?php echo JText::_('DOCUMENTATION'); ?></h3>
			</div>
			<div class="jsn-help-item"> <?php echo JText::_('DES DOCUMENTATION'); ?>
				<ul class="jsnis-link-arrow">
					<li><a class="jsnis-action-link link-action" href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-docs.zip" target="_blank"><?php echo JText::_('DOWNLOAD PDF DOCUMENTATION'); ?>
					</a></li>
					<li><a class="jsnis-action-link link-action" href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-quick-start-video.html" target="_blank"><?php echo JText::_('WATCH QUICK START VIDEO'); ?>
					</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="jsn-column">
		<div class="padding">
			<div class="jsnis-dgrey-heading-style jsnis-dgrey-heading">
				<h3 class="jsnis-element-heading "><?php echo JText::_('SUPPORT FORUM'); ?></h3>
			</div>
			<div class="jsn-help-item"> <?php echo JText::_('DES CHECK SUPPORT FORUM'); ?>
				<ul class="jsnis-link-arrow">
					<?php if (strtolower($xmlDetails['edition']) == 'pro standard' || strtolower($xmlDetails['edition']) == 'pro unlimited') :?>
					<li><a class="jsnis-action-link link-action" href="http://www.joomlashine.com/forum/" target="_blank"><?php echo JText::_('CHECK SUPPORT FORUM'); ?>
					</a></li>
					<?php endif;?>
					<?php if ($hasString == false):?>
					<li><a class="jsnis-action-link link-action" href="http://www.joomlashine.com/joomla-extensions/buy-jsn-imageshow.html" target="_blank"><?php echo JText::_('BUY PRO STANDARD EDITION'); ?>
					</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="jsn-column last-col">
		<div class="padding">
			<div class="jsnis-dgrey-heading-style jsnis-dgrey-heading">
				<h3 class="jsnis-element-heading "><?php echo JText::_('HELPDESK SYSTEM'); ?></h3>
			</div>
			<div class="jsn-help-item"> <?php echo JText::_('DES HELPDESK SYSTEM'); ?>
				<ul class="jsnis-link-arrow">
					<?php if (strtolower($xmlDetails['edition']) == 'pro unlimited') :?>
					<li><a class="jsnis-action-link link-action" href="http://www.joomlashine.com/dedicated-support.html" target="_blank"><?php echo JText::_('SUBMIT TICKET IN HELPDESK SYSTEM'); ?>
					</a></li>
					<?php endif;?>
					<?php if ($hasString == false):?>
					<li><a class="jsnis-action-link link-action" href="http://www.joomlashine.com/joomla-extensions/buy-jsn-imageshow.html" target="_blank"><?php echo JText::_('BUY PRO UNLIMITED EDITION'); ?>
					</a></li>
					<?php endif; ?>
					<?php if (strtolower($xmlDetails['edition']) == 'pro standard') :?>
					<li><a class="jsnis-action-link link-action" href="http://www.joomlashine.com/docs/general/how-to-upgrade-to-pro-unlimited-edition.html" target="_blank"><?php echo JText::_('UPGRADE TO PRO UNLIMITED EDITION'); ?>
					</a></li>
					<?php endif;?>
				</ul>
			</div>
		</div>
	</div>
	<div class="clr"></div>
</div>
