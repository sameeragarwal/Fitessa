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
global $cloneManifest, $mainframe, $cloneParent;
$cloneManifest = $manifest;
$cloneParent = $this->parent;
require_once dirname(__FILE__).DS.'subinstall'.DS.'subinstall.php';
require_once dirname(__FILE__).DS.'subinstall'.DS.'upgrade.helper.php';
require_once dirname(__FILE__).DS.'classes'.DS.'jsn_is_upgradedbutil.php';
@ini_set('display_errors', 0);
function com_install() {
	global $cloneManifest, $mainframe, $cloneParent;
	$session 	=& JFactory::getSession();
    $si 		= new SubInstaller();	
    $ret 		= $si->install();
	$errorArray = $si->getError();
	$session->set('jsn_install_error', $errorArray);
	$document 	 			=& $cloneManifest->document;
	$version     			=& $document->getElementByPath('version');
	$edition     			=& $document->getElementByPath('edition');
	$version     			= $version->data();
	$edition				= str_replace(' ', '_' ,JString::strtolower($edition->data()));
	
	$packageFile 			= JPATH_ROOT.DS.'tmp'.DS.'jsn_imageshow_'.$edition.'_'.$version.'_install.zip';
	$packageExtDir 			= $cloneParent->getPath('source');
	
	$flagInstallation = false;
	$disable = '';
	$resultCheckManifestFile = checkManifestFileExist();
	if($resultCheckManifestFile == true)
	{
		$objUpgradeHelper	= new JSNUpgradeHelper($cloneManifest);
		$objUpgradeHelper->executeUpgrade();
		
		$objUpgradeDBUtil		= new JSNISUpgradeDBUtil($cloneManifest);
		$objUpgradeDBUtil->executeUpgradeDB();	
	}
	if (!$cloneParent->copyManifest()) 
	{
		$cloneParent->abort(JText::_('Component').' '.JText::_('Install').': '.JText::_('Could not copy setup file'));
		return false;
	}
	removeFile($packageFile);
	removeFolder($packageExtDir);
	$mainframe->redirect('index.php?option=com_imageshow&controller=installer&task=installcore');
}

function checkManifestFileExist()
{
	jimport('joomla.filesystem.file');
	$path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'com_imageshow.xml';
	if(JFile::exists($path))
	{
		return true;
	}
	return false;
}

function removeFile($path)
{
	if (JFile::exists($path))
	{
		JFile::delete($path);
	}		
}

function removeFolder($path)
{
	if (JFolder::exists($path))
	{
		JFolder::delete($path);
	}		
}