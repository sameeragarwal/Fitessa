<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewAbout extends JView
{
	function display($tpl = null) 
	{
		global $mainframe, $option;
		JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');	
		JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
		
		$objJSNXML 		  = JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$objJSNUtils      = JSNISFactory::getObj('classes.jsn_is_utils');
		$doc			  =& JFactory::getDocument();
		$infoXmlDetail    = $objJSNXML->parserXMLDetails();	
		$mootoolVersion   = $objJSNUtils->checkMootoolVersion();
		
		if($mootoolVersion == '1.1') 
		{
			$doc->addScriptDeclaration("
				window.addEvent('domready', function(){
					$('jsn-check-version').addEvent('click', function() {
					    $('jsn-check-version').setHTML('');				   
					    var resultVersionMsg = new Element('span');
						var actionVersionUrl = 'index.php?option=com_imageshow&controller=ajax&task=checkVersion';
						resultVersionMsg.setProperty('class', 'jsn-version-checking').setHTML('".JText::_('CHECKING')."');
						resultVersionMsg.injectInside($('jsn-check-version-result'));	
						var request = new Json.Remote(actionVersionUrl, {
								onComplete: function(jsonObj) {
									if(jsonObj.connection) {
										if(jsonObj.version == '".trim($infoXmlDetail['version'])."') {
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
		else if ($mootoolVersion == '1.2') 
		{
			$doc->addScriptDeclaration("
				window.addEvent('domready', function(){	
					$('jsn-check-version').addEvent('click', function() {
					   $('jsn-check-version').set('html', '');							   	
						var actionVersionUrl = 'index.php';
						var resultVersionMsg = new Element('span');
						resultVersionMsg.set('class','jsn-version-checking');
						resultVersionMsg.set('html','".JText::_('CHECKING')."');
						resultVersionMsg.inject($('jsn-check-version-result'));		
						var jsonRequest = new Request.JSON({url: actionVersionUrl, onSuccess: function(jsonObj){
							if(jsonObj.connection) {
								if(jsonObj.version == '".trim($infoXmlDetail['version'])."') {
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
						}}).get({'option': 'com_imageshow', 'controller': 'ajax', 'task': 'checkVersion'});						
					});						
				});
				
			");
		}
				
		$params		         =& JComponentHelper::getParams('com_imageshow');
		$this->assignRef('infoXmlDetail',$infoXmlDetail);
		$this->assignRef('params',$params);		
		parent::display($tpl);
		
	}
}