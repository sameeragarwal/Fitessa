<?php
/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2009 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( '_JEXEC' ) or die( 'Restricted index access' );

class JElementJSNParams extends JElement
{
	
	var $_name = null;
	var $_value = null;
	var $_template_path_of_base;
	var $_template_name;
	var $_node;
	var $_control_name;
	var $_url = null;
	var $_modal_window = null;
	
	function JElementJSNParams()
	{
		$this->_setTmplInfo();
		require_once( $this->_template_path_of_base.DS.'php'.DS.'lib'.DS.'jsn_utils.php' );
		$this->_setTmplEdition();
		//$this->_initAccordion();	
	}
	
	function fetchElement( $name, $value, &$node, $control_name )
	{
		$this->_name 			= $name;
		$this->_value 			= $value;
		$this->_node 			= $node;
		$this->_control_name 	= $control_name;
		$this->_url 			= $node->attributes( 'url' );
		$this->_modal_window 	= $node->attributes( 'modalwindow' );
		$subtype 				= $node->attributes( 'subtype' );
		
		return $this->$subtype();
	}
	
	function _initAccordion()
	{
		$document 		=& JFactory::getDocument();
		$jsAccordion 	= "window.addEvent('domready', function(){ new Accordion($$('.panel h3.jpane-toggler'), $$('.panel div.jpane-slider'), {onActive: function(toggler, i) { toggler.addClass('jpane-toggler-down'); toggler.removeClass('jpane-toggler'); },onBackground: function(toggler, i) { toggler.addClass('jpane-toggler'); toggler.removeClass('jpane-toggler-down'); },duration: 300,opacity: false,alwaysHide: true}); });";
		$document->addScriptDeclaration($jsAccordion);
	}
	
	function _setTmplInfo()
	{
		$template_name 					= explode( DS, str_replace( array( '\elements', '/elements' ), '', dirname(__FILE__) ) );
		$template_name 					= $template_name [ count( $template_name ) - 1 ];
		$path_base 						= str_replace( DS."templates".DS.$template_name.DS.'elements', "", dirname(__FILE__) );
		$this->_template_path_of_base 	= $path_base . DS . 'templates' .  DS . $template_name;	
		$this->_template_name			= $template_name;
	}
	
	function _setTmplEdition()
	{				
		$obj_jsn_utils 			 = new JSNUtils();
		$result 				 = $obj_jsn_utils->getTemplateDetails($this->_template_path_of_base, $this->_template_name);		
		$template_edition 		 = $result->edition;
		$this->_template_edition = $template_edition;
	}
		
	function _buildModalWindow ($name, $url)
	{
		JHTML::_('behavior.modal', 'a.jsn-modal');
		$url 	= JURI::root().'templates/'.$this->_template_name.'/'.$url;
		$link 	= '<a class="jsn-modal" rel="{handler: \'iframe\', size: {x: 600, y: 450}}" href="'.$url.'">'.$name.'</a>';
		return $link;
	}
	
	function jsnLabel()
	{
		$tag 		= ( $this->_node->attributes( 'tag' ) ? $this->_node->attributes( 'tag' ) : 'div' );
		$class 		= ' class="'.( $this->_node->attributes( 'class' ) ? $this->_node->attributes('class') : 'jsn-label' ).'"';
		$alt_class 	= ( $this->_node->attributes( 'altclass' ) ? ' class="'.$this->_node->attributes( 'altclass' ).'"' : '' );
		
		if ( $this->_value )
		{
			return "<$tag$class>"."<span$class>".JText::_($this->_value)."</span></$tag>";
		}
		else
		{
			return '<hr />';
		}
	}
	
