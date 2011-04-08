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
if(count($this->allContentTips)){
	foreach($this->allContentTips as $value){
?>
	<div><h3><?php echo $value['title']; ?></h3><p><?php echo $value['content']; ?></p></div>
<?php
		}
?>	
<?php 
}
?>
