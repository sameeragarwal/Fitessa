<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
$user = & JFactory::getUser();
require_once (JPATH_COMPONENT.DS.'controller.php');
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'media.php' );
require_once( JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_factory.php' );
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
$controllerName = JRequest::getCmd( 'controller' );

global $objectLog;
$objShowcaseTheme 		= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
$objectLog 				= JSNISFactory::getObj('classes.jsn_is_log');
$objectCheckJooPhoca 	= JSNISFactory::getObj('helpers.checkjoopho', 'CheckJoomPhocaHelper');
$objectCheckJooPhoca->checkComInstalled();
$objShowcaseTheme->enableAllTheme();

if ($controller = JRequest::getWord('controller')) 
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) 
	{
		require_once $path;
	} 
	else 
	{
		$controller = '';
	}
}

$classname	= 'ImageShowController'.$controller;
$controller	= new $classname();
$controller->execute( JRequest::getVar( 'task' ) );
$controller->redirect();