	function jsnPanel()
	{
		JHTML::stylesheet( 'jsn_admin.css', JURI::root().'templates/'.$this->_template_name.'/admin/css/' );
		JHTML::script('jsn_admin.js', JURI::root().'templates/'.$this->_template_name.'/admin/js/');
		static $count = 1;
		$output = "";
		$alt_class 				= ( $this->_node->attributes( 'altclass' ) ? $this->_node->attributes( 'altclass' ) : 'none' );
		$class 					= 'class="'. ( $this->_node->attributes( 'class' ) ? $this->_node->attributes('class') : 'title jsn-pane-toggler' ).'"';
		$opens_table 			= '<table class="paramlist admintable" width="100%" cellspacing="1" border="0"><tbody><tr><td>';
		$close_table 			= '</td></tr></tbody></table>';
		$surround_opens 		= '<div class="jsn-panel">';
		$surround_close 		= '</div>';
		$title 					= '<h3 id="panel-'.$count.'" '.$class.'><span class="toggle-arrow"><span class="param-icon"><span class="'.$alt_class.'">'.JText::_($this->_value).'</span></span></span></h3>';
		$inner_opens 			= '<div class="jsn-pane-slider content">';
		$inner_close 			= '</div>';
		$accordion_opens 		= '';
		$accordion_close 		= '';
		$accordion_condition 	= $this->_node->attributes('position');
		$controller		 		= '';
		if(!defined ('PANEL_CONTROLLER'))
		{
			$controller = '<div class="jsn-controller">';
			$controller .= '<a id="expand-all" href="#" title="'.JText::_('EXPAND_ALL_PANELS').'">'.JText::_('EXPAND_ALL').'</a>';
			$controller .= '&nbsp;&nbsp;|&nbsp;&nbsp;';
			$controller .= '<a id="collapse-all" href="#" title="'.JText::_('COLLAPSE_ALL_PANELS').'">'.JText::_('COLLAPSE_ALL').'</a>';
			$controller .= '</div>';
			
			define('PANEL_CONTROLLER', 1);
		}
		if($accordion_condition == 'first')
		{
			$accordion_opens 	= '<div class="jsn-pane-sliders" id="content-pane" style="width:100%;">';
		}
		$output .= $surround_close;
		if($accordion_condition == 'last')
		{
			$accordion_close 	= '</div>';
		}
		$output = $accordion_close . $close_table . $inner_close . $surround_close . $accordion_opens . $controller . $surround_opens . $title . $inner_opens . $opens_table;
		$count++;
		return $output;	
	}
	
	function jsnList()
	{
		$class 		= 'class="'. ( $this->_node->attributes( 'class' ) ? $this->_node->attributes( 'class' ) : 'inputbox' ).'"';
		$disabled 	= ( $this->_node->attributes( 'disabled' ) ? 'disabled="'.$this->_node->attributes( 'disabled' ).'"' : '' );
		$options 	= array ();
		
		if( count( $this->_node->children() ) )
		{
			foreach ( $this->_node->children() as $option )
			{
				$val		= $option->attributes( 'value' );
				$text		= $option->data();
				$options[] 	= JHTML::_( 'select.option', $val, JText::_( $text ) );
			}
		}

		return JHTML::_( 'select.genericlist',  $options, $this->_control_name.'['.$this->_name.']', $class.' '.$disabled, 'value', 'text', $this->_value, $this->_control_name.$this->_name );
	}
	
 	function jsnRadio()
	{
		$class 		= 'class="'. ( $this->_node->attributes( 'class') ? $this->_node->attributes( 'class' ) : 'inputbox' ).'"';
		$disabled 	= ( $this->_node->attributes( 'disabled' ) ? 'disabled="'.$this->_node->attributes( 'disabled' ).'"' : '' );
		$options 	= array ();
		
		if( count( $this->_node->children() ) )
		{
			foreach ( $this->_node->children() as $option )
			{
				$val		= $option->attributes( 'value' );
				$text		= $option->data();
				$options[] 	= JHTML::_( 'select.option', $val, JText::_( $text ) );
			}
		}

		return JHTML::_( 'select.radiolist', $options, ''.$this->_control_name.'['.$this->_name.']', $class.' '.$disabled, 'value', 'text', $this->_value, $this->_control_name.$this->_name );
	}
	
	function jsnText()
	{
		$size 		= ( $this->_node->attributes( 'size' ) ? 'size="'.$this->_node->attributes( 'size' ).'"' : '' );
		$class 		= 'class="'. ( $this->_node->attributes( 'class' ) ? $this->_node->attributes( 'class' ) : 'text_area' ).'"';
		$disabled 	= ( $this->_node->attributes( 'disabled' ) ? 'disabled="'.$this->_node->attributes( 'disabled' ).'"' : '' );
		$value		= htmlspecialchars( html_entity_decode( $this->_value, ENT_QUOTES ), ENT_QUOTES );

		return '<input type="text" name="'.$this->_control_name.'['.$this->_name.']" id="'.$this->_control_name.$this->_name.'" value="'.$value.'" '.$class.' '.$size.' '.$disabled.' />';
	}
	
