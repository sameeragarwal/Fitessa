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
JToolBarHelper::title( JText::_('JSN IMAGESHOW').': '.JText::_('INSTALLER'));
$checkTheme = false;
$number		= '3';
$step1		= '';
$step2		= '';
$step3		= '';
$enabled	= ' enabled';
$task 		= JRequest::getString('task');
$status 	= JRequest::getString('status');
if(!$this->model->checkThemePlugin())
{
	$link = 'index.php?option=com_imageshow&controller=installer&task=installtheme';
}
else 
{
	if($status != 'installed')
	{
		$checkTheme = true;
		$number		= '2';	
	}
}

switch ($task)
{
	case 'installcore':
		$step1 = $enabled;
	break;	
	case 'installtheme':	
		$step2 = $enabled;
	break;	
	case 'installsuccessfully':	
		$step3 = $enabled;
	break;	
	default:
	JRequest::setVar('layout', 'default');	
	break;
}
?>
<script type="text/javascript">
 	function redirectToInstallPage()
 	{
 		window.location.href = 'index.php?option=com_installer';
 	}
 	
 	function redirectToImageShowPage()
 	{
 		window.location.href = 'index.php?option=com_imageshow&controller=installer&task=finish';
 	} 	
</script>