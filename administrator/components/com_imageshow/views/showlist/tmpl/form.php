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
jimport('joomla.html.pane');
$task = JRequest::getVar('task');
$pane =& JPane::getInstance('Sliders', array('allowAllClose' => true));
echo $this->loadTemplate('showlist');
echo "<div class=\"jsnis-dgrey-heading jsnis-dgrey-heading-style\"><h3 class=\"jsnis-element-heading\">".JText::_('TITLE SHOWLIST IMAGES')."</h3></div>";
		if($task == 'add'){
			echo "<div id=\"jsnis-no-showlist\"><p>".JText::_('PLEASE SAVE THIS SHOWLIST BEFORE SELECTING IMAGES')."</p></div>";
		}else{
			echo $this->loadTemplate('flex');
		}
include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php'); 