	function jsnIconSelector()
	{
		$disabled 	= $this->_node->attributes( 'disabled' );
		$size 		= ( $this->_node->attributes( 'size' ) ? 'size="'.$this->_node->attributes( 'size' ).'"' : '' );
		$class 		= 'class="'. ( $this->_node->attributes( 'class' ) ? $this->_node->attributes( 'class' ) : 'text_area jsn-text-read-only' ).'"';
		$value		= htmlspecialchars( html_entity_decode( $this->_value, ENT_QUOTES ), ENT_QUOTES );
		if($disabled)
		{
			return '<input type="text" name="'.$this->_control_name.'['.$this->_name.']" id="'.$this->_control_name.$this->_name.'" value="'.$value.'" '.$class.' '.$size.' disabled="disabled" /> <span class="link-disabled">'.JText::_('Select').'</span>';
		}
		else
		{		
			require($this->_template_path_of_base.DS.'php'.DS.'jsn_config.php');
			
			$obj_jsn_utils =& JSNUtils::getInstance();
			$mootool_version = $obj_jsn_utils->checkMootoolVersion();
			JHTML::stylesheet( 'jsn_admin.css', JURI::root().'templates/'.$this->_template_name.'/admin/css/' );
			JHTML::script('jsn_utils.js', JURI::root().'templates/'.$this->_template_name.'/js/');
			JHTML::script('jsn_admin.js', JURI::root().'templates/'.$this->_template_name.'/admin/js/');
			JHTML::script('swfobject.js', JURI::root().'templates/'.$this->_template_name.'/js/');
			static $counter = 0;			
			$read_only 	= 'readonly="readonly"';						
			$index	 	= ++$counter;
			$url		= JURI::root().'templates/'.$this->_template_name.'/';
			$flash_alert_string = '<p>'.JText::_('YOU NEED FLASH PLAYER').'</p><p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p><p><a onclick="JSNAdmin.toggleIconSelector($$(\'.jsn-icon-selector-wapper\'), 2);return false;" href="#" class="link-action">'.JText::_('Close').'</a></p>';
			if($mootool_version == '1.2')
			{
                $flash_string = '<script language="javascript">'."\n";
    			    $flash_string .= 'window.addEvent("domready", function(){'."\n"; 
                    $flash_string .= 'var obj_'.$index.' = new Swiff("'.$url.'admin/swf/iconselector.swf", {'."\n";
                    $flash_string .= 'id: "jsn-flash-icon-selector-'.$index.'",'."\n";
                    $flash_string .= 'container: "jsn-flash-swiff-icon-selector-'.$index.'",'."\n";
                    $flash_string .= 'width: 320,'."\n";
                    $flash_string .= 'height: 250,'."\n";
                    $flash_string .= 'params: {'."\n";
                        $flash_string .= 'wMode: "window",'."\n";
                        $flash_string .= 'bgcolor:"#FFFFFF",'."\n";
                        $flash_string .= 'menu: "false",'."\n";
                        $flash_string .= 'allowFullScreen: "false",'."\n";
                        $flash_string .= 'quality: "best",'."\n";
                        $flash_string .= 'allowScriptAccess: "always"'."\n";
                    $flash_string .= '},'."\n";
                    $flash_string .= 'vars: {'."\n";
                        $flash_string .= 'sourceid: "icon-selector-container-'.$index.'",'."\n";
                        $flash_string .= 'iconsprite: "'.$url.'images/icons/icons-uni.png",'."\n";
                        $flash_string .= 'iconsize: "'.$jsn_icon_size.'",'."\n";
                        $flash_string .= 'iconoffset: "'.$jsn_icon_offset.'",'."\n";
                        $flash_string .= 'iconnames: "'.implode(',', $jsn_icon_names).'"'."\n";
                    $flash_string .= '}'."\n";    
                    $flash_string .= '});'."\n";
                $flash_string .= '});'."\n";
                $flash_string .= '</script>'."\n";
                $div_icon_container = '<div id="jsn-flash-swiff-icon-selector-'.$index.'" class="jsn-flash-swiff-inner-icon-selector"></div><div class="jsn-flash-swiff-icon-selector" style="display:none;">'.$flash_alert_string.'</div>';
			}
			else
			{
			    $flash_string = '<script language="javascript">'."\n";
			    $flash_string .= 'var flashvars = {sourceid:"icon-selector-container-'.$index.'", iconsprite:"'.$url.'images/icons/icons-uni.png", iconnames:"'.implode(',', $jsn_icon_names).'", iconsize:"'.$jsn_icon_size.'", iconoffset:"'.$jsn_icon_offset.'"};'."\n";
			    $flash_string .= 'var params = {wmode:"window", bgcolor:"#FFFFFF", menu:"false", allowFullScreen:"false", quality:"best", allowScriptAccess:"always"};'."\n";
			    $flash_string .= 'swfobject.embedSWF("'.$url.'admin/swf/iconselector.swf", "jsn-flash-icon-selector-'.$index.'", "320", "250", "9.0.0", "'.$url.'admin/swf/expressInstall.swf", flashvars, params);'."\n";
			    $flash_string .= '</script>'."\n";
			    $div_icon_container = '<div id="jsn-flash-icon-selector-'.$index.'" class="jsn-flash-icon-selector">'.$flash_alert_string.'</div>';			    
			}
			return '<div id="icon-selector-container-'.$index.'" class="jsn-icon-selector-wapper"><div class="jsn-icon-selector-container"><div class="jsn-icon-selector-holder normal">'.$flash_string.$div_icon_container.'</div></div><input type="text" name="'.$this->_control_name.'['.$this->_name.']" id="'.$this->_control_name.$this->_name.'" value="'.$value.'" '.$class.' '.$size.' '.$read_only.' /> <a class="jsn-link-icon-selector jsn-button-select" href="#" onclick="return false;" title="'.JText::_('SELECT').'"><span class="jsn-open-status">'.JText::_('Select').'</span><span style="display:none;" class="jsn-close-status">'.JText::_('Close').'</span></a>';
		}
	}	
	
