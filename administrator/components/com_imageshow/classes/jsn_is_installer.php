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
jimport('joomla.installer.installer');
class JSNISInstaller extends JInstaller 
{
	function __construct()
	{
		parent::__construct();
	}
	
	function &getInstance()
	{
		static $instance;

		if (!isset ($instance)) 
		{
			$instance = new JSNISInstaller();
		}
		return $instance;
	}
	
	function setAdapter($name, $adapter = null)
	{
		if (!is_object($adapter))
		{
			// Try to load the adapter object
			require_once(JPATH_COMPONENT.DS.'adapters'.DS.strtolower($name).'.php');
			$class = 'JSNISInstaller'.ucfirst($name);
			
			if (!class_exists($class)) 
			{
				return false;
			}
			
			$adapter = new $class($this);
			$adapter->parent =& $this;
		}
		$this->_adapters[$name] =& $adapter;
		return true;
	}
			
	function setupInstall()
	{
		if (!$this->_findManifest()) 
		{
			return false;
		}

		$root =& $this->_manifest->document;
		$type = $root->attributes('type');
		$group = $root->attributes('group');

		if($type != 'plugin' || $group != 'jsnimageshow')
		{
			return false;
		}
		// Lazy load the adapter
		if (!isset($this->_adapters[$type]) || !is_object($this->_adapters[$type])) 
		{
			if (!$this->setAdapter($type)) 
			{
				return false;
			}
		}

		return true;
	}

	function install($path=null)
	{
		if ($path && JFolder::exists($path)) 
		{
			$this->setPath('source', $path);
		}
		else 
		{
			$this->abort(JText::_('Install path does not exist'));
			return false;
		}

		if (!$this->setupInstall()) 
		{
			$this->abort(JText::_('ERRORNOTFINDJOOMLAXMLSETUPFILE'));
			return false;
		}

		$root		=& $this->_manifest->document;
		$version	= $root->attributes('version');
		$rootName	= $root->name();
		$config		= &JFactory::getConfig();
		if ((version_compare($version, '1.5', '<') || $rootName == 'mosinstall')) 
		{
			$this->abort(JText::_('MUSTENABLELEGACY'));
			return false;
		}

		$type  = $root->attributes('type');
		$group = $root->attributes('group');
		
		if ($type != 'plugin' || $group != 'jsnimageshow')
		{
			return false;
		}
		
		if (is_object($this->_adapters[$type])) 
		{
			return $this->_adapters[$type]->install();
		}
		return false;
	}
}
?>