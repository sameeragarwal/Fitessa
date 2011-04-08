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
require_once (JPATH_COMPONENT.DS.'controller.php');
include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'classes'.DS.'jsn_is_factory.php');
$controllerName = JRequest::getCmd( 'controller' );

if ($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

$classname	= 'ImageShowController'.$controller;
$controller	= new $classname();
$controller->execute( JRequest::getVar( 'task' ) );
$controller->redirect();