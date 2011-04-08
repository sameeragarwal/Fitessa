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
class JElementJsnshowcase extends JElement
{
	var	$_name = 'JsnShowCase';
	function fetchElement($name, $value, &$node, $control_name)
	{
		$showcaseID = JRequest::getInt('showcase_id');
		
		if ($showcaseID != 0)
		{
			$value = $showcaseID;
		}
				
		$db = &JFactory::getDBO();
		JHTML::stylesheet('style.css','modules/mod_imageshow/assets/css/');
		JHTML::script('jsnis_module.js','modules/mod_imageshow/assets/js/');
        //build the list of categories
		$query = 'SELECT a.showcase_title AS text, a.showcase_id AS id'
		. ' FROM #__imageshow_showcase AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );	
		$results[] = JHTML::_('select.option', '0', JText::_( '- Select Showcase -' ), 'id', 'text' );
		$results = array_merge( $results, $db->loadObjectList() ); 

		$html  = "<div id='jsn-showcase-icon-warning'>";
		$html .= JHTML::_('select.genericList', $results, ''.$control_name.'['.$name.']', 'class="inputbox jsn-select-value"', 'id', 'text', $value, $control_name.$name);
		$html .= "<span class=\"jsn-icon-warning\"><span class=\"jsn-tooltip-wrap\"><span class=\"jsn-tooltip-anchor\"></span><p class=\"jsn-tooltip-title\">".JText::_('TITLE SHOWCASE WARNING')."</p>".JText::_('DES SHOWCASE WARNING')."</span>";
		$html .= "</div>";

		return $html;
	}
}
?>