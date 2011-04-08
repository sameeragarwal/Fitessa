<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
if ( ! defined('replaceJSNSeparator') ){
	function replaceJSNSeparator($result){
		$top 		= '<a><span class="separator">';
		$bottom 	= '</span></span></a>';
		$result 	= str_replace('<span class="separator">','<a><span class="separator">', $result);
		$result 	= str_replace('</span></span>', '</span></span></a>', $result);
		$result 	= str_replace('</a></span>', '</span></a>', $result);
		$result 	= str_replace('</span></a></a>', '</span></a>', $result);
		return $result; 
	}
	define('replaceJSNSeparator', true);
}
if ( ! defined('modMainMenuXMLCallbackDefined') ) {
	function modMainMenuXMLCallback(&$node, $args)
	{
		global $jsn_richmenu_separator;
		$user	= &JFactory::getUser();
		$menu	= &JSite::getMenu();
		$active	= $menu->getActive();
		$path	= isset($active) ? array_reverse($active->tree) : null;
		$class_array = array();

		if (($args['end']) && ($node->attributes('level') >= $args['end']))
		{
			$children = $node->children();
			foreach ($node->children() as $child)
			{
				if ($child->name() == 'ul') {
					$node->removeChild($child);
				}
			}
		}

		if ($node->name() == 'ul') {
			$subitem_index = 0;
			$subitems_count = count($node->children());
			foreach ($node->children() as $child)
			{
				if ($child->attributes('access') > $user->get('aid', 0))
				{
					$node->removeChild($child);
					$subitems_count = $subitems_count - 1;
				}
				else
				{
					if ($subitem_index == 0) $node->_children[$subitem_index]->addAttribute('first', 1);
					$order = $subitem_index + 1;
					$node->_children[$subitem_index]->addAttribute('order', 'order'.$order);
					if ($subitem_index == $subitems_count - 1) $node->_children[$subitem_index]->addAttribute('last', 1);
					$subitem_index++;
				}				
			}
		}

		// Process parent item
		if (($node->name() == 'li') && isset($node->ul)) {
			$class_array[] = 'parent';
		}

		// Process active item
		if (isset($path) && in_array($node->attributes('id'), $path))
		{
			$class_array[] = 'active';
		}
		else
		{
			if (isset($args['children']) && !$args['children'])
			{
				$children = $node->children();
				foreach ($node->children() as $child)
				{
					if ($child->name() == 'ul') {
						$node->removeChild($child);
					}
				}
			}
		}
		
		// Process regular item
		if(isset($jsn_richmenu_separator) && $jsn_richmenu_separator !='')
		{
			if ( isset( $node->_children[0] ) && isset( $node->_children[0]->span[0] ) ) 
			{
				$root_span 	= $node->_children[0]->span[0];
				$title 		= $root_span->data();
				if( strlen( $title ) > 0 )
				{
					$position = strpos ( $title, $jsn_richmenu_separator );
					if( $position != false )
					{					
						$sub_string_last 	= substr( $title, $position + 3 );
						$sub_string_first 	= substr( $title, 0, $position );
						$span =& $node->_children[0]->span[0]->addChild( 'span', array( 'class' => 'jsn-menutitle' ) );
						$span->setData( trim( $sub_string_first ).' ' );					
						$span =& $node->_children[0]->span[0]->addChild( 'span', array( 'class' => 'jsn-menudescription' ) );
						$span->setData( trim( $sub_string_last ) );
					}
				}			
			}
		}
		if (($node->name() == 'li') && ($id = $node->attributes('id'))) {
			$item = $menu->getItem($node->attributes('id'));
			$class_array[] = 'item'.$id;
			if ($node->attributes('order')) $class_array[] = $node->attributes('order');
			if ($node->attributes('first')) $class_array[] = 'first';
			if ($node->attributes('last')) $class_array[] = 'last';
		}
		if (isset($path) && $node->attributes('id') == $path[0]){
			$class_array[] = 'current';
			$node->_children[0]->addAttribute('class', 'current');
		}
		// Setup class
		if($node->name() == 'li'){
			$item_class = implode(" ", $class_array);
			$node->addAttribute('class', $item_class);
		}

		// Clear attributes
		$node->removeAttribute('id');
		$node->removeAttribute('level');
		$node->removeAttribute('access');
		$node->removeAttribute('first');
		$node->removeAttribute('last');
		$node->removeAttribute('order');
		unset($class_array);
	}
	define('modMainMenuXMLCallbackDefined', true);
}
ob_start();
modMainMenuHelper::render($params, 'modMainMenuXMLCallback');
$menu_html = ob_get_contents();
ob_end_clean();
echo replaceJSNSeparator($menu_html);
