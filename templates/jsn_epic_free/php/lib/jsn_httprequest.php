<?php
/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2009 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );

class JSNHTTPRequests
{
	var $_fp 		= null;
    var $_url 		= '';
    var $_host		= '';
    var $_protocol	= '';
    var $_uri		= '';
    var $_port		= '';
 	var $_query 	= '';
	var $_method 	= null; 
	var $_data 	    = null;   
	var $_referer   = null;
	
    function JSNHTTPRequests($url, $data = null, $referer = null, $method = 'post') 
    { 
        $this->_url     = $url; 
        $this->_data    = $data;
        $this->_referer = $referer;
        $this->_method  = $method;
        $this->_scan_url(); 
    }
    	
	function _scan_url() 
	{ 
		$req = $this->_url;
		$url = parse_url($req);
		
		if(!isset($url['scheme']) || ($url['scheme'] != 'http')) 
		{ 
		    return false;
		} 
		
		$this->_protocol = $url['scheme'];
		$this->_host	 = $url['host'];   
		$this->_port     = (($url['scheme'] == 'https') ? 443 : 80); 
		$this->_uri 	 = (isset($url['path']) ? $url['path'] : '/');
		$this->_query 	 = (isset($url['query']) ? '?'.$url['query'] : '');
	}	
    
    function sendRequest() 
    { 
        $crlf     = "\r\n"; 
        $response = '';
        $data     = '';
        
        if (is_array($this->_data) && count($this->_data) > 0) 
        {
            $data = array();
            while (list ($n, $v) = each ($this->_data)) 
            {
                $data[] = "$n=$v";
            }    
            $data = implode('&', $data);
            $contentType = "Content-type: application/x-www-form-urlencoded".$crlf;
        }
        else 
        {
            $data = $this->_data;
            $contentType = "Content-type: text/xml".$crlf;
        } 
               
        if (is_null($this->_referer)) 
        {
            $referer = JURI::root();
        } 
               
        $this->_fp = @fsockopen(($this->_protocol == 'https' ? 'ssl://' : '') . $this->_host, $this->_port, $errno, $errstr, 5); 
   		
        if ($this->_fp === false) 
   		{
            return false;
        } 
               
        if ($this->_method == 'post')
        {
        	$req = 'POST '.$this->_uri
		        	.' HTTP/1.1'.$crlf
		        	.'Host: '.$this->_host.$crlf
		        	.'Referer: '.$referer.$crlf.$contentType
		        	.'Content-length: '.strlen($data).$crlf
		        	.'Connection: close'.$crlf.$crlf.$data;      
        }
        elseif ($this->_method == 'get')
        {
        	$req = 'GET '.$this->_uri.$this->_query
		        	.' HTTP/1.1'.$crlf
		        	.'Host: '.$this->_host.$crlf
		        	.'Connection: close'.$crlf.$crlf;    
        	fwrite($this->_fp, $req); 
        }
        
        while (is_resource($this->_fp) && $this->_fp && !feof($this->_fp))
        { 
           $response .= fread($this->_fp, 1024);
        } 
        
        fclose($this->_fp); 
        
        $pos = strpos($response, $crlf.$crlf);         
        if ($pos === false)
        { 
        	return($response); 
        }
        $header 	= substr($response, 0, $pos); 
        $body 		= substr($response, $pos + 2 * strlen($crlf));         
        $headers 	= array();
         
        $lines = explode($crlf, $header); 
        
        foreach ($lines as $line)
        { 
            if (($pos = strpos($line, ':')) !== false)
            { 
                $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1)); 
            }
        }

        if (isset($headers['location'])) 
        { 
            $http = new JSNHTTPRequests($headers['location']); 
            return $http->sendRequest(); 
        } 
        else 
        { 
            return $body; 
        } 
    }	
}
?>