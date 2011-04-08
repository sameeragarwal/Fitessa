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

class JSNISShow 
{
	var $_db = null;
	
	function JSNISShow()
	{
		if ($this->_db == null)
		{
			$this->_db =& JFactory::getDBO(); 
		}
	}
	
	function &getInstance()
	{
		static $instanceShow;
		if ($instanceShow == null)
		{
			$instanceShow = new JSNISShow();
		}
		return $instanceShow;
	}

	function getArticleAlternate($showlistID)
	{
		$query 	= 'SELECT c.introtext, c.fulltext 
				   FROM #__imageshow_showlist sl 
				   INNER JOIN #__content c ON sl.alter_id = c.id 
				   WHERE sl.showlist_id='.$showlistID;
		$this->_db->setQuery($query);
		
		return $this->_db->loadAssoc();
	}
	
	function getModuleAlternate($showlistID)
	{
		$query = 'SELECT m.* 
				  FROM #__imageshow_showlist sl 
				  INNER JOIN #__modules m ON sl.alter_module_id = m.id 
				  WHERE sl.showlist_id = '.(int)$showlistID;
		
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}
	
	function getModuleSEO($showlistID)
	{
		$query = 'SELECT m.* 
				  FROM #__imageshow_showlist sl 
				  INNER JOIN #__modules m ON sl.seo_module_id = m.id 
				  WHERE sl.showlist_id = '.(int)$showlistID;
		
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}
	
	function getArticleSEO($showlistID)
	{
		$query = 'SELECT c.introtext, c.fulltext 
		          FROM #__imageshow_showlist sl 
		          INNER JOIN #__content c ON sl.seo_article_id = c.id 
		          WHERE sl.showlist_id='.$showlistID;
		$this->_db->setQuery($query);
		return $this->_db->loadAssoc();
	}
	
	function getArticleAuth($showlistID)
	{
		$query 	= 'SELECT c.introtext, c.fulltext 
				   FROM #__imageshow_showlist sl 
				   INNER JOIN #__content c ON sl.alter_autid = c.id 
				   WHERE sl.showlist_id='.$showlistID;
		$this->_db->setQuery($query);
		
		return $this->_db->loadAssoc();		
	}
		
	function getModuleByID($ID)
	{
		$query = 'SELECT id, title, module, position, content, showtitle, control, params FROM #__modules WHERE id = '.(int)$ID;
		$this->_db->setQuery($query);
		$row 			= $this->_db->loadObject();	
		$file 			= $row->module;
		$custom         = substr($file, 0, 4) == 'mod_' ?  0 : 1;
	    $row->user      = $custom;
	    $row->name      = $custom ? $row->title : substr($file, 4);
	    return $row;
	}
	
	function renderAlternativeImage($path)
	{
		jimport('joomla.filesystem.file');
		
		$rootPath 	= JPATH_ROOT;	
		$imagePath 	= $rootPath.DS.str_replace('/', DS, $path);
		$dimension	= array();	
		
		if (JFile::exists($imagePath)) 
		{		
			list($width, $height) = @getimagesize($imagePath);
			$dimension ['width']  = $width;
			$dimension ['height'] = $height;
		}
		return $dimension;		
	}
}	