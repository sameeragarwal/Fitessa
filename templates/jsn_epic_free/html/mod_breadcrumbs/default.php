<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); 
global $jsn_richmenu_separator;
?>
<span class="breadcrumbs pathway clearafter">
<?php for ($i = 0; $i < $count; $i ++) :
	if(isset($jsn_richmenu_separator) && $jsn_richmenu_separator !='')
	{
		$breadcrumb_explode_name 	= explode($jsn_richmenu_separator, $list[$i]->name);
		$breadcrumb_name			= $breadcrumb_explode_name[0];
	}
	else
	{
		$breadcrumb_name = $list[$i]->name;
	}
	// If not the last item in the breadcrumbs add the separator
	if ($i < $count-1) {
		if(!empty($list[$i]->link)) {
			echo '<a href="'.$list[$i]->link.'"'.($i==0?' class="first">':'>').$breadcrumb_name.'</a>';
		} else {
			echo '<span>'.$breadcrumb_name.'</span>';
		}
	}  else if ($params->get('showLast', 1)) { // when $i == $count -1 and 'showLast' is true
	    echo '<span class="current">'.$breadcrumb_name.'</span>';
	}
endfor; ?>
</span>