	function jsnTextArea()
	{
		$rows 		= $this->_node->attributes('rows');
		$cols 		= $this->_node->attributes('cols');
		$disabled 	= ( $this->_node->attributes( 'disabled' ) ? 'disabled="'.$this->_node->attributes( 'disabled' ).'"' : '' );
		$class 		= ( $this->_node->attributes('class') ? 'class="'.$this->_node->attributes('class').'"' : 'class="text_area"' );
		$value 		= str_replace('<br />', "\n", $this->_value);
		return '<textarea name="'.$this->_control_name.'['.$this->_name.']" cols="'.$cols.'" rows="'.$rows.'" '.$class.' id="'.$this->_control_name.$this->_name.'" '.$disabled.'>'.$value.'</textarea>';
	}
			
	function jsnSampleData()
	{
		$template_name	= $this->_template_name;
		return JText::_('INSTALL SAMPLE DATA AS SEEN ON DEMO WEBSITE').'&nbsp;&nbsp;<a class="jsn-button-select" href="../index.php?template='.$template_name.'&tmpl=jsn_installsampledata">'.JText::_('Install sample data').'</a>';
	}
	
	function jsnCacheFolder() 
	{
		// Include JSN Utils
		$jsnutils = &JSNUtils::getInstance();
		/* Mootool version */
		$mootool_version = $jsnutils->checkMootoolVersion();
		/* Load Joomla configuration */
		$jconfig 		= new JConfig();
		
		$size 			= ( $this->_node->attributes( 'size' ) ? 'size="'.$this->_node->attributes( 'size' ).'"' : '' );
		$class 			= 'class="'. ( $this->_node->attributes( 'class' ) ? $this->_node->attributes( 'class' ) : 'text_area' ).'"';
		$value			= htmlspecialchars( html_entity_decode( $this->_value, ENT_QUOTES ), ENT_QUOTES );
		$root_folder	= str_replace('\\', '/', JPATH_ROOT ).'/';
		$php_version 	= $this->_node->attributes( 'phpversion' );
		$disabled 		= ( $this->_node->attributes( 'disabled' ) ? 'disabled="'.$this->_node->attributes( 'disabled' ).'"' : '' );
		$php_warning 	= '';
		$doc			=& JFactory::getDocument();
		$output			= '';
		
		if (!$disabled) {
			if(version_compare(PHP_VERSION, $php_version, '<') ) {
				$disabled 	= 'disabled="disabled"';
				$php_warning = '<font color="green">['.JText::_('MINIFY_PHP_5_REQUIRED').']</font>'; 
			}
			if($mootool_version == '1.1') {
				$doc->addScriptDeclaration("
					window.addEvent('domready', function(){
						$('paramscacheFolder').addEvents({
							keyup: function()
							{
								$('paramscacheFolder').fireEvent('hideCheckLink');
							},
							keydown: function()
							{
								$('paramscacheFolder').fireEvent('hideCheckLink');
							},
							hideCheckLink: function()
							{
								if( $('paramscacheFolder').value == '') 
								{
									$('jsncheckcache').setStyle('visibility', 'hidden');
								} else {
									$('jsncheckcache').setStyle('visibility', 'visible');
								}
							}
						});	
						
						$('jsncheckcache').addEvent('click', function() {
							var actionUrl = '".JURI::root()."index.php?template=$this->_template_name&tmpl=jsn_runajax&task=checkCacheFolder&cache_folder=".$root_folder."'+ $('paramscacheFolder').value;		
							var resultMsg = new Element('font');
							var request = new Json.Remote(actionUrl, {
									onComplete: function(jsonObj) {
										$('jsn-checkresult').setHTML('');
										if(jsonObj.isDir) {
											if(jsonObj.isWritable) {
												resultMsg.setProperty('color', 'green').setHTML('".JText::_('FOLDER_EXISTS_WRITABLE')."');
											} else {
												resultMsg.setProperty('color', 'red').setHTML('".JText::_('FOLDER_NOT_WRITABLE')."');
											}
										} else {
											resultMsg.setProperty('color', 'red').setHTML('".JText::_('FOLDER_NOT_EXISTS')."');	
										}
										resultMsg.injectInside($('jsn-checkresult'));
									}
							}).send();
						});
					});
				");
			} else if ($mootool_version == '1.2') {
				$doc->addScriptDeclaration("
					window.addEvent('domready', function(){ 
						$('paramscacheFolder').addEvents({
							keyup: function()
							{
								$('paramscacheFolder').fireEvent('hideCheckLink');
							},
							keydown: function()
							{
								$('paramscacheFolder').fireEvent('hideCheckLink');
							},
							hideCheckLink: function()
							{
								if( $('paramscacheFolder').value == '') 
								{
									$('jsncheckcache').set('styles', {'visibility': 'hidden'});
								} else {
									$('jsncheckcache').set('styles', {'visibility': 'visible'});
								}
							}
						});	
						$('jsncheckcache').addEvent('click', function() {
							actionUrl = '".JURI::root()."index.php';
							var resultMsg = new Element('font');
							var jsonRequest = new Request.JSON({url: actionUrl, onSuccess: function(jsonObj){
								$('jsn-checkresult').setHTML('');
								if(jsonObj.isDir) {
									if(jsonObj.isWritable) {
										resultMsg.set({color: 'green', text: '".JText::_('FOLDER_EXISTS_WRITABLE')."'});
									} else {
										resultMsg.set({color: 'red', text: '".JText::_('FOLDER_NOT_WRITABLE')."'});
									}
								} else {
									resultMsg.set({color: 'red', text: '".JText::_('FOLDER_NOT_EXISTS')."'});	
								}
								resultMsg.inject($('jsn-checkresult'));
							}}).get({'template': '".$this->_template_name."', 'tmpl': 'jsn_runajax', 'cache_folder': '".$root_folder."' + $('paramscacheFolder').value , 'task': 'checkCacheFolder'});
						
						});
						
					});
				");
			}
			$output = '<input type="text" name="'.$this->_control_name.'['.$this->_name.']" id="'.$this->_control_name.$this->_name.'" value="'.$value.'" '.$class.' '.$size.' '.$disabled.' />&nbsp;'.$php_warning.'&nbsp;<a id="jsncheckcache" class="jsn-button-select" href="javascript:void(0)">'.JText::_('CHECK_CACHE_FOLDER').'</a><span id="jsn-checkresult"></span>';
		} else {
			$output = '<input type="text" name="'.$this->_control_name.'['.$this->_name.']" id="'.$this->_control_name.$this->_name.'" value="'.$value.'" '.$class.' '.$size.' '.$disabled.' />&nbsp;'.$php_warning.'&nbsp;<span class="link-disabled">'.JText::_('CHECK_CACHE_FOLDER').'</span>';
		}
		
		return $output;
	}
	
	function jsnAbout()
	{		
		$obj_jsn_utils 	  =& JSNUtils::getInstance();
		$doc			  =& JFactory::getDocument();
		$mootool_version  = $obj_jsn_utils->checkMootoolVersion();
		$tell_more	      = '';
					
		$result 			   = $obj_jsn_utils->getTemplateDetails( $this->_template_path_of_base, $this->_template_name);
		$template_version	   = $result->version;	
		
		$template_name	       = $result->name;
				
		$template_name         = str_replace('_', ' ', $template_name);				   		
		$template_edition 	   = strtolower($this->_template_edition);
		$template_name 		   = $template_name.' '.strtoupper($template_edition); 
		
		if($mootool_version == '1.1') {
				$doc->addScriptDeclaration("
					window.addEvent('domready', function(){
						$('jsn-check-version').addEvent('click', function() {
						    $('jsn-check-version').setHTML('');				   
						    var resultVersionMsg = new Element('span');
							var actionVersionUrl = '".JURI::root()."index.php?template=$this->_template_name&tmpl=jsn_runajax&task=checkVersion';
							resultVersionMsg.setProperty('class', 'jsn-version-checking').setHTML('".JText::_('CHECKING')."');
							resultVersionMsg.injectInside($('jsn-check-version-result'));	
							var request = new Json.Remote(actionVersionUrl, {
									onComplete: function(jsonObj) {
										if(jsonObj.connection) {
											if(jsonObj.version == '".$template_version."') {
												resultVersionMsg.setProperty('class', 'jsn-latest-version').setHTML('".JText::_('THE LATEST VERSION')."');	
											} else {
												resultVersionMsg.setProperty('class', 'jsn-outdated-version').setHTML('".JText::_('OUTDATE VERSION')." <span class=\"jsn-newer-version\">' + jsonObj.version + '. </span>' + '".JText::_('CHECK DETAILS')."');
											}
										} else {
											resultVersionMsg.setProperty('class', 'jsn-connection-fail').setHTML('".JText::_('CONNECTION FAILED')."');	
										}
										resultVersionMsg.injectInside($('jsn-check-version-result'));
									}
							}).send();
						});
					});
				");
			} 
			else if ($mootool_version == '1.2') 
			{
				$doc->addScriptDeclaration("
					window.addEvent('domready', function(){	
						$('jsn-check-version').addEvent('click', function() {
						   $('jsn-check-version').set('html', '');							   	
							var actionVersionUrl = '".JURI::root()."index.php';
							var resultVersionMsg = new Element('span');
							resultVersionMsg.set('class','jsn-version-checking');
							resultVersionMsg.set('html','".JText::_('CHECKING')."');
							resultVersionMsg.inject($('jsn-check-version-result'));		
							var jsonRequest = new Request.JSON({url: actionVersionUrl, onSuccess: function(jsonObj){
								if(jsonObj.connection) {
									if(jsonObj.version == '".$template_version."') {
										resultVersionMsg.set('class','jsn-latest-version');
										resultVersionMsg.set('html','".JText::_('THE LATEST VERSION')."');
									} else {
										resultVersionMsg.set('class','jsn-outdated-version');
										resultVersionMsg.set('html','".JText::_('OUTDATE VERSION')." <span class=\"jsn-newer-version\">' + jsonObj.version + '. </span>' + '".JText::_('CHECK DETAILS')."');
									}
								} else {
									resultVersionMsg.set('class','jsn-connection-fail');
									resultVersionMsg.set('html','".JText::_('CONNECTION FAILED')."');
								}
								resultVersionMsg.inject($('jsn-check-version-result'));
							}}).get({'template': '".$this->_template_name."', 'tmpl': 'jsn_runajax', 'task': 'checkVersion'});						
						});						
					});
					
				");
			}
					
		if($template_edition == 'standard')
		{
			$tell_more = '<p>'.JText::_('UPGRADE TO UNLIMITED').'</p>';
		}
		$html   = '</td><td></tr><tr class="jsn-product-about"><td width="10" valign="top">';
		$html  .= '<img src ="../templates/'.$this->_template_name.'/template_thumbnail.png" width="206" height="150" />';
		$html  .= '</td><td valign="top">';
		$html  .= '<h2><a href="http://www.joomlashine.com/joomla-templates/jsn-epic.html" target="_blank">'.$template_name.'</a></h2>'.$tell_more.'<hr />';
		$html  .= '<dl>';
		$html  .= '<dt>'.JText::_('VERSION').':</dt><dd><strong class="jsn-current-version">'.$template_version.'</strong> - <a href="javascript:void(0);" class="link-action" id="jsn-check-version">'.JText::_('CHECK_FOR_UPDATE').'</a><span id="jsn-check-version-result"></span></dd>';
		$html  .= '<dt>'.JText::_('AUTHOR').':</dt><dd><a target="_blank" title="JoomlaShine.com" href="'.$result->authorUrl.'">'.$result->author.'</a></dd>';
		$html  .= '<dt>'.JText::_('COPYRIGHT').':</dt><dd>'.$result->copyright.'</dd>';
		$html  .= '</dl>';
		$html  .= '</td></tr>';
		return $html;
	}	
}
?>