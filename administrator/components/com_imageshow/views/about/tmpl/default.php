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
JToolBarHelper::title( JText::_('JSN IMAGESHOW').': '.JText::_( 'ABOUT' ), 'about' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$edition = @$this->infoXmlDetail['edition'];
$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
echo $objJSNMsg->displayMessage('ABOUT');
?>
<div id="jsn-imageshow-about">
	<div id="jsn-imageshow-intro">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="10"><?php echo JHTML::_('image.administrator', 'components/com_imageshow/assets/images/extension-box.jpg',''); ?></td>
				<td align="left">
					<h2><a href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow.html"><?php echo JText::_('JSN') .' '. @$this->infoXmlDetail['realName'].'&nbsp;'.strtoupper($edition); ?></a></h2>
					<?php if(strtolower($edition) == 'pro standard')
					{
					?>
					<p class="jsn-message-upgrade"><?php echo JText::_('UPGRADE TO UNLIMITED'); ?></p>
					<?php
					}
					?>
					<hr />	
					<dl>
						<dt><?php echo JText::_('VERSION'); ?>:</dt><dd><strong class="jsn-current-version"><?php echo $this->infoXmlDetail['version']; ?></strong>&nbsp;-&nbsp;<a href="javascript:void(0);" id="jsn-check-version" class="link-action"><strong><?php echo JText::_('CHECK FOR UPDATE'); ?></strong></a><span id="jsn-check-version-result"></span></dd>
						<dt><?php echo JText::_('AUTHOR'); ?>:</dt><dd><a href="http://<?php echo $this->infoXmlDetail['website']; ?>"><?php echo $this->infoXmlDetail['author']; ?></a></dd>
						<dt><?php echo JText::_('COPYRIGHT'); ?>:</dt><dd><?php echo $this->infoXmlDetail['copyright']; ?></dd>
					</dl>
					<div class="content-center"><ul class="list-horizontal"><li><a href="http://twitter.com/joomlashine" target="_blank" class="link-button"><span class="icon-twitter"><?php echo JText::_('FOLLOW US ON TWITTER'); ?></span></a></li></ul></div>
				</td>
			</tr>
		</table>
	</div>
	<?php
		$explodeEdition =  explode(' ', $edition);
		echo JText::_('COMPONENT DESCRIPTION '.$explodeEdition[0]);
	?>
</div>