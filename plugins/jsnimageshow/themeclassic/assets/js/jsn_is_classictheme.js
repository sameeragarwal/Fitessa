/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISClassicTheme = {
	ShowcaseSwitchBrowsingMode:function(){
		var valueMode = $('thumbpanel_thumb_browsing_mode').value;
		if(valueMode == 'sliding'){
			$('thumbpanel_thumb_row').value = 1;
			$('thumbpanel_thumb_row').readOnly  = true;
			$('thumbpanel_thumb_row').setStyle('background', '#ECE9D8');
		}else{
			$('thumbpanel_thumb_row').value = 1;
			$('thumbpanel_thumb_row').readOnly = false;
			$('thumbpanel_thumb_row').setStyle('background', '#fff');
		}
	},
	ShowcaseFitin:function(){
		var obj1= $('item1');
		var obj2= $('item2');
		var obj3= $('fit');
		var obj4= $('expand');
		obj1.setStyle('display', '');
		obj2.setStyle('display', 'none');
		obj3.removeClass("inactive");
		obj3.addClass("active");
		obj4.removeClass("active");
		obj4.addClass("inactive");
	},
	ShowcaseExpand:function(){
		var obj1= $('item1');
		var obj2= $('item2');
		var obj3= $('fit');	
		var obj4= $('expand');
		obj1.setStyle('display', 'none');
		obj2.setStyle('display', '');
		obj3.removeClass("active");
		obj3.addClass("inactive");
		obj4.removeClass("inactive");
		obj4.addClass("active");
		var accParent = $('acc-image-presentation').getParent();
		accParent.setStyles({'height' : 'auto'});
		accParent.setStyles({'height' : ''});
	},
	ShowcaseViewGraphic: function(url){
		if($('imgpanel_bg_value_first').value == ''){
			return false;
		}
		$('view-image-graphic').href=url+$('imgpanel_bg_value_first').value;
	},
	ShowcaseChangeBg:function(){
		var originalValue =  $('imgpanel_bg_type').options[$('imgpanel_bg_type').selectedIndex].value;
		if(originalValue == 2 && $('imgpanel_bg_value_first').value ==''){
			$('solid_value').setStyle('display', 'none');
			$('gradient_value').setStyle('display', '');
			$('wrap-color').setStyle('display', '');
			$('span_imgpanel_bg_value_first').setStyle('display', '');
			$('span_imgpanel_bg_value_last').setStyle('display', '');	
			$('span_imgpanel_bg_value_last').setStyle('background', '#262626');
			$('span_imgpanel_bg_value_first').setStyle('background', '#595959');
			$('span_solidpanel_bg_value_first').setStyle('background', '#fff');
			$('pattern_value').setStyle('display', 'none');
			$('pattern_title').setStyle('display', 'none');
			$('image_title').setStyle('display', 'none');
			$('background_value').setStyle('display', 'none');
			$('imgpanel_bg_value_last').setStyle('display', '');
			$('imgpanel_bg_value_last').setStyle('width', '50px');
			$('imgpanel_bg_value_first').setStyle('width', '50px');
			$('imgpanel_bg_value_first').value='#595959';
			$('imgpanel_bg_value_last').value='#262626';
			JSNISClassicTheme['background-type-' + originalValue] = {'firstValue' : $('imgpanel_bg_value_first').value, 'lastValue': $('imgpanel_bg_value_last').value};
		}
		$('imgpanel_bg_type').addEvent('click', function() {
			var value = $('imgpanel_bg_type').options[$('imgpanel_bg_type').selectedIndex].value;
			JSNISClassicTheme['background-type-' + value] = {'firstValue' : $('imgpanel_bg_value_first').value, 'lastValue': $('imgpanel_bg_value_last').value};
		});
		$('imgpanel_bg_type').addEvent('change', function() {
			var accBgParent = $('acc-background').getParent();
			accBgParent.setStyles({'height' : 'auto'});
			accBgParent.setStyles({'height' : ''});
			var value = $('imgpanel_bg_type').options[$('imgpanel_bg_type').selectedIndex].value;
			
			switch (value) 
			{
				case '1':
					$('solid_value').setStyle('display', '');
					$('gradient_value').setStyle('display', 'none');
					$('wrap-color').setStyle('display', '');
					$('pattern_value').setStyle('display', 'none');
					$('pattern_title').setStyle('display', 'none');
					$('image_title').setStyle('display', 'none');
					$('background_value').setStyle('display', 'none');
					$('span_imgpanel_bg_value_first').setStyle('display', '');
					$('span_imgpanel_bg_value_last').setStyle('display', 'none');
					$('span_imgpanel_bg_value_last').setStyle('background', (JSNISClassicTheme['background-type-' + value] ) ? JSNISClassicTheme['background-type-' + value].lastValue : '#fff');
					$('span_imgpanel_bg_value_first').setStyle('background', (JSNISClassicTheme['background-type-' + value] ) ? JSNISClassicTheme['background-type-' + value].firstValue : '#fff');
					$('span_solidpanel_bg_value_first').setStyle('background', (JSNISClassicTheme['background-type-' + value] ) ? JSNISClassicTheme['background-type-' + value].firstValue : '#fff');
					$('imgpanel_bg_value_first').setStyle('width', '50px');
					$('imgpanel_bg_value_last').setStyle('display', 'none');
					$('imgpanel_bg_value_first').value = (JSNISClassicTheme['background-type-' + value] ) ? JSNISClassicTheme['background-type-' + value].firstValue : '';
					$('imgpanel_bg_value_last').value = (JSNISClassicTheme['background-type-' + value]) ? JSNISClassicTheme['background-type-' + value].lastValue : '';
					JSNISClassicTheme['background-type-' + value] = {'firstValue' : $('imgpanel_bg_value_first').value, 'lastValue': $('imgpanel_bg_value_last').value};
					break;
				case '2':	
				case '3':
					$('solid_value').setStyle('display', 'none');
					$('gradient_value').setStyle('display', '');
					$('wrap-color').setStyle('display', '');
					$('span_imgpanel_bg_value_first').setStyle('display', '');
					$('span_imgpanel_bg_value_last').setStyle('display', '');	
					$('span_imgpanel_bg_value_last').setStyle('background', (JSNISClassicTheme['background-type-' + value] ) ? JSNISClassicTheme['background-type-' + value].lastValue : '#fff');
					$('span_imgpanel_bg_value_first').setStyle('background', (JSNISClassicTheme['background-type-' + value] ) ? JSNISClassicTheme['background-type-' + value].firstValue : '#fff');
					$('span_solidpanel_bg_value_first').setStyle('background', (JSNISClassicTheme['background-type-' + value] ) ? JSNISClassicTheme['background-type-' + value].firstValue : '#fff');
					$('pattern_value').setStyle('display', 'none');
					$('pattern_title').setStyle('display', 'none');
					$('image_title').setStyle('display', 'none');
					$('background_value').setStyle('display', 'none');
					$('imgpanel_bg_value_last').setStyle('display', '');
					$('imgpanel_bg_value_last').setStyle('width', '50px');
					$('imgpanel_bg_value_first').setStyle('width', '50px');
					$('imgpanel_bg_value_first').value = (JSNISClassicTheme['background-type-' + value]) ? JSNISClassicTheme['background-type-' + value].firstValue : '';
					$('imgpanel_bg_value_last').value = (JSNISClassicTheme['background-type-' + value]) ? JSNISClassicTheme['background-type-' + value].lastValue : '';
					JSNISClassicTheme['background-type-' + value] = {'firstValue' : $('imgpanel_bg_value_first').value, 'lastValue': $('imgpanel_bg_value_last').value};
					break;
				case '4':
					$('wrap-color').setStyle('display', 'none');
					$('solid_value').setStyle('display', 'none');
					$('gradient_value').setStyle('display', 'none');
					$('pattern_value').setStyle('display', '');
					$('pattern_title').setStyle('display', '');
					$('background_value').setStyle('display', 'none');
					$('span_imgpanel_bg_value_first').setStyle('display', 'none');
					$('span_imgpanel_bg_value_last').setStyle('display', 'none');
					$('image_title').setStyle('display', 'none');
					$('imgpanel_bg_value_last').setStyle('display', 'none');
					$('imgpanel_bg_value_first').setStyle('width', '100%');
					$('imgpanel_bg_value_first').value = (JSNISClassicTheme['background-type-' + value]) ? JSNISClassicTheme['background-type-' + value].firstValue : '';
					$('imgpanel_bg_value_last').value='';
					JSNISClassicTheme['background-type-' + value] = {'firstValue' : $('imgpanel_bg_value_first').value, 'lastValue': $('imgpanel_bg_value_last').value};
					break;				
				case '5':
					$('wrap-color').setStyle('display', 'none');
					$('solid_value').setStyle('display', 'none');
					$('gradient_value').setStyle('display', 'none');
					$('pattern_value').setStyle('display', 'none');
					$('background_value').setStyle('display', '');
					$('pattern_title').setStyle('display', 'none');
					$('image_title').setStyle('display', '');
					$('span_imgpanel_bg_value_first').setStyle('display', 'none');
					$('span_imgpanel_bg_value_last').setStyle('display', 'none');
					$('imgpanel_bg_value_last').setStyle('display', 'none');
					$('imgpanel_bg_value_first').setStyle('width', '100%');
					$('imgpanel_bg_value_first').value = (JSNISClassicTheme['background-type-' + value]) ? JSNISClassicTheme['background-type-' + value].firstValue : '';
					$('imgpanel_bg_value_last').value='';
					JSNISClassicTheme['background-type-' + value] = {'firstValue' : $('imgpanel_bg_value_first').value, 'lastValue': $('imgpanel_bg_value_last').value};
					break;	
				default:
					break;
			}
			$('imgpanel_bg_value_first').onchange();
			$('imgpanel_bg_value_last').onchange();
		});
		var rainBowPath = 'components/com_imageshow/assets/images/rainbow/';
		var solid_link = new MooRainbow('solid_link', {
			id: 'solidLink',
			imgPath: rainBowPath,
			startColor:JSNISClassicTheme.hextorgb($('imgpanel_bg_value_first').value),
			onChange: function(color) {
				$('imgpanel_bg_value_first').value = color.hex;
				$('imgpanel_bg_value_first').onchange();
				$('imgpanel_bg_value_last').value='';
				$('span_solidpanel_bg_value_first').setStyle('background', color.hex);
			}
		});
		var active_state_color = new MooRainbow('active_state_color', {
			id: 'activestatecolor',
			imgPath: rainBowPath,
			startColor:JSNISClassicTheme.hextorgb($('thumbpanel_active_state_color').value),
			onChange: function(color) {
				$('thumbpanel_active_state_color').value = color.hex;
				$('thumbpanel_active_state_color').onchange();
				$('span_thumbpanel_active_state_color').setStyle('background', color.hex);
			}
		});
		var big_thumb_color = new MooRainbow('big_thumb_color', {
			id: 'bigthumbcolor',
			imgPath: rainBowPath,
			startColor:JSNISClassicTheme.hextorgb($('thumbpanel_big_thumb_color').value),
			onChange: function(color) {
				$('thumbpanel_big_thumb_color').value = color.hex;
				$('thumbpanel_big_thumb_color').onchange();
				$('span_thumbpanel_big_thumb_color').setStyle('background', color.hex);
			}
		});
	
		var bg_color_fill = new MooRainbow('bg_color_fill', {
			id: 'bgcolorfill',
			imgPath: rainBowPath,
			startColor:JSNISClassicTheme.hextorgb($('infopanel_bg_color_fill').value),
			onChange: function(color) {
				$('infopanel_bg_color_fill').value = color.hex;
				$('infopanel_bg_color_fill').onchange();
				$('span_bg_color_fill').setStyle('background', color.hex);
			}
		});
		var gradient_link_1 = new MooRainbow('gradient_link_1', {
			id: 'gradientLink1',
			imgPath: rainBowPath,
			startColor:JSNISClassicTheme.hextorgb($('imgpanel_bg_value_first').value),
			onChange: function(color) {
				$('imgpanel_bg_value_first').value = color.hex;
				$('imgpanel_bg_value_first').onchange();
				$('span_imgpanel_bg_value_first').setStyle('background', color.hex);
			}
		});
		
		var gradient_link_2 = new MooRainbow('gradient_link_2', {
			id: 'gradientLink2',
			imgPath: rainBowPath,
			startColor:JSNISClassicTheme.hextorgb($('imgpanel_bg_value_last').value),
			onChange: function(color) {			
				$('imgpanel_bg_value_last').value =  color.hex;
				$('imgpanel_bg_value_last').onchange();
				$('span_imgpanel_bg_value_last').setStyle('background', color.hex);
			}
		});	
		var thumnail_panel_color = new MooRainbow('thumnail_panel_color', {
			id: 'thumnailpanelcolor',
			imgPath: rainBowPath,
			startColor:JSNISClassicTheme.hextorgb($('thumbpanel_thumnail_panel_color').value),
			onChange: function(color) {
				$('thumbpanel_thumnail_panel_color').value = color.hex;
				$('thumbpanel_thumnail_panel_color').onchange();
				$('span_thumnail_panel_color').setStyle('background', color.hex);
			}
		});	
		var thumnail_normal_state = new MooRainbow('thumnail_normal_state', {
			id: 'thumnailnormalstate',
			imgPath: rainBowPath,
			startColor:JSNISClassicTheme.hextorgb($('thumbpanel_thumnail_normal_state').value),
			onChange: function(color) {
				$('thumbpanel_thumnail_normal_state').value = color.hex;
				$('thumbpanel_thumnail_normal_state').onchange();
				$('span_thumnail_normal_state').setStyle('background', color.hex);
			}
		});
		var imgpanel_inner_shawdow_color = new MooRainbow('imgpanel_inner_shawdow_color_link', {
			id: 'imgpanelInnerShawdowColorLink',
			imgPath: rainBowPath,
			startColor:JSNISClassicTheme.hextorgb($('imgpanel_inner_shawdow_color').value),
			onChange: function(color) {
				$('imgpanel_inner_shawdow_color').value = color.hex;
				$('imgpanel_inner_shawdow_color').onchange();
				$('span_imgpanel_inner_shawdow_color').setStyle('background', color.hex);
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
					var n1 = JSNISClassicTheme.ReplaceVals(stringArray[i]);				
				}else if(i == 2){
					var n2 = JSNISClassicTheme.ReplaceVals(stringArray[i]);
				}else if(i == 3){
					var n3 = JSNISClassicTheme.ReplaceVals(stringArray[i]);
				}else if(i == 4){
					var n4 = JSNISClassicTheme.ReplaceVals(stringArray[i]);
				}else if(i == 5){
					var n5 = JSNISClassicTheme.ReplaceVals(stringArray[i]);
				}else if(i == 6){
					var n6 = JSNISClassicTheme.ReplaceVals(stringArray[i]);
				}			
			}
			
			var returnval = ((16 * n1) + (1 * n2));
			var returnval1 = 16 * n3 + n4;
			var returnval2 = 16 * n5 + n6;
			return new Array(((16 * n1) + (1 * n2)), ((16 * n3) + (1 * n4)), ((16 * n5) + (1 * n6)));
		}
		return new Array(255, 0, 0);
	},
	
	getShowcaseJSON: function()
	{
		var options = {};
		var imgpanelBgValue = '';
		
		$('adminForm').getFormElements().each(function(el)
		{
			var name = el.name;
			if(el.type == 'radio')
			{
				if(el.checked == true)
				{
					var value = el.getProperty('value');
				}
			}
			else
			{
				var value = el.getProperty('value');
			}
			if(name == 'imgpanel_bg_value[]' && value !=''){
				imgpanelBgValue += ","+value;
				options['imgpanel_bg_value'] = imgpanelBgValue;	
			}else{
				if(name == 'imgpanel_bg_value[]' && value ==''){
				
				}else{				
					if(value != undefined)
					{
						options[name] = value;
					}
				}
			}
		});	
		
		var showcaseJSON = JSNISClassicTheme.prepareShowcaseData(options);
		return showcaseJSON;
	},
	
	previewFlashFirstLoad : false,
	currentShowcaseSetting : null,
	
	ShowcasePreview: function()
	{
		var showlistID 		= $('showlist_id').options[$('showlist_id').selectedIndex].value;
		var showlistURL 	= 'index.php?option=com_imageshow&controller=showlist&format=showlist&showlist_id='+showlistID;
		JSNISClassicTheme.currentShowcaseSetting = JSNISClassicTheme.getShowcaseJSON();
		
		var ajax = new Ajax(showlistURL, {
			method: 'post',
			onComplete: function(showlistJSON)
			{
				if(JSNISClassicTheme.currentShowcaseSetting != '')
				{
					document.getElementById('jsn-flash-preview-object').loadData(JSNISClassicTheme.currentShowcaseSetting, showlistJSON);
					return true;
				}
			}
		});		
		ajax.request();
		
		this.previewFlashFirstLoad = true;
		return true;
	},
	
	previewModal: function()
	{
		if (this.previewFlashFirstLoad == true)
		{
			this.ShowcasePreview();
		}
		var windowObj 	= window.getSize();
		var overlay 	= $('jsn-overlay');
		var windowbox 	= $('jsn-windowbox');
		var modalStatus = 'open';
		var contentSize = windowbox.getSize();
		
		if (window.webkit && !window.chrome) // fix bug with safari on MacOS, flash is load but not shown
		{
			$('jsn-flash-preview-object').style.display = 'none';
		}
		
		if (window.opera) // get dimentions on Opera
		{
			var windowH  = window.innerHeight;
			var top 	 = window.pageYOffset + (windowH - contentSize.size.y)/2;
		}
		else
		{
			var top  = windowObj.scroll.y + (windowObj.size.y - contentSize.size.y)/2;
		}
		
		var left 		= (windowObj.size.x - contentSize.size.x)/2;
		
		overlay.addClass('overlay');
		
		JSNISImageShow.previewModalEffect = new Fx.Styles(overlay, {
			duration : 300,
			transition : Fx.Transitions.linear,
			onComplete: function()
			{
				if (modalStatus == 'open')
				{
					windowbox.setStyles({'left':left, 'top':top});
					modalStatus = 'close';
					JSNISClassicTheme.toggleVisualPreview('none');
					
					if (window.webkit && !window.chrome) // fix bug with safari on MacOS, flash is load but not shown
					{
						$('jsn-flash-preview-object').style.display = 'block';
					}
				}
				else
				{
					overlay.removeClass('overlay');
				}
			},
			onStart: function()
			{
				if (modalStatus == 'close')
				{
					windowbox.setStyles({'left':'-9999px'});
				}
			}
		});
		
		JSNISImageShow.previewModalEffect.start({'opacity':0.7});
		
		$('jsn-windowbox-close').addEvent('click', function()
		{
			document.getElementById('jsn-flash-preview-object').clear();
		});
	},
	
	onClearComplete: function()
	{
		if (JSNISImageShow.previewModalEffect)
		{
			JSNISImageShow.previewModalEffect.start({'opacity':0.1});
		}
		JSNISClassicTheme.toggleVisualPreview('block');
	},
	
	toggleVisualPreview : function(status)
	{
		$('jsn-flash-visual-object').style.display = status;
	},
	
	ChangeWatermark:function(){
		var value = $('imgpanel_watermark_position').options[$('imgpanel_watermark_position').selectedIndex].value;
		if(value =='center'){
			$('imgpanel_watermark_offset').disabled=true;
		}else{
			$('imgpanel_watermark_offset').disabled=false;
		}
	},
	SwitchPresentationModeKenBurn:function(){
		var value = $('slideshow_presentation_mode').options[$('slideshow_presentation_mode').selectedIndex].value;		
		if(value=='fit-in'){			
			$('slideshow_enable_ken_burn_effect0').checked = true;
		}
	},
	SwitchKenBurnPresentationMode:function(){
		if($('slideshow_enable_ken_burn_effect1').checked==true){			
			$('slideshow_presentation_mode').value = 'expand-out';
		}

	},
	EnableShowCasePreview:function(){
		var value 			= $('showlist_id').options[$('showlist_id').selectedIndex].value;
		var previewButton 	= $('preview-showcase-link');
		
		if(value == 0){
			previewButton.setProperty('disabled', true);
			previewButton.style.color = '#ccc';
		}else{
			previewButton.setProperty('disabled', false);
			previewButton.style.color = '#000';
		}
	},
	
	prepareShowcaseData: function(data)
	{
		var booleanArray 	= ['no', 'yes'];
		var imgPanelBgType 	= ['transparent', 'solid-color', 'linear-gradient', 'radial-gradient', 'pattern', 'image'];
		
		try{
			if(data['imgpanel_bg_value'].contains('#') != true && data['imgpanel_bg_value'] != '')
			{
				var backgroundValue = data['showcase_base_url'] + data['imgpanel_bg_value'].substr(1);
			}else{
				var backgroundValue = data['imgpanel_bg_value'].substr(1);
			}
		}catch(err){
			var backgroundValue = null;
		}
		
		var objGeneral = {
			'round-corner' 			: data['general_round_corner_radius'],
			'border-stroke' 		: data['general_border_stroke'],
			'background-color' 		: data['background_color'],
			'border-color' 			: data['general_border_color'],
			'number-images-preload' : data['general_number_images_preload'],
			'images-order'			: data['general_images_order'],
			'title-source' 			: data['general_title_source'],
			'description-source' 	: data['general_des_source'],
			'link-source' 			: data['general_link_source'],
			'open-link-in' 			: data['general_open_link_in']
		};
		
		var objImage = {
			'default-presentation'	: data['imgpanel_presentation_mode'],
			'background-type' 		: imgPanelBgType[data['imgpanel_bg_type']],
			'background-value' 		: backgroundValue,
			'show-watermark' 		: booleanArray[data['imgpanel_show_watermark']],
			'watermark-path' 		: (data['imgpanel_watermark_path'] != null && data['imgpanel_watermark_path'] != '') ? (data['showcase_base_url'] + data['imgpanel_watermark_path']) : '',
			'watermark-opacity' 	: data['imgpanel_watermark_opacity'],
			'watermark-position' 	: data['imgpanel_watermark_position'],
			'watermark-offset' 		: data['imgpanel_watermark_offset'],
			'show-inner-shadow' 	: booleanArray[data['imgpanel_show_inner_shawdow']],
			'inner-shadow-color' 	: (data['imgpanel_inner_shawdow_color'] != '') ? data['imgpanel_inner_shawdow_color'] : '' ,
			'show-overlay' 			: booleanArray[data['imgpanel_show_overlay_effect']],
			'overlay-type' 			: data['imgpanel_overlay_effect_type'],
			'fitin-settings'		: {
											'transition-type' 		: data['imgpanel_img_transition_type_fit'],
											'transition-timing' 	: data['imgpanel_img_transition_timing_fit'],
											'click-action' 			: data['imgpanel_img_click_action_fit']
									  },
			'expandout-settings'	: {
											'transition-type' 		: data['imgpanel_img_transition_type_expand'],
											'transition-timing' 	: data['imgpanel_img_transition_timing_expand'],
											'motion-type' 			: data['imgpanel_img_motion_type_expand'],
											'motion-timing' 		: data['imgpanel_img_motion_timing_expand'],
											'click-action' 			: data['imgpanel_img_click_action_expand']
									  }		
		};
		
		var objThumb = {
			'show-panel' 					: data['thumbpanel_show_panel'],
			'panel-position' 				: data['thumbpanel_panel_position'],
			'collapsible-panel' 			: booleanArray[data['thumbpanel_collapsible_position']],
			'background-color'	 			: data['thumbpanel_thumnail_panel_color'],
			'thumbnail-row' 				: data['thumbpanel_thumb_row'],
			'thumbnail-width' 				: data['thumbpanel_thumb_width'],
			'thumbnail-height' 				: data['thumbpanel_thumb_height'],
			'active-state-color' 			: data['thumbpanel_active_state_color'],
			'normal-state-color' 			: data['thumbpanel_thumnail_normal_state'],
			'thumbnails-browsing-mode'	 	: data['thumbpanel_thumb_browsing_mode'],
			'thumbnails-presentation-mode'  : data['thumbpanel_presentation_mode'],
			'thumbnail-border'	 			: data['thumbpanel_border'],
			'show-thumbnails-status' 		: booleanArray[data['thumbpanel_show_thumb_status']],
			'enable-big-thumbnail'	 		: booleanArray[data['thumbpanel_enable_big_thumb']],
			'big-thumbnail-size' 			: data['thumbpanel_big_thumb_size'],
			'big-thumbnail-color' 			: data['thumbpanel_big_thumb_color'],
			'big-thumbnail-border'	 		: data['thumbpanel_thumb_border']
		};
		
		var objInfo = {
			'panel-presentation' 			: data['infopanel_presentation'],
			'panel-position'				: data['infopanel_panel_position'],
			'background-color-fill' 		: data['infopanel_bg_color_fill'],
			'show-title' 					: booleanArray[data['infopanel_show_title']],
			'click-action'	 				: data['infopanel_panel_click_action'],
			'title-css' 					: (data['infopanel_title_css'] !='') ? data['infopanel_title_css'] : '',
			'show-description'	 			: booleanArray[data['infopanel_show_des']],
			'description-length-limitation' : data['infopanel_des_lenght_limitation'],
			'description-css' 				: (data['infopanel_des_css'] !='') ? data['infopanel_des_css'] : '',
			'show-link'						: booleanArray[data['infopanel_show_link']],
			'link-css' 						: (data['infopanel_link_css'] != '') ? data['infopanel_link_css'] : ''	
		};
		
		var objToolbar = {
			'panel-position' 				: data['toolbarpanel_panel_position'],
			'panel-presentation' 			: data['toolbarpanel_presentation'],
			'show-image-navigation' 		: booleanArray[data['toolbarpanel_show_image_navigation']],
			'show-slideshow-player' 		: booleanArray[data['toolbarpanel_slideshow_player']],
			'show-fullscreen-switcher' 		: booleanArray[data['toolbarpanel_show_fullscreen_switcher']],
			'show-tooltip' 					: booleanArray[data['toolbarpanel_show_tooltip']]	
		};
		
		var objSlide = {
			'image-presentation'	: data['slideshow_presentation_mode'],
			'slide-timing' 			: data['slideshow_slide_timing'],
			'auto-play' 			: booleanArray[data['slideshow_process']],
			'slideshow-looping' 	: booleanArray[data['slideshow_looping']],
			'enable-kenburn'		: booleanArray[data['slideshow_enable_ken_burn_effect']],
			'show-status' 			: booleanArray[data['slideshow_show_status']]
		};
		
		if(data['slideshow_show_thumb_panel'] == 'inherited')
		{
			var objShowThumb = {
				'show-thumbnail-panel' : data['thumbpanel_show_panel']
			};
		}
		else
		{
			var objShowThumb = {
				'show-thumbnail-panel' : data['slideshow_show_thumb_panel']
			};
		}
		
		if(data['slideshow_show_image_navigation'] == 2)
		{
			var objNAV = {
				'show-image-navigation' : booleanArray[data['toolbarpanel_show_image_navigation']]
			};
		}
		else
		{
			var objNAV = {
				'show-image-navigation' : booleanArray[data['slideshow_show_image_navigation']]
			};
		}

		if(data['slideshow_show_watermark'] == 2)
		{
			var objWatermark = {
				'show-watermark' : booleanArray[data['imgpanel_show_watermark']]
			};
		}
		else
		{
			var objWatermark = {
				'show-watermark' : booleanArray[data['slideshow_show_watermark']]
			};
		}
		
		if(data['slideshow_show_overlay_effect'] == 2)
		{
			var objOverlay = {
				'show-overlay' : booleanArray[data['imgpanel_show_overlay_effect']]
			};
		}
		else
		{
			var objOverlay = {
				'show-overlay' : booleanArray[data['slideshow_show_overlay_effect']]
			};
		}
		
		var objSlideMerger = $merge(objSlide, objShowThumb, objNAV, objWatermark, objOverlay);
		
		var objShowcase = {
			'showcase' : {
				'general': objGeneral,
				'image-panel' : objImage,
				'thumbnail-panel' : objThumb,
				'information-panel' : objInfo,
				'toobar-panel' : objToolbar,
				'slideshow' : objSlideMerger
			}
		};
		
		return Json.toString(objShowcase);
	},
	
	changeValueFlash: function(panel, element)
	{
		var name = element.name;
		
		if(element.type == 'radio')
		{
			if(element.checked == true)
			{
				var value = element.getProperty('value');
			}
		}
		else
		{
			var value = element.getProperty('value');
		}
		
		var objParam = JSNISClassicTheme.compareFieldFlash(name, value);
		JSNISClassicTheme.sendAgrToVisualFlash(panel, objParam.name, objParam.value);
	},
	
	sendAgrToVisualFlash: function(panel, name , value)
	{
		document.getElementById('jsn-flash-visual-object').loadData(panel, name, value);
	},
	
	addEvent2ChangeValueVisualFlash: function(elementClass) 
	{
		$$('.'+elementClass).each(function(element)
		{
			var event = 'change';
			
			if (element.type == 'radio') event = 'click';
			
			element.addEvent(event, function()
			{
				JSNISClassicTheme.changeValueFlash(elementClass, element);
			});
		});
	},
	
	visualFlash: function()
	{
		this.addEvent2ChangeValueVisualFlash('imagePanel');
		this.addEvent2ChangeValueVisualFlash('informationPanel');
		this.addEvent2ChangeValueVisualFlash('thumbnailPanel');
		this.addEvent2ChangeValueVisualFlash('toolbarPanel');
	},
	
	parseParamVisualFlash: function(paramObj, value)
	{
		var booleanArray 	= ['no', 'yes'];
		var imgPanelBgType 	= ['transparent', 'solid-color', 'linear-gradient', 'radial-gradient', 'pattern', 'image'];
		var paramValue;
		var type = paramObj.type;
		
		if (type == 'boolean')
		{
			paramValue = booleanArray[value];
		}
		else if (type == 'booleanBG')
		{
			paramValue = imgPanelBgType[value];
		}
		else if (type == 'string')
		{
			paramValue = value;
		} // something else later
		
		paramName = paramObj.value;
		paramName = paramName.replace(/-/g, ' ');
		paramName = paramName.capitalize().replace(/ /g,'');
		paramName = paramName.charAt(0).toLowerCase() + paramName.slice(1);
		
		var newObj = {name : paramName, value : paramValue};
		return newObj;
	},
	
	compareSpecifyFieldFlash: function(fieldName, value)
	{
		
		if (fieldName == 'imgpanel_bg_value[]')
		{
			fieldName = 'imgpanel_bg_value';
			var backgroundValue = $('imgpanel_bg_value_first').value + "," + $('imgpanel_bg_value_last').value;
			
			if ($('imgpanel_bg_value_last').value == '')
			{
				backgroundValue = backgroundValue.slice(0, -1);
			}
			
			try
			{
				if (backgroundValue.contains('#') != true && backgroundValue != '')
				{
					var value = $('adminForm').showcase_base_url.value + backgroundValue;
				}
				else
				{
					var value = backgroundValue;
				}
			}
			catch (err)
			{
				var value = null;
			}
		}
		
		if (fieldName == 'imgpanel_watermark_path')
		{
			var value = $('adminForm').showcase_base_url.value + value;
		}
		
		var compareObj = {fieldName : fieldName, value : value};
		return compareObj;
	},
	
	compareFieldFlash: function(fieldName, value)
	{
		var compareSpecifyObj = JSNISClassicTheme.compareSpecifyFieldFlash(fieldName, value);
		var baseObj = {
//			general_round_corner_radius 	: {'type' : 'string', 'value' : 'round-conner'},
//			general_border_stroke	 		: {'type' : 'string', 'value' : 'border-stroke'}, 
//			background_color 				: {'type' : 'string', 'value' : 'background-color'}, 
//			general_border_color 			: {'type' : 'string', 'value' : 'border-color'}, 
//			general_number_images_preload	: {'type' : 'string', 'value' : 'number-images-preload'}, 
//			general_images_order 			: {'type' : 'string', 'value' : 'images-order'},
//			general_title_source 			: {'type' : 'string', 'value' : 'title-source'},
//			general_des_source	 			: {'type' : 'string', 'value' : 'description-source'},
//			general_link_source 			: {'type' : 'string', 'value' : 'link-source'},
//			general_open_link_in 			: {'type' : 'string', 'value' : 'open-link-in'},
			
			imgpanel_presentation_mode 		: {'type' : 'string', 'value' : 'default-presentation'},
			imgpanel_bg_type 				: {'type' : 'booleanBG', 'value' : 'background-type'},
			imgpanel_bg_value 				: {'type' : 'string', 'value' : 'background-value'},
			imgpanel_show_watermark 		: {'type' : 'boolean', 'value' : 'show-watermark'},
			imgpanel_watermark_path 		: {'type' : 'string', 'value' : 'watermark-path'},
			imgpanel_watermark_opacity 		: {'type' : 'string', 'value' : 'watermark-opacity'},
			imgpanel_watermark_position 	: {'type' : 'string', 'value' : 'watermark-position'},
			imgpanel_watermark_offset 		: {'type' : 'string', 'value' : 'watermark-offset'},
			imgpanel_show_inner_shawdow 	: {'type' : 'boolean', 'value' : 'show-inner-shadow'},
			imgpanel_inner_shawdow_color 	: {'type' : 'string', 'value' : 'inner-shadow-color'},
			imgpanel_show_overlay_effect 	: {'type' : 'boolean', 'value' : 'show-overlay'},
			imgpanel_overlay_effect_type 	: {'type' : 'string', 'value' : 'overlay-type'},
			
			imgpanel_img_transition_type_fit 		: {'type' : 'string', 'value' : 'transition-type'},
			imgpanel_img_transition_timing_fit	 	: {'type' : 'string', 'value' : 'transition-timing'},
			imgpanel_img_click_action_fit	 		: {'type' : 'string', 'value' : 'click-action'},
								  
			imgpanel_img_transition_type_expand 	: {'type' : 'string', 'value' : 'transition-type'},
			imgpanel_img_transition_timing_expand 	: {'type' : 'string', 'value' : 'transition-timing'},
			imgpanel_img_motion_type_expand 		: {'type' : 'string', 'value' : 'motion-type'},
			imgpanel_img_motion_timing_expand 		: {'type' : 'string', 'value' : 'motion-timing'},
			imgpanel_img_click_action_expand 		: {'type' : 'string', 'value' : 'click-action'},
						
			thumbpanel_show_panel 				: {'type' : 'string', 'value' : 'show-panel'},
			thumbpanel_panel_position	 		: {'type' : 'string', 'value' : 'panel-position'},
			thumbpanel_collapsible_position 	: {'type' : 'boolean', 'value' : 'collapsible-panel'},
			thumbpanel_thumnail_panel_color 	: {'type' : 'string', 'value' : 'background-color'},
			thumbpanel_thumb_row 				: {'type' : 'string', 'value' : 'thumbnail-row'},
			thumbpanel_thumb_width 				: {'type' : 'string', 'value' : 'thumbnail-width'},
			thumbpanel_thumb_height 			: {'type' : 'string', 'value' : 'thumbnail-height'},
			thumbpanel_active_state_color	 	: {'type' : 'string', 'value' : 'active-state-color'},
			thumbpanel_thumnail_normal_state 	: {'type' : 'string', 'value' : 'normal-state-color'},
			thumbpanel_thumb_browsing_mode	 	: {'type' : 'string', 'value' : 'thumbnails-browsing-mode'},
			thumbpanel_presentation_mode 		: {'type' : 'string', 'value' : 'thumbnails-presentation-mode'},
			thumbpanel_border	 				: {'type' : 'string', 'value' : 'thumbnail-border'},
			thumbpanel_show_thumb_status 		: {'type' : 'boolean', 'value' : 'show-thumbnails-status'},
			thumbpanel_enable_big_thumb 		: {'type' : 'boolean', 'value' : 'enable-big-thumbnail'},
			thumbpanel_big_thumb_size 			: {'type' : 'string', 'value' : 'big-thumbnail-size'},
			thumbpanel_big_thumb_color	 		: {'type' : 'string', 'value' : 'big-thumbnail-color'},
			thumbpanel_thumb_border 			: {'type' : 'string', 'value' : 'big-thumbnail-border'},
			
			infopanel_presentation 				: {'type' : 'string', 'value' : 'panel-presentation'},
			infopanel_panel_position 			: {'type' : 'string', 'value' : 'panel-position'},
			infopanel_bg_color_fill 			: {'type' : 'string', 'value' : 'background-color-fill'},
			infopanel_show_title 				: {'type' : 'boolean', 'value' : 'show-title'},
			infopanel_panel_click_action 		: {'type' : 'string', 'value' : 'click-action'},
			infopanel_title_css 				: {'type' : 'string', 'value' : 'title-css'},
			infopanel_show_des	 				: {'type' : 'boolean', 'value' : 'show-description'},
			infopanel_des_lenght_limitation 	: {'type' : 'string', 'value' : 'description-length-limitation'},
			infopanel_des_css 					: {'type' : 'string', 'value' : 'description-css'},
			infopanel_show_link 				: {'type' : 'boolean', 'value' : 'show-link'},
			infopanel_link_css	 				: {'type' : 'string', 'value' : 'link-css'},
						
			toolbarpanel_panel_position 			: {'type' : 'string', 'value' : 'panel-position'},
			toolbarpanel_presentation 				: {'type' : 'string', 'value' : 'panel-presentation'},
			toolbarpanel_show_image_navigation 		: {'type' : 'boolean', 'value' : 'show-image-navigation'},
			toolbarpanel_slideshow_player 			: {'type' : 'boolean', 'value' : 'show-slideshow-player'},
			toolbarpanel_show_fullscreen_switcher	: {'type' : 'boolean', 'value' : 'show-fullscreen-switcher'},
			toolbarpanel_show_tooltip	 			: {'type' : 'boolean', 'value' : 'show-tooltip'}
			
//			slideshow_presentation_mode 			: {'type' : 'string', 'value' : 'image-presentation'},
//			slideshow_slide_timing 					: {'type' : 'string', 'value' : 'slide-timing'},
//			slideshow_process 						: {'type' : 'boolean', 'value' : 'auto-play'},
//			slideshow_looping 						: {'type' : 'boolean', 'value' : 'slideshow-looping'},
//			slideshow_enable_ken_burn_effect 		: {'type' : 'boolean', 'value' : 'enable-kenburn'},
//			slideshow_show_status	 				: {'type' : 'boolean', 'value' : 'show-status'},
//			slideshow_show_thumb_panel 				: {'type' : 'boolean', 'value' : 'show-thumbnail-panel'},
//			slideshow_show_image_navigation 		: {'type' : 'boolean', 'value' : 'show-image-navigation'},
//			slideshow_show_watermark 				: {'type' : 'boolean', 'value' : 'show-watermark'},
//			slideshow_show_overlay_effect	 		: {'type' : 'boolean', 'value' : 'show-overlay'}
		};

		return JSNISClassicTheme.parseParamVisualFlash(baseObj[compareSpecifyObj.fieldName], compareSpecifyObj.value);
	},
	
	fadeAccTitleColor : {},
	
	openAccordion: function(panelID, accID)
	{
		$(panelID).fireEvent('click');
		var accFullID = panelID + '-' + accID;
		var accordion = $(accFullID);
		
		setTimeout( function()
		{
			accordion.setStyles({'color' : '#ffcd3f'});
			if (accordion.hasClass('down') == false)
			{
				accordion.fireEvent('click');
			}

			if (JSNISClassicTheme.fadeAccTitleColor[accFullID + '-status'] == undefined || JSNISClassicTheme.fadeAccTitleColor[accFullID + '-status'] == false)
			{
				JSNISClassicTheme.fadeAccTitleColor[accFullID + '-status'] = true;
				if (JSNISClassicTheme.fadeAccTitleColor[accFullID] == undefined)
				{
					JSNISClassicTheme.fadeAccTitleColor[accFullID] = new Fx.Style(accordion, 'color', {wait: false, duration : 1000});
				}
				
				setTimeout(function()
				{
					JSNISClassicTheme.fadeAccTitleColor[accFullID].start('#ffcd3f', '#ffffff').chain(function()
					{
						accordion.setStyles({'color' : ''});
						JSNISClassicTheme.fadeAccTitleColor[accFullID + '-status'] = false;
					});
				}, 3000);
			}
		}, 200);
	},
	
	getCurrentShowcaseSetting: function()
	{
		return JSNISClassicTheme.currentShowcaseSetting;
	},
	
	showPreviewHintText: function()
	{
		var hintText 	= $('jsn-preview-hint-text');
		var content 	= $('jsn-preview-hint-text-content');
		var hintTextImg = $('jsn-preview-hint-text-img');
		hintTextImg.addEvent('mouseover', function()
		{
			hintTextImg.addClass('hint-text-active').removeClass('hint-text-deactive');
			hintText.setStyles({'width':'500px'});
			content.style.display = 'block';
		});
		
		hintText.addEvent('mouseleave', function()
		{
			hintTextImg.removeClass('hint-text-active').addClass('hint-text-deactive');
			hintText.setStyles({'width':''});
			content.style.display = 'none';
		});
	},
	
	closeInfoBarPreview: function()
	{
		JSNISUtils.setCookie('jsn-info-bar-close', 1, 15);
		$('jsn-info-bar').style.display = 'none';
	}
};