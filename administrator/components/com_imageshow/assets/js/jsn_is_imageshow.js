/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISImageShow = {
	ChooseProfileFolder:function(){
		if($('add_image_manual_auto').checked == true){			
			$('user_select_folder').disabled = false;
		}
		if($('add_image_manual').checked == true){
			$('user_select_folder').disabled = true;
		}
	},
	ShowListCheckAlternativeContent:function(){
			var value = $('alternative_status').options[$('alternative_status').selectedIndex].value;
			if(value == 2){
				$('wrap-btt-article').setStyle('display', '');	
			}else{
				$('wrap-btt-article').setStyle('display', 'none');	
			}
			if(value == 1){
				$('wrap-btt-module').setStyle('display', '');	
			}else{
				$('wrap-btt-module').setStyle('display', 'none');	
			}
			
			if(value == 3){
				$('wrap-btt-image').setStyle('display', '');	
			}else{
				$('wrap-btt-image').setStyle('display', 'none');	
			}
	},
	ShowListCheckSeoContent:function(){
		var value = $('seo_status').options[$('seo_status').selectedIndex].value;
		if(value == 1){
			$('wrap-seo-article').setStyle('display', '');	
		}else{
			$('wrap-seo-article').setStyle('display', 'none');	
		}
		if(value == 2){
			$('wrap-seo-module').setStyle('display', '');	
		}else{
			$('wrap-seo-module').setStyle('display', 'none');	
		}
	},
	ShowListCheckAuthorizationContent:function(){
			var value = $('authorization_status').options[$('authorization_status').selectedIndex].value;
			if(value == 1){
				$('wrap-aut-article').setStyle('display', '');	
			}else{
				$('wrap-aut-article').setStyle('display', 'none');	
			}
	},
	ImagesPurgeObsolete:function(){
		$('task').value = 'obsolete';
		$('adminForm').submit();
	},
	ImagesPurgeSynchronize:function(){
		$('task').value = 'synchronize';
		$('adminForm').submit();
	},
	ProfileDelete:function(){
		$('task').value="removeprofile";
		$('controller').value="maintenance";
		$('adminForm').submit();
		window.top.setTimeout('window.parent.document.getElementById("sbox-window").close(); window.top.location.reload(true);', 1000);
	},
	Maintenance:function(){
		if($('linkbackup') != null){
			$('linkconfigs').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=configs';
			});
			$('linkmsgs').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=msgs';
			});
			$('linklangs').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=inslangs';
			});
			$('linksampledata').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=sampledata';
			});
			$('linkbackup').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=backup';
			});
			$('linkprofile').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=profiles';
			});
			$('linkthemes').addEvent('click', function() { 
				window.top.location='index.php?option=com_imageshow&controller=maintenance&type=themes';
			});	
		}		
	},
	SlideMessage: function(){
		$$( '.jsn-more-msg-info-wrapper' ).each(function(item){
			var thisSlider = new Fx.Slide( item.getElement( '.jsn-more-msg-info' ), { duration: 300 } );
			thisSlider.hide();
			if(item.getElement( '.jsn-link-readmore-messages' ) != null){
				item.getElement( '.jsn-link-readmore-messages' ).addEvent( 'click', function(){
					if(item.getElement( '.jsn-more-msg-info' ).innerHTML != ''){
						thisSlider.toggle(); 
					}
				});
			}
			thisSlider.addEvent('onStart', function(){
				var a = $E('a', item);
				if(a){
					var newHTML = a.innerHTML == '[+]' ? '[-]' : '[+]';
					a.setHTML(newHTML);
				}
			});
		});		
	},
	SetStatusMessage:function(token, msg_id){
		var url  = 'index.php?option=com_imageshow&controller=maintenance&task=setstatusmsg&msg_id='+msg_id+'&'+token+'=1';	
		var ajax = new Ajax(url, {
			method: 'get',
			onComplete: function(response) {
			}
		});
		ajax.request();
	},
	setDisplayMessage:function(){
		$$( '.jsn-link-delete-messages' ).each(function(item, i){
			item.addEvent( 'click', function(){
				var thisSlider = new Fx.Slide( $$( '.jsn-more-msg-info-wrapper' )[i], { duration: 300 } );
				thisSlider.toggle();
			});
		});
	},
	ShowcaseChangeBg:function(){
		var link_general_border_color = new MooRainbow('link_general_border_color', {
			id: 'linkgeneralbordercolor',
			imgPath: 'components/com_imageshow/assets/images/rainbow/',
			startColor:JSNISImageShow.hextorgb($('general_border_color').value),
			onChange: function(color) {
				$('general_border_color').value = color.hex;
				$('span_general_border_color').setStyle('background', color.hex);
			}
		});
		
		var background_color = new MooRainbow('general_background_color', {
			id: 'backgroundcolor',
			imgPath: 'components/com_imageshow/assets/images/rainbow/',
			startColor:JSNISImageShow.hextorgb($('background_color').value),
			onChange: function(color) {
				$('background_color').value = color.hex;
				$('span_background_color').setStyle('background', color.hex);
			}
		});
	},
	ReplaceVals: function (n) {
		if (n == "a") { n = 10; }
		if (n == "b") { n = 11; }
		if (n == "c") { n = 12; }
		if (n == "d") { n = 13; }
		if (n == "e") { n = 14; }
		if (n == "f") { n = 15; }
		
		return n;
	},
	hextorgb: function (strPara) {
		var casechanged=strPara.toLowerCase(); 
		var stringArray=casechanged.split("");
		if(stringArray[0] == '#'){
			for(var i = 1; i < stringArray.length; i++){			
				if(i == 1 ){
					var n1 = JSNISImageShow.ReplaceVals(stringArray[i]);				
				}else if(i == 2){
					var n2 = JSNISImageShow.ReplaceVals(stringArray[i]);
				}else if(i == 3){
					var n3 = JSNISImageShow.ReplaceVals(stringArray[i]);
				}else if(i == 4){
					var n4 = JSNISImageShow.ReplaceVals(stringArray[i]);
				}else if(i == 5){
					var n5 = JSNISImageShow.ReplaceVals(stringArray[i]);
				}else if(i == 6){
					var n6 = JSNISImageShow.ReplaceVals(stringArray[i]);
				}			
			}
			
			var returnval = ((16 * n1) + (1 * n2));
			var returnval1 = 16 * n3 + n4;
			var returnval2 = 16 * n5 + n6;
			return new Array(((16 * n1) + (1 * n2)), ((16 * n3) + (1 * n4)), ((16 * n5) + (1 * n6)));
		}
		return new Array(255, 0, 0);
	},
	
	switchShowcaseTheme: function(me)
	{
		$('adminForm').redirectLinkTheme.value = me.href;
		$('adminForm').task.value = 'switchTheme';
		$('adminForm').submit();
	},
	
	resizeFlex: function(direction, userID)
	{	
		var heightCookieName 		= 'jsnis-height-flex-' + userID;
		var heightLevelCookieName  	= 'jsnis-height-flex-level-' + userID;
		var heightCookieStatus 		= JSNISUtils.getCookie(heightCookieName);
		var levelCookieStatus 		= JSNISUtils.getCookie(heightLevelCookieName);
		var wrapperFlex 			= $('jsnis-showlist-flex');
		var height 					= wrapperFlex.getSize().size.y;
		var count;
		
		if (direction == 'reduce' && height <= 50) return false;
		
		if (heightCookieStatus == null || heightCookieStatus == '')
		{
			JSNISUtils.setCookie(heightCookieName, height, 15);		
		}

		if (levelCookieStatus == null || levelCookieStatus == '')
		{
			JSNISUtils.setCookie(heightLevelCookieName, 1, 15);
		}

		height = JSNISUtils.getCookie(heightCookieName);
		count  = JSNISUtils.getCookie(heightLevelCookieName);
		height = height.toInt();
		count  = count.toInt();
		
		var newHeight = height;
		
		if (direction == 'increase' && count <= 6)
		{
			newHeight = height + 50;	
			count++;
		}
		
		if (direction == 'reduce' && count > 1)
		{
			newHeight = height - 50;
			count--;
		}
		
		var increaseButton = $$('.jsnis-panel-increase');
		var decreaseButton = $$('.jsnis-panel-decrease');
		
		if (count >= 7)
		{
			increaseButton.addClass('disabled');
		}
		else if (count <= 1)
		{
			decreaseButton.addClass('disabled');
		}
		else
		{
			increaseButton.removeClass('disabled');
			decreaseButton.removeClass('disabled');
		}

		JSNISUtils.setCookie(heightCookieName, newHeight, 15);
		JSNISUtils.setCookie(heightLevelCookieName, count, 15);
		
		var effect = new Fx.Style(wrapperFlex, 'height', {
			duration:300,
			onComplete: function()
			{
				if (direction == 'increase' && count <= 7)
				{
					var scroll = new Fx.Scroll(window, {duration: 300});
					scroll.toBottom();
				}
			}
		});
		
		effect.start(height, newHeight);
	},
	
	setCookieSettingFlex: function(name, value)
	{
		if (userID != '' || userID != undefined)
		{
			JSNISUtils.setCookie('jsn-flex-cookie-' + name + '-' + userID, value, 15);
		}
	},
	
	loadCookieSettingFlex: function()
	{
		var heightLevelFlex = JSNISUtils.getCookie('jsnis-height-flex-level-' + userID);
		
		if (heightLevelFlex >= 7)
		{
			$$('.jsnis-panel-increase').addClass('disabled');
		}
		
		if (heightLevelFlex <= 1 || heightLevelFlex == null || heightLevelFlex == '')
		{
			$$('.jsnis-panel-decrease').addClass('disabled');
		}
	},
	
	jsnMenuSaveToLeave: function(action, link)
	{
		if (action != 'save')
		{
			window.top.location = link;
		}
		else
		{
			if ($('jsn-menu-link-redirect')) $('jsn-menu-link-redirect').remove();
			var linkElement = new Element('input', {'type' : 'hidden', 'id':'jsn-menu-link-redirect', 'name':'jsn-menu-link-redirect', 'value' : link});
			linkElement.injectInside(document.adminForm);
			submitbutton('save');
		}
	},
	
	jsnMenuEffect: function()
	{
		var jsnMenu = $$('#jsnis-menu li.menu-name')[0];
		var subMenu = $$('.jsnis-submenu')[0];
		
		function hideSubMenu()
		{
			subMenu.style.left = 'auto';
			subMenu.style.right = '0';
			
			setTimeout(function(){
				subMenu.style.left = '';
				subMenu.style.right = '';
			}, 500);
		}
		
		jsnMenu.addEvent('mouseleave', function(e)
		{
			var event = new Event(e);
			event.stop();
			hideSubMenu();
		});
		
		subMenu.addEvent('mouseleave', function(e)
		{
			var event = new Event(e);
			event.stop();
			hideSubMenu();
		});
	},

	flexShowlistLoadStatus: false,
	
	flexShowlistLoadCallBack: function()
	{
		JSNISImageShow.flexShowlistLoadStatus = true;
		JSNISImageShow.showlistSaveButtonsStatus('');
	},
	
	showlistSaveButtonsStatus: function(status)
	{
		$('jsn-showlist-toolbar-css').innerHTML = ''; // remove style css
	}
};