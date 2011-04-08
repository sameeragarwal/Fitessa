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
class ImageShowControllerShowList extends JController {

	function __construct($config = array())
	{
		parent::__construct($config);
	
		$this->registerTask( 'add',  'display' );
		$this->registerTask( 'edit', 'display' );
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'accesspublic', 'accessMenu');
		$this->registerTask( 'accessregistered', 'accessMenu');
		$this->registerTask( 'accessspecial', 'accessMenu');
	}
	
	function display( ) 
	{		
		switch($this->getTask())
		{
			case 'add' :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form' );
				JRequest::setVar( 'view', 'showlist' );
				JRequest::setVar( 'edit', false );
				JRequest::setVar( 'model', 'showlist' );				
			} 
				break;
			case 'edit' :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				JRequest::setVar( 'layout', 'form' );
				JRequest::setVar( 'view', 'showlist');
				JRequest::setVar( 'edit', true );
				JRequest::setVar( 'model', 'showlist' );
				
			} 
				break;
			case 'elements':
			{
				JRequest::setVar( 'layout', 'elements' );
				JRequest::setVar( 'view', 'showlists' );
				JRequest::setVar( 'model', 'showlists' );					
			}
				break;	
			case 'element':
			{
				JRequest::setVar( 'layout', 'element' );
				JRequest::setVar( 'view', 'showlists' );
				JRequest::setVar( 'model', 'showlists' );					
			}
				break;	
			case 'modules':
			{
				JRequest::setVar( 'layout', 'modules' );
				JRequest::setVar( 'view', 'showlist' );
				JRequest::setVar( 'model', 'showlist' );
				JRequest::setVar( 'tmpl', 'component');		
			}	
				break;		
			default:			
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'view', 'showlists' );
				JRequest::setVar( 'model', 'showlists' );
		}
		
		parent::display();	
	}
	
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		global $objectLog, $mainframe;
		$objJSNUtils 				= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNShowlist 	= JSNISFactory::getObj('classes.jsn_is_showlist');
		$date =& JFactory::getDate();
		$user	= & JFactory::getUser();
		$userID	= $user->get ('id');
		$db								= & JFactory::getDBO();
		$nullDate						= $db->getNullDate();		
		$post							= JRequest::get('post');
		$cid							= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$alternativeStatus				= JRequest::getInt('alternative_status');
		$seoStatus						= JRequest::getInt('seo_status');
		$authorizationStatus			= JRequest::getInt('authorization_status');
		$post['showlist_id'] 			= (int) $cid[0];
		$post['date_create']			= $date->toFormat();
		$post['showlist_link']			= $objJSNUtils->encodeUrl($post['showlist_link']);
		
		if ($cid[0] == '' or $cid[0] == 0)
		{
			$post['date_create']			= JHTML::_('date', $date->toUnix(), '%Y-%m-%d %H:%M:%S');
			$post['date_modified']			= $nullDate;
		}
		else
		{
			$post['date_modified']			= JHTML::_('date', $date->toUnix(), '%Y-%m-%d %H:%M:%S');
		}		
		
		if ($alternativeStatus != 2)
		{
			$post['alter_id'] = 0;
		}
		
		if ($alternativeStatus != 1)
		{
			$post['alter_module_id'] = 0;
		}
		
		if ($alternativeStatus != 3)
		{
			$post['alter_image_path'] = '';
		}
				
		if ($seoStatus != 1 && ($seoStatus == 0 || $seoStatus == 2))
		{
			$post['seo_article_id'] = 0;
		}
		
		if ($seoStatus != 2 && ($seoStatus == 1 || $seoStatus == 0))
		{
			$post['seo_module_id'] = 0;
		}
		
		if ($authorizationStatus != 1)
		{
			$post['alter_autid']			= 0;
		}
		
		$model 		= $this->getModel('showlist');
		$count   	= $objJSNShowlist->countShowlist();
		$arrayID 	= $objJSNShowlist->getShowlistID();
		$hashString = $objJSNUtils->checkHashString();
		
		if ($count[0] >= 3 && $hashString == false)
		{
			if(!in_array((int)$cid[0], $arrayID))
			{
				$this->setRedirect('index.php?option=com_imageshow&controller=showlist');
				return false;		
			}
		}
				
		if ($model->store($post)) 
		{
			if($post['showlist_id']==0 or $post['showlist_id'] =='')
			{
				$objectLog->addLog($userID, JRequest::getURI(), JRequest::getVar('showlist_title'), 'showlist', 'add');
			}
			else
			{
				if($this->getTask() == 'save')
				{
					$objectLog->addLog($userID, JRequest::getURI(), JRequest::getVar('showlist_title'), 'showlist', 'modify');
				}
			}
			switch ($this->getTask()) 
			{
				case 'apply':
					$msg  = JText::_('SUCCESSFULLY SAVED CHANGES');
					$link = 'index.php?option=com_imageshow&controller=showlist&task=edit&cid[]='. $model->_id;
					break;			
				default:
					$msg  = JText::_('SUCCESSFULLY CREATED');
					$link = 'index.php?option=com_imageshow&controller=showlist';
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
			$msg = JText::_('ERROR SAVING SHOWLIST');
		}
		
		$this->setRedirect($link, $msg);

	}
	
	function remove()
	{
		global $mainframe, $objectLog;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$user			= & JFactory::getUser();
		$objJSNShowlist	= JSNISFactory::getObj('classes.jsn_is_showlist');
		$userID			= $user->get ('id');
		$cid 			= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('PLEASE MAKE A SELECTION FROM THE LIST TO').' '.JText::_('DELETE'));
		}
		
		$model = $this->getModel('showlists');
		
		if(count($cid) == 1){
			$showlistInfo = $objJSNShowlist->getTitleShowList($cid[0]);				
		}
		
		if(!$model->delete($cid)) 
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		else
		{
			if(count($cid) == 1){
				$objectLog->addLog($userID, JRequest::getURI(), $showlistInfo[0], 'showlist', 'delete');
			}else{
				$objectLog->addLog($userID, JRequest::getURI(), count( $cid ), 'showlist', 'delete');
			}
		}
		
		$msg = JText::_('DELETE SHOWLIST SUCCESSFUL');
		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist', $msg);		
	}
	
	function publish()
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_(JText::_('SELECT AN ITEM TO PUBLISH')));
		}
		
		$model = $this->getModel('showlists');
		
		if(!$model->approve($cid, 1)) 
		{
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist');		
	}

	function unpublish()
	{	
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_('SELECT AN ITEM TO UNPUBLISH') );
		}
		
		$model = $this->getModel('showlists');
		
		if(!$model->approve($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		
		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist');
	}
	
	function cancel()
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist');
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
		$row 		= & JTable::getInstance('showlist','Table');
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
		
		$msg = JText::_('NEW ORDERING SAVED');
		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist', $msg);		
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
		$db 	=& JFactory::getDBO();
		$row 	= & JTable::getInstance('showlist','Table');
		$row->load( $uid );
		$row->move( $inc);
		$msg 	= JText::_('NEW ORDERING SAVED');
		
		$mainframe->redirect('index.php?option=com_imageshow&controller=showlist', $msg);		
	}
	
	function accessMenu() 
	{
		
		global $mainframe;
		$post			= JRequest::get('post');
		$cid			= JRequest::getVar( 'cid', array(0), 'post', 'array' );	
		
		switch ($this->getTask()) 
		{
			case 'accessregistered':
				$accessID= 1;
			break;

			case 'accessspecial':
				$accessID= 2;
			break;
			
			case 'accesspublic':
			default:
				$accessID= 0;
			break;
		}
	
		$model = $this->getModel( 'showlists' );
		$model->accessmenu($cid[0],$accessID);
		$link = 'index.php?option=com_imageshow&controller=showlist';
		$mainframe->redirect($link);
	}
	
	function imanager()
	{
		global $mainframe;
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );	
		$link 	= 'index.php?option=com_imageshow&controller=images&showlist_id=' . (int) $cid[0];
		$mainframe->redirect($link);		
	}
}
?>