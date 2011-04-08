/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISAccordions = new Class({
	initialize: function(element, options) {	
		this.options 	= Object.extend(options);		
		this.el 		= $(element);
		this.elID 		= element;		
		this.list 		= $$('#' + this.elID + ' div.jsn-accordion-pane');		
		this.headings 	= $$('#' + this.elID + ' .jsn-accordion-title');
		this.button		= $$('#' + this.elID + ' .jsn-accordion-title .jsn-accordion-button');
		
		this.multipleOpen(this.options.activeClass, this.options.showFirstElement, this.options.durationEffect);
		this.enableControlAccordion();		
	},
	
	multipleOpen:function(activeClass, showFirstElement, durationEffect)
	{
		var list     	 = this.list;
		var collapsibles = new Array();
		var headings	 = this.headings;
		var button		 = this.button;
		var buttonClass	 = 'button-' + activeClass;
		
		headings.each( function(heading, i) 
		{
			var collapsible = new Fx.Slide(list[i], 
			{
				duration: durationEffect,
				transition: Fx.Transitions.linear
			});
			
			collapsibles[i] = collapsible;
			
			heading.addEvent('click', function()
			{
				collapsible.toggle();		        						    
				return false;
			}.bind(this));
			
			
			if (showFirstElement && i == 0)
			{
				if(headings[0].className.indexOf(activeClass) == -1)
				{
					headings[0].addClass(activeClass);
					button[0].addClass(buttonClass);
				}
			}
			else
			{
				collapsible.hide();
			}
						
			collapsible.addEvent('onStart', function() 
			{
				if (heading)
				{
					if (heading.className.indexOf(activeClass) == -1)
					{
						heading.addClass(activeClass);
						button[i].addClass(buttonClass);
					}
					else
					{						
						heading.removeClass(activeClass);
						button[i].removeClass(buttonClass);
					}
				}
			}.bind(this));			
		}.bind(this));
	},
	
	enableControlAccordion: function()
	{
		var parent 			= this.el;
		var expandButton 	= parent.getElement('div[class=jsn-accordion-control]').getElements('span')[0];
		var collapseButton 	= parent.getElement('div[class=jsn-accordion-control]').getElements('span')[1];
		var accordions 		= parent.getElements('div[class^=jsn-accordion-title]');
		
		expandButton.addEvent('click', function()
		{
			accordions.each(function(acc)
			{
				if (acc.hasClass('down') == false)
				{
					acc.fireEvent('click');
				}
			});
		});
		
		collapseButton.addEvent('click', function()
		{
			accordions.each(function(acc)
			{
				if (acc.hasClass('down') == true)
				{
					acc.fireEvent('click');
				}
			});
		});
	}
});