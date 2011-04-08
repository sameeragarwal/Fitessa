<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$showlistID = JRequest::getInt('showlist_id');
$showcaseID = JRequest::getInt('showcase_id');
$pluginInfo = $this->pluginContentInfo;
?>
<div class="jsnis-plugin-details">
<h3><?php echo JText::_('PLUGIN SYSTAX DETAILS'); ?></h3>
<?php
echo JText::_('PLEASE INSERT FOLLOWING TEXT TO YOUR ARTICLE AT THE POSITION WHERE YOU WANT TO SHOW GALLERY');
echo '<p>{imageshow sl='.$showlistID.' sc='.$showcaseID.' /}</p>';
echo JText::sprintf('MORE DETAILS ABOUT PLUGIN SYNTAX CAN BE FOUND IN PLUGIN SETTINGS PAGE', 'index.php?option=com_plugins&view=plugin&client=site&task=edit&cid[]='.@$pluginInfo[0]);
?>
</div>
