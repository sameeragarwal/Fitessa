/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2009 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/
var objReg = new RegExp("1.2");
var mtMatch = objReg.exec(MooTools.version);
var JSNAdmin = {
	init: function() {
		JSNAdmin.clean();
		JSNAdmin.setSlider();
	},
	clean: function(){
		var empties = $$('table.paramlist tr');
		if(mtMatch == null)
		{				
			empties.each(function(empty) {
				var children = empty.getChildren();
				if (children.length < 2) empty.remove();
				else if(!children[1].innerHTML.length) empty.remove();
			});				
		}
		else
		{
			empties.each(function(empty) {
				var children = empty.getChildren();
				if (children.length < 2) empty.destroy();
				else if(!children[1].innerHTML.length) empty.destroy();
			});
		}
	},
	setSlider:function(){
		var status = {'true': 'open', 'false': 'close'};
		var settings = {selected: null};
		var HashCookie = new Hash.Cookie('HashCookieIndex', {duration: 24*60*60});
		if(HashCookie.get('selected') != null){
			var strCookieParse = HashCookie.get('selected').substr(0, HashCookie.get('selected').length - 1);	
		}else{
			HashCookie.extend(settings);
		}			
		var collapsibles = new Array();
		var headings = $$('#content-pane div h3');
		var index = 0;
		$$('.jsn-panel').each(function(item, i){	
			var collapsible = new Fx.Slide( item.getElement('.jsn-pane-slider'), { 
				duration: 300,
				transition: Fx.Transitions.linear
			});
			collapsibles[i] = collapsible;			
			item.getElement('.title').addEvent( 'click', function(){
				collapsible.toggle();
				return false;
			});			
			
			if(HashCookie.get('selected') != null && HashCookie.get('selected') !=',,'){
				if(strCookieParse.indexOf(i) == -1){
					collapsible.hide();
				}else{
					collapsible.show();
					if(mtMatch == null)
					{
						var h3 = $E('h3', item);
					}
					else
					{	
						var h3 = item.getElement('h3', item);
					}
					h3.className = "title jsn-pane-toggler-down";				
				}				
			}else{
				collapsible.hide();
			}
			collapsible.addEvent('onStart', function() {
				if(mtMatch == null)
				{
					var h3 = $E('h3', item);
				}
				else
				{	
					var h3 = item.getElement('h3', item);
				}
				if(h3){
					if(h3.className == "title jsn-pane-toggler"){
						h3.className = "title jsn-pane-toggler-down";
					}else{						
						h3.className = "title jsn-pane-toggler";
					}
				}		
			});			
			collapsible.addEvent('onComplete', function() {
				var strCookie = HashCookie.get('selected');
				if(strCookie != null){
					if(strCookie.indexOf(i) == -1){
						strCookie += i+",";
						settings['selected'] = strCookie;
						HashCookie.extend(settings);
						
					}else{	
						str = strCookie.replace(i+",", ",");	
						settings['selected'] = str;
						HashCookie.extend(settings);								
					}
				}else{
					settings['selected'] = i+",";
					HashCookie.extend(settings);	
				}	
			});			
		});
		
		$('collapse-all').addEvent('click', function(){
			$$('.jsn-panel').each(function(item, i){	
				collapsibles[i].hide();
				item.getElement( '.title' ).className = "title jsn-pane-toggler";
			});
			settings['selected'] = null;
			HashCookie.extend(settings);
			return false;				
		});
		$('expand-all').addEvent('click', function(){
			var strCookie = null;
			$$( '.jsn-panel' ).each(function(item, i){
				strCookie += i+",";
				collapsibles[i].show();
				item.getElement( '.title' ).className = "title jsn-pane-toggler-down";
				settings['selected'] = strCookie;
			});
			HashCookie.extend(settings);	
			return false;			
		});				
	}
};
window.addEvent('domready', JSNAdmin.init);