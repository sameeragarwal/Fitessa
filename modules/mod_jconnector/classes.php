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

defined( '_JEXEC' ) or die( 'Restricted access' );

/*
* This class is wrapper for different components that have their own registration process
*/
class jconnector_registration
{    /*
    * Checks which extension is used and calls the required method
    */
	function run($generated_details, $user_details)
	{		if (extension_detector::detect_cb())
		{        	return $this->register_cb($generated_details, $user_details);
		}
		else
		{			return $this->register_joomla($generated_details, $user_details);
		}
	}

	/*
	* Default registration process that is used in Joomla
	*/
	function register_joomla($generated_details, $user_details)
	{
		$db = & JFactory::getDBO();
		//emulate Joomla input
		$token	= JUtility::getToken();
	    JRequest::setVar($token, 1, 'post');
	    JRequest::setVar('name', ($user_details['first_name']?$user_details['first_name']:$username), 'post');
	    JRequest::setVar('username', $generated_details['username'], 'post');
	    JRequest::setVar('email', mt_rand().'@'.mt_rand().'.rnd', 'post');
	    JRequest::setVar('password', $generated_details['password'], 'post');
	    JRequest::setVar('password2', $generated_details['password'],'post');

        define( 'JPATH_COMPONENT',	'components'.DS.'com_user');
		$joomla_reg = new UserController();
	    //load language vars that Joomla registration uses (e.g. in emails)
		$lang =& JFactory::getLanguage();
	    $lang->load('com_user');
	    //early 1.5.x Joomla versions are trying to start output here
	    //so we need to clear it out
	    ob_start();
	    $joomla_reg->register_save();
	    ob_end_clean();
	    //save user's facebook id
	    $query = 'UPDATE #__users SET facebook_id='.$db->Quote($user_details['uid']).' WHERE username = '.$db->Quote($generated_details['username']);
		$db->setQuery($query);
		$db->query();
	    //now we must activate this user
		$query = 'SELECT id FROM #__users WHERE username = '.$db->Quote($generated_details['username']);
		$db->setQuery($query);
		$user_id = intval($db->loadResult());
		if($user_id)
		{
			$user =& JUser::getInstance((int)$user_id);
			$user->set('block', '0');
			$user->set('activation', '');
			$user->save();
		}

	    $query = 'UPDATE #__users SET email='.$db->Quote($generated_details['email']).' WHERE id='.$db->Quote($user_id);
		$db->setQuery($query);
		$db->query();

		return $user_id;
	}

	/*
	* Registration process that is used in CB
	*/
	function register_cb($generated_details, $user_details)
	{
		$db = & JFactory::getDBO();

		//emulate Joomla input
		$token	= JUtility::getToken();
	    JRequest::setVar($token, 1, 'post');
	    JRequest::setVar('option', 'com_comprofiler', 'post');
	    JRequest::setVar('name', ($user_details['first_name']?$user_details['first_name']:$username), 'post');
	    JRequest::setVar('firstname', ($user_details['first_name']?$user_details['first_name']:$username), 'post');
	    JRequest::setVar('lastname', ($user_details['last_name']?$user_details['last_name']:$username), 'post');
	    JRequest::setVar('username', $generated_details['username'], 'post');
	    JRequest::setVar('email', mt_rand().'@'.mt_rand().'.rnd', 'post');
	    JRequest::setVar('password', $generated_details['password'], 'post');
	    JRequest::setVar('password__verify', $generated_details['password'], 'post');

        define('JPATH_COMPONENT', 'components'.DS.'com_comprofiler');
        define('_VALID_CB', 1);
        define('_VALID_MOS', 1);

        //supress output of the default "task"
   	    ob_start();
        include_once('components'.DS.'com_comprofiler'.DS.'comprofiler.php');
        ob_end_clean();

    	 //this values keep CB happy
    	JRequest::setVar(cbSpoofField(), cbSpoofString(null, 'registerForm'), 'post');
    	$cbGetRegAntiSpams = cbGetRegAntiSpams();
        include_once('administrator'.DS.'components'.DS.'com_comprofiler'.DS.'library'.DS.'cb'.DS.'cb.session.php');
    	CBCookie::setcookie(cbGetRegAntiSpamCookieName($cbGetRegAntiSpams[0]), $cbGetRegAntiSpams[1], false);
    	$_COOKIE[cbGetRegAntiSpamCookieName($cbGetRegAntiSpams[0])] = $cbGetRegAntiSpams[1];
    	JRequest::setVar(cbGetRegAntiSpamFieldName(), $cbGetRegAntiSpams[0], 'post');
    	//disable captcha
		global $_PLUGINS;
    	ob_start();
		$_PLUGINS->loadPluginGroup('user');
		ob_end_clean();
		foreach($_PLUGINS->_plugins as $k=>$v)
		{
			if ($v->element=='cb.captcha')
			{
            	$_PLUGINS->_plugins[$k]->published = 0;
			}
		}
	    //user agrees to terms and conditions
   	    JRequest::setVar('acceptedterms', '1', 'post');

    	//disable email confirmation and admin approval
    	$GLOBALS['ueConfig']['reg_confirmation'] = 0;
    	$GLOBALS['ueConfig']['reg_admin_approval'] = 0;

    	//perform registration and supress its output
    	ob_start();
        saveRegistration(null);
        ob_end_clean();

		$query = 'SELECT id FROM #__users WHERE username = '.$db->Quote($generated_details['username']);
		$db->setQuery($query);
		$user_id = intval($db->loadResult());

	    $query = 'UPDATE #__users SET email='.$db->Quote($generated_details['email']).' WHERE id='.$db->Quote($user_id);
		$db->setQuery($query);
		$db->query();

		return $user_id;
	}
}

?>