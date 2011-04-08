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
class JButtonJSNMenuButton extends JButton
{
	var $_name = 'JSNMenuButton';

	function fetchButton($type = 'JSNMenuButton')
	{
		$edit 		= JRequest::getVar('edit');
		$text		= JText::_('JSN MENU BUTTON');
		$document 	= &JFactory::getDocument();
		$strAlert 	= '';
		if (!is_null($edit))
		{
			$strAlert = 'jsnMenu.getElements(\'a\').each(function(el)
					{
						el.addEvent(\'click\', function(event)
						{
							event = new Event(event);
							event.stop();

							JSNISImageShow.menuLinkRedirect = el.href;

							var result = confirm("'.JText::_('JSN MENU CONFIRM BOX ALERT', true).'");

							if (result == true)
							{
							  	JSNISImageShow.jsnMenuSaveToLeave(\'save\', JSNISImageShow.menuLinkRedirect);
							}
							else
							{
							  	JSNISImageShow.jsnMenuSaveToLeave(\'notsave\', JSNISImageShow.menuLinkRedirect);
							}
						});
					});';
		}
		
		$document->addScriptDeclaration("
			window.addEvent('domready', function()
			{
				JSNISImageShow.jsnMenuEffect();

				var jsnMenu = $('jsnis-menu');
				var edit = '".trim(strtolower($edit))."';
				JSNISImageShow.menuLinkRedirect = null;

				".$strAlert."
			})
		");


		$html  = '<ul id="jsnis-menu" class="clearafter">';
		$html .= '	<li class="menu-name"><a class="icon-32 icon-menu"><span>'.$text.'</span></a>';
		$html .= '		<ul class="jsnis-submenu">';
		$html .= '			<li class="first"><a class="icon-24 icon-launchpad " href="index.php?option=com_imageshow"><span>'.JText::_('LAUNCH PAD').'</span></a></li>';
		$html .= '			<li class="parent"><a class="icon-24 icon-showlist " href="index.php?option=com_imageshow&controller=showlist"><span>'.JText::_('SHOWLISTS').'</span></a>';
		$html .= '				<ul>';
		$html .= '					<li><a class="icon-24 icon-additem " title="'.JText::_('CREATE NEW SHOWLIST').'" href="index.php?option=com_imageshow&controller=showlist&task=add"><span>&nbsp;</span></a></li>';
		$html .= '				</ul>';
		$html .= '			</li>';
		$html .= '			<li class="parent"><a class="icon-24 icon-showcase" href="index.php?option=com_imageshow&controller=showcase"><span>'.JText::_('SHOWCASES').'</span></a>';
		$html .= '				<ul>';
		$html .= '					<li><a class="icon-24 icon-additem " title="'.JText::_('CREATE NEW SHOWCASE').'" href="index.php?option=com_imageshow&controller=showcase&task=add"><span>&nbsp;</span></a></li>';
		$html .= '				</ul>';
		$html .= '			</li>';
		$html .= '			<li class="separator"></li>';
		$html .= '			<li><a href="index.php?option=com_imageshow&controller=maintenance&type=configs"><span>'.JText::_('CONFIGURATION & MAINTENANCE').'</span></a></li>';
		$html .= '			<li><a href="index.php?option=com_imageshow&controller=help"><span>'.JText::_('HELP & SUPPORT').'</span></a></li>';
		$html .= '			<li class="last"><a href="index.php?option=com_imageshow&controller=about"><span>'.JText::_('ABOUT').'</span></a></li>';
		$html .= '		</ul>';
		$html .= '	</li>';
		$html .= '</ul>';

		return $html;
	}
}