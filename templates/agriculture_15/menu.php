<?php
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
/* returns a properly nested UL menu */

//mosShowListMenu('mainmenu'); // currently hard-coded to mainmenu
ini_set('arg_separator.output','&amp;');
function mosShowListMenu($menutype) {
	global $mainframe; 
	$database = $mainframe->getCfg('db');
	$user	=& JFactory::getUser();
	$my = $user -> get('usertype');
	$cur_template = $mainframe->getTemplate();
	$Itemid = JRequest::getInt('Itemid');
	//$mosConfig_absolute_path;
	$mosConfig_live_site = $mainframe->getCfg('live_site');
	$contentConfig = &JComponentHelper::getParams( 'com_content' );
	$mosConfig_shownoauth	= !$contentConfig->get('shownoauth');    
	
	$class_sfx = null;
	error_reporting(E_ALL ^ E_NOTICE);
	$hilightid = null;

    /* If a user has signed in, get their user type */
	$intUserType = 0;
	if($my->gid){
		switch ($my->usertype)
		{
			case 'Super Administrator':
			$intUserType = 0;
			break;
			case 'Administrator':
			$intUserType = 1;
			break;
			case 'Editor':
			$intUserType = 2;
			break;
			case 'Registered':
			$intUserType = 3;
			break;
			case 'Author':
			$intUserType = 4;
			break;
			case 'Publisher':
			$intUserType = 5;
			break;
			case 'Manager':
			$intUserType = 6;
			break;
		}
	}
	else
	{
		/* user isn't logged in so make their usertype 0 */
		$intUserType = 0;
	}

		if ($mosConfig_shownoauth) {
		$database = &JFactory::getDBO();
		$database->setQuery( "SELECT m.*, count(p.parent) as cnt" .
	"\nFROM #__menu AS m" .
	"\nLEFT JOIN #__menu AS p ON p.parent = m.id" .
    "\nWHERE m.menutype='$menutype' AND m.published='1'" .
	"\nGROUP BY m.id ORDER BY m.parent, m.ordering ");

	 
      } else {
	  $database = &JFactory::getDBO();
		$database->setQuery( "SELECT m.*, sum(case when p.published=1 then 1 else 0 end) as cnt" .
	"\nFROM #__menu AS m" .
	"\nLEFT JOIN #__menu AS p ON p.parent = m.id" .
    "\nWHERE m.menutype='$menutype' AND m.published='1' AND m.access <= '$my->gid'" .
	"\nGROUP BY m.id ORDER BY m.parent, m.ordering ");
  
   }

	$rows = $database->loadObjectList( 'id' );
	echo $database->getErrorMsg();
	
		//work out if this should be highlighted
		$sql = &JFactory::getDBO();
		$sql->setQuery( 'SELECT m.* FROM #__menu AS m WHERE menutype='.$menutype.' AND m.published=1'); // nie wiadomo po co to
		$query = 'SELECT m.* FROM #__menu AS m WHERE menutype='.$menutype.' AND m.published=1';
		$database->setQuery( $query );
		$subrows = $database->loadObjectList( 'id' );
		$maxrecurse = 5;
		$parentid = $Itemid;
				

		//this makes sure toplevel stays hilighted when submenu active
		while ($maxrecurse-- > 0) {
			$parentid = getParentRow($subrows, $parentid);
			if (isset($parentid) && $parentid >= 0 && $subrows[$parentid]) {
				$hilightid = $parentid;
			} else {
				break;	
			}
		}	


   // I think nav-wrapper is obsolete and can be removed. See comment in suckerfiss.css
	echo "<div id=\"cssMenu1\" class=\"horizontal\">";
	$indents = array(
	// block prefix / item prefix / item suffix / block suffix
	array( "<ul class=\"menu\">", "<li>" , "</li>", "</ul>" ),
	);

    // establish the hierarchy of the menu
	$children = array();

    // first pass - collect children
    foreach ($rows as $v ) {
		$pt = $v->parent;
		$list = @$children[$pt] ? $children[$pt] : array();
		array_push( $list, $v );

        $children[$pt] = $list;

    }

    // second pass - collect 'open' menus
	$open = array( $Itemid );
	$count = 20; // maximum levels - to prevent runaway loop
	$id = $Itemid;
	while (--$count) {
		if (isset($rows[$id]) && $rows[$id]->parent > 0) {
			$id = $rows[$id]->parent;
			$open[] = $id;
		} else {
			break;
		}
	}

	$class_sfx = null;

    mosRecurseListMenu( 0, 0, $children, $open, $indents, $class_sfx, $hilightid );

	echo "<br/>"; ?>
	          
			  <script type="text/javascript">
	<!--
     var obj_cssMenu1 = new CSSMenu("cssMenu1");
    obj_cssMenu1.setTimeouts(400, 200, 800);
    obj_cssMenu1.setSubMenuOffset(0, 0, 0, 0);
    obj_cssMenu1.setHighliteCurrent(true);
    obj_cssMenu1.setAnimation('none');
    obj_cssMenu1.show();
   //-->
          </script>

<?php	echo "</div>";
}

