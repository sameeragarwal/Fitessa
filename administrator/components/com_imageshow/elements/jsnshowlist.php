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
class JElementJsnshowlist extends JElement
{
	var	$_name = 'JsnShowList';
	function fetchElement($name, $value, &$node, $control_name)
	{
		$showlistID = JRequest::getInt('showlist_id');
		
		if ($showlistID != 0)
		{
			$value = $showlistID;
		}
		
		$db = &JFactory::getDBO();
		JHTML::stylesheet('style.css','modules/mod_imageshow/assets/css/');
		JHTML::script('jsnis_module.js','modules/mod_imageshow/assets/js/');
		//build the list of categories
		$query = 'SELECT a.showlist_title AS text, a.showlist_id AS id'
		. ' FROM #__imageshow_showlist AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );	
		$results[] = JHTML::_('select.option', '0', JText::_( '- Select Showlist -' ), 'id', 'text' );
		$results = array_merge( $results, $db->loadObjectList() );

		$html  = "<div id='jsn-showlist-icon-warning'>";
		$html .= JHTML::_('select.genericList', $results, ''.$control_name.'['.$name.']', 'class="inputbox jsn-select-value"', 'id', 'text', $value, $control_name.$name);
		$html .= "<span class=\"jsn-icon-warning\"><span class=\"jsn-tooltip-wrap\"><span class=\"jsn-tooltip-anchor\"></span><p class=\"jsn-tooltip-title\">".JText::_('TITLE SHOWLIST WARNING')."</p>".JText::_('DES SHOWLIST WARNING')."</span></span>";
		$html .= "</div>";

		return $html;
	}
}
?>