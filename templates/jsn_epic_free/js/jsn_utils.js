/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2009 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/


var JSNUtils = {

	addEvent: function(target, event, func){
		if (target.addEventListener){
			target.addEventListener(event, func, false);
			return true;
		} else if (target.attachEvent){
			var result = target.attachEvent("on"+event, func);
			return result;
		} else {
			return false;
		}
	},

	checkSubstring: function(targetString, targetSubstring, delimeter, wholeWord){
		if(wholeWord == undefined) wholeWord = false;
		var parts = targetString.split(delimeter);
		for (var i = 0; i < parts.length; i++){
			if (wholeWord && parts[i] == targetSubstring) return true;
			if (!wholeWord && parts[i].indexOf(targetSubstring) > -1) return true;
		}
		return false;
	},

	getElementsByClass: function(targetParent, targetTag, targetClass){
		if(!targetParent.getElementsByTagName) return;

		var elements, tags, tag, tagClass;

		tags = targetParent.getElementsByTagName(targetTag);
		elements = [];

		for(var i=0;i<tags.length;i++){
			tagClass = tags[i].className;
			if(tagClass != "" && JSNUtils.checkSubstring(tagClass, targetClass, " ", false)){
				elements[elements.length] = tags[i];
			}
		}

		return elements;
	},

	getFirstChild: function(targetEl, targetTagName){
		var nodes, node;
		nodes = targetEl.childNodes;
		for(var i=0;i<nodes.length;i++){
			node = nodes[i];
			if (node.tagName == targetTagName)
				return node;
		}
		return null;
	},

	getFirstChildAtLevel: function(targetEl, targetTagName, targetLevel){
		var child, nodes, node;
		nodes = targetEl.childNodes;
		for(var i=0;i<nodes.length;i++){
			node = nodes[i];
			if (targetLevel == 1) {
				if(node.tagName == targetTagName) return node;
			} else {
				child = JSNUtils.getFirstChildAtLevel(node, targetTagName, targetLevel-1);
				if(child != null) return child;
			}
		}
		return null;
	},

	getViewportSize: function(){
		var myWidth = 0, myHeight = 0;
		
		if( typeof( window.innerWidth ) == 'number' ) {
			//Non-IE
			myWidth = window.innerWidth;
			myHeight = window.innerHeight;
		} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
			//IE 6+ in 'standards compliant mode'
			myWidth = document.documentElement.clientWidth;
			myHeight = document.documentElement.clientHeight;
		} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
			//IE 4 compatible
			myWidth = document.body.clientWidth;
			myHeight = document.body.clientHeight;
		}
		
		return {width:myWidth, height:myHeight };
	},

	getChildren: function(targetEl, targetTagName){
		var nodes, node;
		var children = [];
		nodes = targetEl.childNodes;
		for(var i=0;i<nodes.length;i++){
			node = nodes[i];
			if(node.tagName == targetTagName)
				children.push(node);
		}
		return children;
	},

	getChildrenAtLevel: function(targetEl, targetTagName, targetLevel){
		var children = [];
		var nodes, node;
		nodes = targetEl.childNodes;
		for(var i=0;i<nodes.length;i++){
			node = nodes[i];
			if (targetLevel == 1) {
				if(node.tagName == targetTagName) children.push(node);
			} else {
				children = children.concat(JSNUtils.getChildrenAtLevel(node, targetTagName, targetLevel-1));
			}
		}
		return children;
	},

	addClass: function(targetTag, targetClass){
		if(targetTag.className == ""){
			targetTag.className = targetClass;
		} else {
			if(!JSNUtils.checkSubstring(targetTag.className, targetClass, " ")){
				targetTag.className += " " + targetClass;
			}
		}
	},

	writeCookie: function (name,value,days){
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		} else expires = "";

		document.cookie = name+"="+value+expires+"; path=/";
	},

	readCookie: function (name){
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	},

	isIE6: function() {
		return (navigator.appVersion.indexOf("MSIE 6.")!=-1);
	},

	isIE7: function() {
		return (navigator.appVersion.indexOf("MSIE 7.")!=-1);
	},

	/* GUI functions */

	createImageMenu: function(menuId, imageClass){
		if (!document.getElementById) return;

		var list = document.getElementById(menuId);
		var listItems;

		var listItem;

		if(list != undefined) {
			listItems = list.getElementsByTagName("LI");
			for(i=0, j=0;i<listItems.length;i++){
				listItem = listItems[i];
				if (listItem.parentNode == list) {
					listItem.className += " " + imageClass + (j+1);
					j++;
				}
			}
		}
	},
	
	createExtList: function(listClass, extTag, className, includeNumber){
		if (!document.getElementById) return;

		var lists = JSNUtils.getElementsByClass(document, "UL", listClass);
		var list;
		var listItems;
		var listItem;

		if(lists != undefined) {
			for(j=0;j<lists.length;j++){
				list = lists[j];
				listItems = JSNUtils.getChildren(list, "LI");
				for(i=0,k=0;i<listItems.length;i++){
					listItem = listItems[i];
					if(className !=''){
						listItem.innerHTML = '<'+ extTag + ' class='+className+'>' + (includeNumber?(k+1):'') + '</'+  extTag +'>' + listItem.innerHTML;
					}else{
						listItem.innerHTML = '<'+ extTag + '>' + (includeNumber?(k+1):'') + '</'+  extTag +'>' + listItem.innerHTML;
					}
					k++;
				}
			}
		}
	},

	matchDivHeights: function (parent, containerTag, containerClass, itemTag, itemClass, itemLevel, heightOffset){
		var parents;
		parents = jsnGetElementsByClass(parent, containerTag, containerClass);

		for(var i=0;i<parents.length;i++){
			var targetParent, targetDivs, maxHeight, divHeight, d, offset;

			targetParent = parents[i];
			divs = jsnGetElementsByClass(targetParent, itemTag, itemClass);
			targetDivs = [];
			maxHeight = 0;

			for(var i=0; i<divs.length; i++){
				d = jsnTagDig(divs[i], itemTag, itemLevel);
				targetDivs[targetDivs.length] = d;

				if(d.offsetHeight){
					 divHeight = d.offsetHeight;
				} else if(d.style.pixelHeight){
					 divHeight = d.style.pixelHeight;
				}

				maxHeight = Math.max(maxHeight, divHeight);
		    }

			for(var i=0;i<targetDivs.length;i++){
				d = targetDivs[i];
				d.style.height = (maxHeight - heightOffset) + "px";
			}
		}
	},

	createGridLayout: function(containerTag, containerClass, columnClass, lastcolumnClass) {
		var gridLayouts, gridLayout, gridColumns, gridColumn, columnsNumber;
		gridLayouts = JSNUtils.getElementsByClass(document, containerTag, containerClass);
		for(var i=0;i<gridLayouts.length;i++){
			gridLayout = gridLayouts[i];
			gridColumns = JSNUtils.getChildren(gridLayout, containerTag);
			columnsNumber = gridColumns.length;
			JSNUtils.addClass(gridLayout, containerClass + columnsNumber);
			JSNUtils.addClass(gridLayout, 'clearafter');
			for(var j=0;j<columnsNumber;j++){
				gridColumn = gridColumns[j];
				JSNUtils.addClass(gridColumn, columnClass);
				if(j == gridColumns.length-1) {
					JSNUtils.addClass(gridColumn, lastcolumnClass);
				}
				gridColumn.innerHTML = '<div class="' + columnClass + '_inner">' + gridColumn.innerHTML + '</div>';
			}
		}
	},

	sfHover: function(menuId, menuDelay) {
		if(menuId == undefined) return;

		var delay = (menuDelay == undefined)?0:menuDelay;
		var pEl = document.getElementById(menuId);
		if (pEl != undefined) {
			var sfEls = pEl.getElementsByTagName("li");
			for (var i=0; i<sfEls.length; ++i) {
				sfEls[i].onmouseover=function() {
					clearTimeout(this.timer);
					if(this.className.indexOf("sfhover") == -1) {
						this.className += " sfhover";
					}
				}
				sfEls[i].onmouseout=function() {
					this.timer = setTimeout(JSNUtils.sfHoverOut.bind(this), delay);
				}
			}
		}
	},

	sfHoverOut: function() {
		clearTimeout(this.timer);
		this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
	},

	/* Accessibility functions */

	setFontSize: function (targetId, fontSize){
		var targetObj = (document.getElementById) ? document.getElementById(targetId) : document.all(targetId);
		targetObj.style.fontSize = fontSize + '%';
	},
	setStickPosition: function(pName, pAlignment) {
		var stickElement = document.getElementById(pName);
		
		if (stickElement != undefined) {
			var topDelta, vpHeight, pHeight;
			vpHeight = (JSNUtils.getViewportSize()).height;
			pHeight = stickElement.offsetHeight;
			switch(pAlignment){
				case "top":
					topDelta = 0;
				break;

				case "middle":
					topDelta = Math.floor((100 - Math.round((pHeight/vpHeight)*100))/2);
				break;

				case "bottom":
					topDelta = 100 - Math.round((pHeight/vpHeight)*100);
				break;
			}
			
			topDelta = (topDelta < 0)?0:topDelta;

			if (this.isIE6()) {}
			else {
				stickElement.style.top = topDelta + "%";
			}
			stickElement.style.visibility = "visible";
		}
	},
	setInnerLayout:function(elements)
	{
		var pleftWidth = 0;
		var pinnerleftWidth = 0;
		var prightWidth = 0;
		var pinnerrightWidth = 0;
		var root = document.getElementById(elements[0]);
		var rootWidth = root.offsetWidth;
		if(document.getElementById(elements[1]) != null) {
			pleftWidth = document.getElementById(elements[1]).offsetWidth;
		}
		if(document.getElementById(elements[3]) != null) {
			pinnerleftWidth = document.getElementById(elements[3]).offsetWidth;				
			var resultLeft = (pleftWidth + pinnerleftWidth)*100/rootWidth;
			root.firstChild.style.right = (100 - resultLeft) + "%";
			root.firstChild.firstChild.style.left = (100 - resultLeft) + "%";
		}
		if(document.getElementById(elements[2]) != null) {
			prightWidth = document.getElementById(elements[2]).offsetWidth;
		}
		if(document.getElementById(elements[4]) != null) {
			pinnerrightWidth = document.getElementById(elements[4]).offsetWidth;				
			var resultRight = (prightWidth + pinnerrightWidth)*100/rootWidth;
			root.firstChild.firstChild.firstChild.style.left = (100 - resultRight) + "%";
			root.firstChild.firstChild.firstChild.firstChild.style.right = (100 - resultRight) + "%";
		}
	},
	setLinkNavPrefix: function(targetId, orgHref)
	{
		var navUrl 			= window.location.href;
		var targetEl 		= document.getElementById(targetId);
		if(targetEl != undefined && targetEl.tagName.toUpperCase() == 'A')
		{
			targetEl.href = navUrl + ((navUrl.indexOf(orgHref) != -1)?'':orgHref);
		}
	}
};