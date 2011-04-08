/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISModule = 
{
		init: function()
		{
			JSNISModule.paramSelect();
		},
		
		paramSelect: function(id)
		{			
			var showlist 	  = document.getElementById('paramsshowlist_id');
			var countShowlist = showlist.options.length;
			
			if(showlist.value == 0)
			{
				showlist.style.background = '#CC0000';
				showlist.style.color = '#fff';
				$$('#paramsshowlist_id .jsn-icon-warning').addClass('show-icon-warning');
			}
			else
			{
				showlist.style.background = '#FFFFDD';
				showlist.style.color = '#000';
				$('jsn-showlist-icon-warning').removeClass('show-icon-warning');
			}			
			for(var i = 0; i < countShowlist; i++) 
			{				
				showlist.options[i].style.background = '#FFFFDD';
				showlist.options[i].style.color = '#000';
			}	

			var showcase 	  = document.getElementById('paramsshowcase_id');
			var countShowcase = showcase.options.length;
			if(showcase.value == 0)
			{
				showcase.style.background = '#CC0000';
				showcase.style.color = '#fff';
				$('jsn-showcase-icon-warning').addClass('show-icon-warning');
			}
			else
			{
				showcase.style.background = '#FFFFDD';
				showcase.style.color = '#000';
				$('jsn-showcase-icon-warning').removeClass('show-icon-warning');
			}
			for(var i = 0; i < countShowcase; i++) 
			{				
				showcase.options[i].style.background = '#FFFFDD';
				showcase.options[i].style.color = '#000';
				
			}
		
			$('paramsshowlist_id').addEvent('change',function()
			{
				if($('paramsshowlist_id').value == 0)
				{		
					showlist.style.background = '#CC0000';
					showlist.style.color = '#fff';
					$('jsn-showlist-icon-warning').addClass('show-icon-warning');
					$$('#paramsshowlist_id .jsn-icon-warning').addClass('show-icon-warning');	
				}else{
					showlist.style.background = 'none';
					showlist.style.color = '#000';
					$('jsn-showlist-icon-warning').removeClass('show-icon-warning');
				}
			});	
			
			$('paramsshowcase_id').addEvent('change',function()
			{
				if(showcase.value == 0){
					showcase.style.background = '#CC0000';
					showcase.style.color = '#fff';
					$('jsn-showcase-icon-warning').addClass('show-icon-warning');
				}else{
					showcase.style.background = 'none';
					showcase.style.color = '#000';
					$('jsn-showcase-icon-warning').removeClass('show-icon-warning');
				}
			});	
		}
};

window.addEvent('domready', function(){
	JSNISModule.init();
});