<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
require_once dirname(__FILE__).DS.'subinstall'.DS.'subinstall.php';
require_once dirname(__FILE__).DS.'classes'.DS.'jsn_is_showcasetheme.php';
function com_uninstall() 
{
    $si 	= new SubInstaller();
    $return = $si->uninstall();
    if ($return)
    {
    	uninstallTheme();
    }
}

function uninstallTheme()
{
	$objTheme 	=& JSNISShowcaseTheme::getInstance();
	$themes  	= $objTheme->listThemes(false);
	if (count($themes)) 
	{
		foreach ($themes as $theme)
		{
			$objTheme->deleteThemeByPluginID((int) $theme['id']);
		}
		$app = JFactory::getApplication();
		$app->enqueueMessage('SubInstall: Successfully removed all JSN ImageShow Theme plugins', 'message');
	} 
}