/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2009 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/

/* Menu */

function jsnSetMenuFX(menuId, menuFX, options)
{
	switch(menuFX)
	{
		case 0:
			JSNUtils.sfHover(menuId);
			break;

		case 1:
			if (typeof(MooTools) != 'undefined') {
				new MooMenu($(menuId), options)
			} else {
				jsnSetMenuFX(menuId, 0, options);
			}
			break;
	}
}

function jsnInitTreemenu(menuClass)
{
	var treemenus, treemenu, menuId;

	// Set ids for all side menus base on class
	treemenus = JSNUtils.getElementsByClass(document, "UL", menuClass);
	if (treemenus == undefined) return;

	for(var i=0;i<treemenus.length;i++){
		menuId = "base-treemenu-" + (i+1);
		treemenu = treemenus[i];
		treemenu.id = menuId;

		// Set fx
		jsnSetMenuFX(menuId, 0, {});
	}
}


function jsnInitTemplate()
{
	JSNUtils.sfHover("base-mainmenu");
	JSNUtils.setInnerLayout(["jsn-content_inner3", "jsn-leftsidecontent", "jsn-rightsidecontent", "jsn-pos-innerleft", "jsn-pos-innerright"]);
	jsnInitTreemenu("menu-treemenu");

	JSNUtils.setStickPosition("jsn-pos-stickleft", lspAlignment);
	JSNUtils.setStickPosition("jsn-pos-stickright", rspAlignment);

	if (JSNUtils.isIE6()) {
		DD_belatedPNG.fix('#jsn-pos-stickleft img, #jsn-pos-stickright img, #mod_search_searchword, #jsn-pos-breadcrumbs, span.breadcrumbs a, span.breadcrumbs span, #base-mainmenu ul, #base-mainmenu span,  ul.menu-treemenu a, .author, .createdate, .list-arrow li, .jsn-top, .jsn-top_inner, .jsn-middle, .jsn-middle_inner, .jsn-bottom, .jsn-bottom_inner');
	}
}

// Call on document load
JSNUtils.addEvent(window, 'load', jsnInitTemplate);