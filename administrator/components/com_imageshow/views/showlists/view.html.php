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
jimport( 'joomla.application.component.view');

class ImageShowViewShowLists extends JView{

	function display($tpl = null)
	{
		global $mainframe, $option;	
		
		JHTML::script('jsn_is_imageshow.js','administrator/components/com_imageshow/assets/js/');
		JHTML::stylesheet('imageshow.css','administrator/components/com_imageshow/assets/css/');
		JHTML::stylesheet('mediamanager.css','administrator/components/com_imageshow/assets/css/');
		$objJSNShowlist 	= JSNISFactory::getObj('classes.jsn_is_showlist');
		$task 				= JRequest::getString('task');
		
		if($task !='element' && $task !='elements')
		{
			$objJSNShowlist->checkShowlistLimition();
		}
				
		$list 				= array();
		$model 				= $this->getModel();
		
		$filterState 		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_state', 'filter_state', '', 'word' );
		$filterOrder		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_order','filter_order', '', 'cmd' );
		$filterOrderDir		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		$showlistTitle 		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.showlist_stitle', 'showlist_stitle', '', 'string' );
		$showlistAccess		= $mainframe->getUserStateFromRequest( 'com_imageshow.showlist.showlist_access', 'access', '', 'string' );
		
		$type = array(0 => array('value'=>'', 'text'=>'- Published -'), 1 => array('value'=>'P', 'text'=>'Yes'), 2 => array('value'=>'U', 'text'=>'No'));	
		
		$lists['type'] 		= JHTML::_('select.genericList', $type, 'filter_state', 'id="filter_state" class="inputbox" onchange="document.adminForm.submit( );"'. '', 'value', 'text', $filterState);	
		$lists['state']		= JHTML::_('grid.state',  $filterState );
		$lists['access']	= $model->accesslevel($showlistAccess);
		$lists['showlistTitle'] 	= $showlistTitle;	
		$lists['order_Dir'] 		= $filterOrderDir;
		$lists['order'] 			= $filterOrder;	
			
		$items		= & $this->get( 'Data' );
		$total		= & $this->get( 'Total' );
		$pagination = & $this->get( 'Pagination' );
	
		$this->assignRef('lists',		$lists);
		$this->assignRef('total',		$total);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);			
		parent::display($tpl);
	}
}
