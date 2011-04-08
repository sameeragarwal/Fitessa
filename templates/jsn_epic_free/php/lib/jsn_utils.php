<?php
/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2009 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/

	defined( '_JEXEC' ) or die( 'Restricted index access' );

	class JSNUtils {

		function JSNUtils() {}

		function &getInstance() {
			static $instance;

			if ($instance == null) {
				$instance = new JSNUtils();
			}

			return $instance;
		}

		function countPositions($t, $positions) {
			$positionCount = 0;
			for($i=0;$i < count($positions); $i++){
				if ($t->countModules($positions[$i])) $positionCount++;
			}
			return $positionCount;
		}
		
		function addToQueryString($key, $value) {
			$url = $_SERVER['REQUEST_URI'];
		    $url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
		    $url = substr($url, 0, -1);
		    if (strpos($url, '?') === false) {
		        return ($url . '?' . $key . '=' . $value);
		    } else {
		        return ($url . '&' . $key . '=' . $value);
		    }
		}

		function getCurrentUrl() {
			$pageURL = 'http';
			if (!empty($_SERVER['HTTPS'])) {
				$pageURL .= "s";
			}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			return JFilterOutput::ampReplace($pageURL);
		}

		function getTemplateAttributes($attrs_array, $template_prefix, $pageclass) {
			$template_attrs = null;

			foreach ($attrs_array as $attr_name => $attr_values) {
				$t_attr = null;
				
				// Get template settings from page class suffix
				if(!empty($pageclass)){
					$pc = 'custom-'.$attr_name.'-';
					$pc_len = strlen($pc);
					$pclasses = explode(" ", $pageclass);
					foreach($pclasses as $pclass){
						if(substr($pclass, 0, $pc_len) == $pc) {
							$t_attr = substr($pclass, $pc_len, strlen($pclass)-$pc_len);
						}
					}
				}
				if( isset( $_GET['jsn_setall'] ) && $_GET['jsn_setall'] == 'default' ) {
					setcookie($template_prefix.$attr_name, '', time() - 3600, '/');
				} else {
					// Apply template settings from cookies
					if (isset($_COOKIE[$template_prefix.$attr_name])) {
						$t_attr = $_COOKIE[$template_prefix.$attr_name];
					}
	
					// Apply template settings from permanent request parameters
					if (isset($_GET['jsn_set'.$attr_name])) {
						setcookie($template_prefix.$attr_name, trim($_GET['jsn_set'.$attr_name]), time() + 3600, '/');
						$t_attr = trim($_GET['jsn_set'.$attr_name]);
					}
				}
				
				// Store template settings
				$template_attrs[$attr_name] = null;
				if(is_array($attr_values)){
					if (in_array($t_attr, $attr_values)) {
						$template_attrs[$attr_name] = $t_attr;
					}
				} else if($attr_values == 'integer'){
					$template_attrs[$attr_name] = intval($t_attr);
				}
			}

			return $template_attrs;
		}
		
		function getTemplateDetails($templateBaseDir, $templateDir)
		{
			// Check of the xml file exists
			if (!is_file($templateBaseDir.DS.'templateDetails.xml')) {
				return false;
			}

			$xml = $this->parseXMLInstallFile($templateBaseDir.DS.'templateDetails.xml');

			if ($xml['type'] != 'template') {
				return false;
			}

			$data = new StdClass();
			$data->directory = $templateDir;

			foreach($xml as $key => $value) {
				$data->$key = $value;
			}

			$data->checked_out = 0;
			$data->mosname = JString::strtolower(str_replace(' ', '_', $data->name));

			return $data;
		}
		
		function wrapFirstWord( $value )
		{
		 	$processed_string =  null;
		 	$explode_string = explode(' ', trim( $value ) );
		 	for ( $i=0; $i < count( $explode_string ); $i++ )
		 	{
		 		if( $i == 0 )
		 		{
		 			$processed_string .= '<span>'.$explode_string[$i].'</span>';
		 		}
		 		else
		 		{
		 			$processed_string .= ' '.$explode_string[$i];
		 		}
		 	}
		 	
		 	return $processed_string;
		 }
		 
		function parseXMLInstallFile($path)
		{
			$xml = & JFactory::getXMLParser('Simple');
			
			if (!$xml->loadFile($path)) {
				unset($xml);
				return false;
			}
			if ( !is_object($xml->document) || ($xml->document->name() != 'install' && $xml->document->name() != 'mosinstall')) {
				unset($xml);
				return false;
			}
			
			$data = array();
			$data['legacy'] = $xml->document->name() == 'mosinstall';
			
			$element = & $xml->document->name[0];
			$data['name'] = $element ? $element->data() : '';
			$data['type'] = $element ? $xml->document->attributes("type") : '';
			
			$element = & $xml->document->creationDate[0];
			$data['creationdate'] = $element ? $element->data() : JText::_('Unknown');
			
			$element = & $xml->document->author[0];
			$data['author'] = $element ? $element->data() : JText::_('Unknown');
			
			$element = & $xml->document->copyright[0];
			$data['copyright'] = $element ? $element->data() : '';
			
			$element = & $xml->document->authorEmail[0];
			$data['authorEmail'] = $element ? $element->data() : '';
			
			$element = & $xml->document->authorUrl[0];
			$data['authorUrl'] = $element ? $element->data() : '';
			
			$element = & $xml->document->version[0];
			$data['version'] = $element ? $element->data() : '';
			
			$element = & $xml->document->edition[0];
			$data['edition'] = $element ? $element->data() : '';
						
			$element = & $xml->document->license[0];
			$data['license'] = $element ? $element->data() : '';
			
			$element = & $xml->document->description[0];
			$data['description'] = $element ? $element->data() : '';
			
			$element = & $xml->document->group[0];
			$data['group'] = $element ? $element->data() : '';
			
			return $data;
		}
		
		function getBrowserInfo($agent = null)
		{
			$browser = array("browser"=>'', "version"=>'');
			$known = array("firefox", "msie", "opera", "chrome", "safari",
						"mozilla", "seamonkey", "konqueror", "netscape",
			            "gecko", "navigator", "mosaic", "lynx", "amaya",
			            "omniweb", "avant", "camino", "flock", "aol");
			$agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);			
			foreach($known as $value)
			{
				if (preg_match("#($value)[/ ]?([0-9.]*)#", $agent, $matches))
				{
					$browser['browser'] = $matches[1];
					$browser['version'] = $matches[2];
					break;
				}
			}
			return $browser;	
		}
		
		function trimEndingSlash($string)
		{
			$string = trim($string);
			$sub_string = substr($string, -1);
			if ($sub_string == '/') {
				$slash_pos = strrpos($string, '/');
				$string = substr($string, 0, $slash_pos);
			}
			return $string;
		}
		
		function checkMootoolVersion()
		{
		    $application = JFactory::getApplication();
			return substr(trim($application->get('MooToolsVersion', '1.11')), 0, 3);
		}

		// encode object data to json data
		function encodeJSON($dataObj)
		{
			if (!function_exists('json_encode')) 
			{
				if(!class_exists('Services_JSON'))
				{
					require_once 'json.php';
				}
			    $json = new Services_JSON;
			    return $json->encode($dataObj);
			}
			else
			{
				return json_encode($dataObj);
			}
		}
		
		// decode json string to object data
		function decodeJSON($stringJSON)
		{
			if (!function_exists('json_decode')) 
			{
				if(!class_exists('Services_JSON'))
				{
					require_once 'json.php';
				}
			    $json = new Services_JSON;
			    return $json->decode($stringJSON);
			}
			else
			{
				return json_decode($stringJSON);
			}
		}
		
		function checkFolderWritable($path)
		{
			if (!is_writable($path)) {
				return false;
			} 
			return true;
		}
		
		function cleanupCacheFolder($template_name = '', $css_compression = 0, $cache_folder_path)
		{
			if( $css_compression !=  1 ) {
				if( $handle = opendir($cache_folder_path) ) {
					while (false !== ($file = readdir($handle))) {
						$pattern = '/^'.$template_name.'_css/';
						if( preg_match($pattern, $file) > 0 ) {
						    @unlink($cache_folder_path.'/'.$file);
						}
				    }
				}
			}
		}
		
		function articleTools($type = 'email', $article, $params, $access, $attribs = array())
		{
			if($type == 'pdf') {
				$url  = 'index.php?view=article';
				$url .=  @$article->catslug ? '&catid='.$article->catslug : '';
				$url .= '&id='.$article->slug.'&format=pdf';
		
				$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
		
				// checks template image directory for image, if non found default are loaded
				if ($params->get('show_icons')) {
					$attribs['class'] = 'jsn-article-pdf-button';
					$text = '&nbsp;';
				} else {
					$text = JText::_('PDF').'&nbsp;';
				}
		
				$attribs['title']	= JText::_( 'PDF' );
				$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
				$attribs['rel']     = 'nofollow';
		
				return JHTML::_('link', JRoute::_($url), $text, $attribs);
			}
			if ($type == 'email') {
				require_once JPATH_SITE.'/components/com_content/helpers/route.php';
				$uri	=& JURI::getInstance();
				$base	= $uri->toString( array('scheme', 'host', 'port'));
				$link	= $base.JRoute::_( ContentHelperRoute::getArticleRoute($article->slug, $article->catslug, $article->sectionid) , false );
				$url	= 'index.php?option=com_mailto&tmpl=component&link='.base64_encode( $link );
		
				$status = 'width=400,height=350,menubar=yes,resizable=yes';
		
				if ($params->get('show_icons')) 	{
					$attribs['class'] = 'jsn-article-email-button';
					$text = '&nbsp;';
				} else {
					$text = '&nbsp;'.JText::_('Email');
				}
		
				$attribs['title']	= JText::_( 'Email' );
				$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
		
				$output = JHTML::_('link', JRoute::_($url), $text, $attribs);
				return $output;
			}
			if($type == 'print_popup') {
				$url  = 'index.php?view=article';
				$url .=  @$article->catslug ? '&catid='.$article->catslug : '';
				$url .= '&id='.$article->slug.'&tmpl=component&print=1&layout=default&page='.@ $request->limitstart;
		
				$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
		
				// checks template image directory for image, if non found default are loaded
				if ( $params->get( 'show_icons' ) ) {
					$attribs['class'] = 'jsn-article-print-button';
					$text = '&nbsp;';
				} else {
					$text = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'Print' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
				}
		
				$attribs['title']	= JText::_( 'Print' );
				$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
				$attribs['rel']     = 'nofollow';
		
				return JHTML::_('link', JRoute::_($url), $text, $attribs);
			}
			if($type = 'print_screen') {
				if ( $params->get( 'show_icons' ) ) {
					$attribs['class'] = 'jsn-article-print-button';
					$text = '&nbsp;';
				} else {
					$text = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'Print' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
				}
				return '<a class="'.$attribs['class'].'" href="#" onclick="window.print();return false;">'.$text.'</a>';
				
			}
		}
	}
?>