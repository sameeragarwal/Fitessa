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
jimport( 'joomla.application.component.controller' );

class ImageShowControllerShowCase extends JController 
{
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('add',  'display');
		$this->registerTask('edit', 'display');
		$this->registerTask('showcaseinstalltheme', 'display');
		$this->registerTask('apply', 'save');
		$this->registerTask('switchTheme', 'switchTheme');
	}
	
	function display( ) 
	{		
		switch(strtolower($this->getTask()))
		{
			case 'add':
			{
				JRequest::setVar('hidemainmenu', 1);
				JRequest::setVar('layout', 'form');
				JRequest::setVar('view', 'showcase');
				JRequest::setVar('edit', false );
				JRequest::setVar('model', 'showcase');	
			} 
			break;
			case 'edit':
			{
				JRequest::setVar('hidemainmenu', 1);
				JRequest::setVar('layout', 'form');
				JRequest::setVar('view', 'showcase');
				JRequest::setVar('edit', true);
				JRequest::setVar('model', 'showcase');
			} 
			break;
			case 'showcaseinstalltheme':	
				JRequest::setVar('layout', 'showcaseinstalltheme');
				JRequest::setVar('view', 'showcase');
				JRequest::setVar('model', 'installer');
			break;	
			default:	
				JRequest::setVar('layout', 'default');
				JRequest::setVar('view', 'showcases');
				JRequest::setVar('model', 'showcases');
		}
		
		parent::display();	
	}

	function save()
	{
		global $objectLog;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$objJSNShowcase 	 = JSNISFactory::getObj('classes.jsn_is_showcase');			
		$date 				 = & JFactory::getDate();
		$user				 = & JFactory::getUser();
		$userID				 = $user->get ('id');
		$db					 = & JFactory::getDBO();
		$nullDate			 = $db->getNullDate();
		$post				 = JRequest::get('post');		
		$cid				 = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['showcase_id'] = (int) $cid[0];
		
		$count   		= $objJSNShowcase->countShowcase();
		$arrayID 		= $objJSNShowcase->getShowcaseID();
		$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
		$hashString 	= $objJSNUtils->checkHashString();
		
		if($count[0] >= 3 && $hashString == false)
		{
			if(!in_array((int)$cid[0], $arrayID))
			{
				$this->setRedirect('index.php?option=com_imageshow&controller=showcase');
				return false;	
			}
		}

			
		if($cid[0] == '' or $cid[0] == 0)
		{
			$post['date_created']	= JHTML::_('date', $date->toUnix(), '%Y-%m-%d %H:%M:%S');
			$post['date_modified']	= $nullDate;
		}
		else
		{
			$post['date_modified']	= JHTML::_('date', $date->toUnix(), '%Y-%m-%d %H:%M:%S');
		}
		
		$objJSNShowcaseTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		$objJSNShowcaseTheme->importTableByThemeName($post['theme_name']);
		$objJSNShowcaseTheme->importModelByThemeName($post['theme_name']);
		
		$modelShowcaseTheme = JModel::getInstance($post['theme_name']);
		
		if($modelShowcaseTheme)
		{
			$post = $modelShowcaseTheme->_prepareSaveData($post);	
		}
		
		$showcaseThemeTable =& JTable::getInstance($post['theme_name'], 'Table');
		
		if(!$showcaseThemeTable)
		{
			$msg = JText::_('SHOWCASE THEME TABLE NOT EXISTS');
			$link = 'index.php?option=com_imageshow&controller=showcase';
			$this->setRedirect($link, $msg);
			return true;
		}
		
		$showcaseThemeTable->bind($post);
		
		if($showcaseThemeTable->store())
		{
			$post['theme_id'] 	= $showcaseThemeTable->theme_id;
			$showcaseTable 		=& JTable::getInstance('showcase', 'Table');
			$showcaseTable->bind($post);
			
			if ($showcaseTable->store($post)) 
			{
				if($post['showcase_id']==0 or $post['showcase_id'] =='')
				{
					$objectLog->addLog($userID, JRequest::getURI(), JRequest::getVar('showcase_title'),'showcase','add');
				}
				else
				{
					if($this->getTask() == 'save')
					{
						$objectLog->addLog($userID, JRequest::getURI(), JRequest::getVar('showcase_title'),'showcase','modify');
					}
				}
				
				switch ($this->getTask()) 
				{
					case 'apply':
						$msg  = JText::_('SUCCESSFULLY SAVED CHANGES');
						$link = 'index.php?option=com_imageshow&controller=showcase&task=edit&theme='.strtolower($post['theme_name']).'&cid[]='. $showcaseTable->showcase_id;
						break;			
					default:
						$msg  = JText::_('SUCCESSFULLY CREATED');
						$link = 'index.php?option=com_imageshow&controller=showcase';
						if (isset($post['jsn-menu-link-redirect']))
						{
							$msg = '';
							$link = ($post['jsn-menu-link-redirect'] != '') ? $post['jsn-menu-link-redirect'] : $link;
						}
						break;
				}		
			}
			else 
			{
				$msg = JText::_('ERROR SAVING SHOWCASE');
			}
		}
		else
		{
			$msg = JText::_('ERROR SAVING SHOWCASE');
		}
		
		$this->setRedirect($link, $msg);

	}
		
	function remove()
	{
		global $mainframe, $objectLog;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$user					=& JFactory::getUser();
		$userID					= $user->get ('id');
		$cid 					= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$showcaseTable 			=& JTable::getInstance('showcase', 'Table');
		$objJSNShowcaseTheme 	= JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		
		JArrayHelper::toInteger($cid);
		
		if (count( $cid ) < 1) 
		{
			JError::raiseError(500, JText::_('PLEASE MAKE A SELECTION FROM THE LIST TO').' '.JText::_('DELETE'));
		}
		
		if (count($cid) == 1)
		{
			$objJSNShowcase = JSNISFactory::getObj('classes.jsn_is_showcase');
			$showcaseInfo = $objJSNShowcase->getShowCaseTitle($cid[0]);				
		}

		foreach ($cid as $id)
		{
			if ($showcaseTable->load($id))
			{
				$objJSNShowcaseTheme->importTableByThemeName($showcaseTable->theme_name);
				$showcaseThemeTable =& JTable::getInstance($showcaseTable->theme_name, 'Table');
				
				if ($showcaseThemeTable->load((int) $showcaseTable->theme_id))
				{
					if ($showcaseThemeTable->delete((int) $showcaseTable->theme_id))
					{
						$showcaseTable->delete($id);
					}
				}	
			}
		}
		
		if (count($cid) == 1)
		{
			$objectLog->addLog($userID, JRequest::getURI(), $showcaseInfo['showcase_title'], 'showcase', 'delete');
		}
		else
		{
			$objectLog->addLog($userID, JRequest::getURI(), count($cid), 'showcase', 'delete');
		}
		
		$mainframe->redirect('index.php?option=com_imageshow&controller=showcase');		
	}
	
	function publish()
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		
		if (count( $cid ) < 1) 
		{
			JError::raiseError(500, JText::_( JText::_( 'SELECT AN ITEM TO PUBLISH' )) );
		}
		
		$model = $this->getModel('showcases');
		
		if (!$model->approve($cid, 1)) 
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$mainframe->redirect('index.php?option=com_imageshow&controller=showcase');		
	}

	function unpublish()
	{	
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) 
		{
			JError::raiseError(500, JText::_( 'SELECT AN ITEM TO UNPUBLISH' ) );
		}
		
		$model = $this->getModel('showcases');
		
		if (!$model->approve($cid, 0)) 
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		
		$mainframe->redirect('index.php?option=com_imageshow&controller=showcase');
	}
	
	function cancel()
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$mainframe->redirect('index.php?option=com_imageshow&controller=showcase');
	}
	function saveOrder()
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$total		= count($cid);
		$conditions	= array ();
		$row 		= & JTable::getInstance('showcase','Table');
		
		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		
		for ($i = 0; $i < $total; $i ++)
		{
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i]) 
			{
				$row->ordering = $order[$i];
				
				if (!$row->store()) 
				{
					JError::raiseError( 500, $db->getErrorMsg() );
					return false;
				}
			}
		}
		
		$msg = JText::_('New ordering saved');
		$mainframe->redirect('index.php?option=com_imageshow&controller=showcase', $msg);		
	}
	
	function orderup()
	{
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		JArrayHelper::toInteger($cid, array(0));		
		$this->orderCategory($cid[0], -1);
	}
	
	function orderdown()
	{
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		$this->orderCategory($cid[0], 1);	
	}
	
	function orderCategory( $uid, $inc)
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );	
		$db =& JFactory::getDBO();
		$row = & JTable::getInstance('showcase','Table');
		$row->load( $uid );
		$row->move( $inc);
		$msg = JText::_('New ordering saved');
		$mainframe->redirect('index.php?option=com_imageshow&controller=showcase', $msg);		
	}
	
	function copy()
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$objJSNShowcase 	= JSNISFactory::getObj('classes.jsn_is_showcase');
		$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
		$hashString 		= $objJSNUtils->checkHashString();
		$totalShowcase		= $objJSNShowcase->countShowcase();
		$db					= & JFactory::getDBO();
		$nullDate			= $db->getNullDate();
		$date 				=& JFactory::getDate();
		$cid 				= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		$total 				 = count($cid);
		$objJSNShowcaseTheme = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
		
		for ($i = 0; $i < $total; $i ++)
		{
			if (($totalShowcase[0] + $i >= 3) && $hashString == false)
			{
				$this->setRedirect('index.php?option=com_imageshow&controller=showcase');
				return false;	
			}
			
			if ($cid[$i])
			{
				$showcaseTable = JTable::getInstance('showcase','Table');
				$showcaseTable->load((int)$cid[$i]);
				$showcaseTable->showcase_title 	= 'Copy of '.$showcaseTable->showcase_title;
				$showcaseTable->ordering 		= 0;
				$showcaseTable->date_created	=  JHTML::_('date', $date->toUnix(), '%Y-%m-%d %H:%M:%S');	
				$showcaseTable->date_modified	=  $nullDate;
				
				$objJSNShowcaseTheme->importTableByThemeName($showcaseTable->theme_name);
				$showcaseThemeTable =& JTable::getInstance($showcaseTable->theme_name, 'Table');
				
				if ($showcaseThemeTable->load((int) $showcaseTable->theme_id))
				{
					$showcaseThemeTable->theme_id = null;

					if ($showcaseThemeTable->store())
					{
						$showcaseTable->showcase_id 	= null;
						$showcaseTable->theme_id 		= $showcaseThemeTable->theme_id;
						
						if (!$showcaseTable->store())
						{
							JError::raiseError( 500, $showcaseTable->getError() );
							return false;
						}
					}
				}
				
				$showcaseTable->reorder();	
			}
		}
		$msg = JText::_('Item(s) successfully copied');
		$mainframe->redirect('index.php?option=com_imageshow&controller=showcase', $msg);		
	}
	
	function switchTheme()
	{
		global $mainframe;
		$session 			=& JFactory::getSession();
		$post 				= JRequest::get('post');	
		$session->set('showcaseThemeSession', $post);
		$currentRequest = str_replace('&task=switchtheme', '', $post['redirectLinkTheme']);
		$currentRequest = str_replace('&subtask', '&task', $currentRequest);
		$mainframe->redirect($currentRequest);
	}
	
	function refreshListThemes()
	{
		global $mainframe;
		
		$session 	=& JFactory::getSession();
		$post 		=	JRequest::get('post');
		$session->set('showcaseThemeSession', $post);
		$mainframe->redirect($post['redirectLinkTheme']);
	}
	
	function installShowcaseTheme()
	{
		$post   = JRequest::get('post');
		$model	= &$this->getModel('installer');
		$link 	= $post['redirect_link'];
		$result = $model->install();
		
		if ($result)
		{
			$link .= '&install=true';	
		}
		
		$this->setRedirect($link);
	}
}
?>