/**
* Utility function to recursively work through a vertically indented
* hierarchial menu
*/
function sefRelToAbs($value) {
	return JRoute::_($value);
}

function mosRecurseListMenu( $id, $level, &$children, $open, &$indents, $class_sfx, $highlight ) {
	global $Itemid;
	global $HTTP_SERVER_VARS, $mosConfig_live_site;

	if (@$children[$id]) {
		$n = min( $level, count( $indents )-1 );

        echo $indents[$n][0];

		foreach ($children[$id] as $row) {

		
		        switch ($row->type) {
          		case 'separator':
          		// do nothing
          		$row->link = "seperator";

          		break;
          
          		case 'url':
          		if ( eregi( 'index.php\?', $row->link ) ) {
        				if ( !eregi( 'Itemid=', $row->link ) ) {
        					$row->link .= '&Itemid='. $row->id;
							
        				}
        			}
          		break;
          
          		default:
          			$row->link .= "&Itemid=$row->id"; 
          		break;
          	}

            $li =  "\n".$indents[$n][1] ;
            $current_itemid = trim( JArrayHelper::getValue( $_REQUEST, 'Itemid', 0 ) );
            if ($row->link != "seperator" &&
								$current_itemid == $row->id || 
            		$row->id == $highlight || 
                (sefRelToAbs( substr($_SERVER['PHP_SELF'],0,-9) . $row->link)) == $_SERVER['REQUEST_URI'] ||
                (sefRelToAbs( substr($_SERVER['PHP_SELF'],0,-9) . $row->link)) == $HTTP_SERVER_VARS['REQUEST_URI']) {
							$li = "<li>"; 
						}
	          echo $li;
								
            echo mosGetLink( $row, $level, $class_sfx );
						mosRecurseListMenu( $row->id, $level+1, $children, $open, $indents, $class_sfx, "" );
            echo $indents[$n][2]; 

        }
		echo "\n".$indents[$n][3];

	}
}

function getParentRow($rows, $id) {
		if (isset($rows[$id]) && $rows[$id]) {
			if($rows[$id]->parent > 0) {
				return $rows[$id]->parent;
			}	
		}
		return -1;
	}

/**
* Utility function for writing a menu link
*/


function mosGetLink( $mitem, $level, $class_sfx='' ) {
	global $Itemid, $mosConfig_live_site;
	$txt = '';
	
	$mitem->link = str_replace( '&', '&amp;', $mitem->link );

	if (strcasecmp(substr($mitem->link,0,4),"http")) {
		$mitem->link = sefRelToAbs($mitem->link);
	}

    switch ($mitem->browserNav) {
		// cases are slightly different
		case 1:
		// open in a new window
    if ($mitem->cnt > 0) {
		   if ($level == 0) {
                $txt = "<a target=\"_window\"  href=\"$mitem->link\">$mitem->name</a>";
		   } else {
                $txt = "<a target=\"_window\"  href=\"$mitem->link\">$mitem->name</a>";
		   }
		} else {
		   	$txt = "<a href=\"$mitem->link\" target=\"_window\" >$mitem->name</a>\n";
		}
		break;

		case 2:
		// open in a popup window
    if ($mitem->cnt > 0) {
				if ($level == 0) {
                $txt = "<a href=\"#\" onClick=\"javascript: window.open('$mitem->link', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');\" class=\"$menuclass\">$mitem->name</a>\n";
		   } else {
                $txt = "<a href=\"#\" onClick=\"javascript: window.open('$mitem->link', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');\" class=\"$menuclass\">$mitem->name</a>\n";
		   }
		} else {
		    $txt = "<a href=\"#\" onClick=\"javascript: window.open('$mitem->link', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');\" class=\"$menuclass\">$mitem->name</a>\n";
		}

		break;

		case 3:
		// don't link it
    if ($mitem->cnt > 0) {
		   if ($level == 0) {
                $txt = "<a>$mitem->name</a>";
		   } else {
                $txt = "<a>$mitem->name</a>";
		   }
		} else {
		   	$txt = "<a>$mitem->name</a>\n";
		}

		break;

		default:	// formerly case 2
		// open in parent window
		if (isset($mitem->cnt) && $mitem->cnt > 0) {
		    if ($level == 0) {
                $txt = "<a href=\"$mitem->link\">$mitem->name</a>";
		   } else {
                $txt = "<a href=\"$mitem->link\"> $mitem->name</a>";
		   }
		} else {
		   $txt = "<a href=\"$mitem->link\">$mitem->name</a>";
		}
        break;
	}
    return $txt;
}
?>



