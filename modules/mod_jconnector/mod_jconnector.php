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

// no direct access
defined('_JEXEC') or die('Restricted access');

$fb_key		 = $params->get('fb_key', '');
$fb_secret	 = $params->get('fb_secret', '');

$db = & JFactory::getDBO();
//definitely not an elegant way to do install but we don't have another choise
if (!file_exists('modules/mod_jconnector/install.done'))
{
	$query = "CREATE TABLE `#__jconnector_ids` (`user_id` INT NOT NULL ,`facebook_id` VARCHAR(50) NOT NULL);";
	$db->setQuery($query);
	$db->query();
	@chmod('modules/mod_jconnector/', 0755);
	@chmod('modules/mod_jconnector/xd_receiver.htm', 0755);
	file_put_contents('modules/mod_jconnector/install.done', date('r'));
}

$user = & JFactory::getUser();
$query = 'SELECT facebook_id FROM #__jconnector_ids WHERE user_id = '.$db->Quote($user->id);
$db->setQuery($query);
$user_data = $db->loadObject();
if ($user_data)
{
	$facebook_id = $user_data->facebook_id;
	//determine if user has a facebook proxied email
	$query = 'SELECT email FROM #__users WHERE id = '.$db->Quote($user->id);
	$db->setQuery($query);
	$email_data = $db->loadObject();
	if (strpos($email_data->email, '@proxymail.facebook.com')!==false) //user has a FB proxied email
	{
     	//now we need to check if user has already granted extended application permission (for email only)
		include_once(JPATH_BASE .DS.'modules'.DS.'mod_jconnector'.DS.'facebook'.DS.'php'.DS.'facebook.php');
		$facebook = new Facebook($fb_key, $fb_secret);
		$has_permission = ($facebook->api_client->users_hasAppPermission('email', $facebook_id) ? true : false);
	}
	else //user has a normal Joomla email that was obtained during normal registration process
	{
    	$has_permission = true;
	}
}
else
{
	$facebook_id = false;
}

$uri_base = JURI::base();

require(JModuleHelper::getLayoutPath('mod_jconnector'));
?>