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
* extension_detector is a static class that provides methods for detecting
* if current Joomla installation uses any components, modules or plugins that require
* specific changes or additions in code
*
* @static
*/
class extension_detector
{

   /*
   * Constructor.
   *
   * This constructor merely triggers a fatal error to prevent this class from
   * being instantiated. The :: syntax must be used instead.
   *
   * All methods are static.
   *
   * {@source}
   */
    function extension_detector()
    {
        trigger_error('Do not instantiate extension_detector.');
        die;
    } // extension_detector

	/*
	* returns true if Community Builder is detected
	*/	function detect_cb()
	{    	$db = & JFactory::getDBO();
     	$db->setQuery("SELECT 1 FROM #__comprofiler LIMIT 0, 1");
		$result = $db->loadObject();
		return (is_object($result));
	} //detect_cb
}
?>