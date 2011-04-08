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
$act = JRequest::getCmd('act','custom');
?>
<div class="item">
	<a href="index.php?option=com_imageshow&amp;controller=media&amp;view=imageslist&amp;act=<?php echo $act;?>&amp;tmpl=component&amp;event=loadMediaImagesList&amp;theme=<?php echo $this->_showcaseThemeName; ?>&amp;folder=<?php echo $this->_tmp_folder->path_relative; ?>">
		<img src="<?php echo JURI::base() ?>components/com_media/images/folder.gif" width="80" height="80" alt="<?php echo $this->_tmp_folder->name; ?>" />
		<span><?php echo $this->_tmp_folder->name; ?></span></a>
</div>
