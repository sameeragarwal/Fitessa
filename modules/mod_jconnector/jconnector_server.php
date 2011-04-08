<?php
//*----------------------------------------------------------------------
//jConnector (TM)
//*----------------------------------------------------------------------
//This source file is subject to the GNU General Public License (GPL)
//which is available online at http://www.gnu.org/copyleft/gpl.html
//*----------------------------------------------------------------------
//Authors: Web Scribble Solutions, Inc. (info@webscribble.com)
//Copyright 2009 Web Scribble Solutions, Inc. All rights reserved.
//Support: http://help.webscribble.com/display/jconnector/Home
//*----------------------------------------------------------------------
// jConnector is a trademark of Web Scribble Solutions, Inc.
//*----------------------------------------------------------------------
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );

include_once './extension_detector.php';
include_once './classes.php';

$_file_ = str_replace(constant('DS').'modules'.constant('DS').'mod_jconnector', '', dirname(__FILE__));
define('JPATH_BASE',  $_file_);

chdir('../../');
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'application'.DS.'module'.DS.'helper.php' );
include_once (JPATH_BASE .DS.'components'.DS.'com_user'.DS.'controller.php');
include_once (JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'base'.DS.'observable.php');

$config =& JFactory::getConfig();
$config->setValue('config.absolute_path', JPATH_BASE);

$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
$db = & JFactory::getDBO();
$user = & JFactory::getUser();
JPluginHelper::importPlugin('system');

$module = JModuleHelper::getModule('jconnector', '');
$pre_module_params = explode("\n", $module->params);
foreach ($pre_module_params as $v)
{
	if ($v)
	{		$parsed_params = explode('=', $v);
		$module_params[$parsed_params[0]] = $parsed_params[1];
	}
}

include_once(JPATH_BASE .DS.'modules'.DS.'mod_jconnector'.DS.'facebook'.DS.'php'.DS.'facebook.php');
$facebook = new Facebook($module_params['fb_key'], $module_params['fb_secret']);
$fb_uid = $facebook->require_login();
//http://wiki.developers.facebook.com/index.php/Users.getInfo
$user_details = $facebook->api_client->users_getInfo($fb_uid, array('last_name', 'first_name', 'about_me', 'activities', 'birthday', 'current_location', 'hometown_location', 'interests',
																	'meeting_for', 'movies', 'music', 'name', 'pic_with_logo', 'pic_big_with_logo', 'political', 'relationship_status',
																	'religion', 'sex', 'status', 'books', 'tv', 'proxied_email'));
$user_details = $user_details[0];
//print_r($user_details);die;

$db->setQuery("SELECT u.id, u.username, u.email FROM #__users AS u INNER JOIN #__jconnector_ids AS ji ON u.id=ji.user_id WHERE ji.facebook_id = ".$db->Quote($user_details['uid']));
$user_data = $db->loadObject();

if(!$user_data) //we don't have this FB user in our DB yet
{
	if ($user->id) //update existing user with his facebook_id
	{
		$username = $user->username;
		$user_id = $user->id;		header('Location: '.str_replace('modules/mod_jconnector/', '', JURI::base()));
	}
	else //register a new user
	{		//generate an unique username
		$i = 0;
		$username_base = str_replace(' ', '_', $user_details['name']);
		$username = '';
	   	do
	   	{
	   		if (!$username) $username = $username_base;
	   		else $username = $username_base.$i;
	   		$db->setQuery("SELECT id FROM #__users WHERE username = ".$db->Quote($username));
	   		$data = $db->loadObject();
	       	$i++;
	   	}while($data);
	   	$generated_details['username'] = $username;
		$generated_details['password'] = substr(uniqid(true), 0, 20);
		$generated_details['email'] = $user_details['proxied_email'];

		$jconnector_registration = new jconnector_registration();
		$user_id = $jconnector_registration->run($generated_details, $user_details);
	}
    //create entry that associates user_id with facebook_id
    if ($user_id)
    {
	    $query = 'INSERT INTO #__jconnector_ids (user_id, facebook_id) VALUES('.$db->Quote($user_id).', '.$db->Quote($user_details['uid']).')';
		$db->setQuery($query);
		$db->query();
	}
}
else
{
	if ($user->id) //somebody is trying to connect second Joomla account with the same Facebook user
	{
    	$username = $user_data->username;
	}
	else //a connected user is trying to sign in
	{		$username = $user_data->username;
	}
}

//*****compatibility layer for older jConnector*****
//here we update previously generated random emails
$is_old_email = preg_match('#\d*@\d*\.rnd#', $user_data->email); //detect if this is a random email created by an old jConnector
if ($is_old_email)
{
	$query = 'UPDATE #__users SET email='.$db->Quote($user_details['proxied_email']).' WHERE id='.$db->Quote($user_data->id);
	$db->setQuery($query);
	$db->query();
}
//*****end of compatibility layer*****

//sign in an existing user
jimport('joomla.user.authentication');
JPluginHelper::importPlugin('user');
$authenticate = & JAuthentication::getInstance(); //though we don't use this object here, creating it somehow makes JAuthenticationResponse class available
$authenticate_response = new JAuthenticationResponse();
$authenticate_response->status = JAUTHENTICATE_STATUS_SUCCESS;
//to make everything run fine, we need only username
$authenticate_response->username = $username;
$results = $mainframe->triggerEvent('onLoginUser', array((array)$authenticate_response));
header('Location: '.str_replace('modules/mod_jconnector/', '', JURI::base()));
?>