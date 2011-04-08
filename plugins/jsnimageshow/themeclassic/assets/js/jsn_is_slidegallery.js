/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISSlideGallery = new Class({
	Version : "1.1",
	Implements: [Options, Events],
	options: {
		nextButton: ".jsnis-slide-nav-right",
		previousButton: ".jsnis-slide-nav-left",
		stopButton: ".jsnis-slide-controller-pause",
		playButton: ".jsnis-slide-controller-play",
		loading: ".jsnis-slide-loading",
		duration: 500,
		changeSlideDuration: 4000,
		positionController: 'bottom',
		current: 0,
		direction: 'next',
		fps: 25,
		slides: [],
		links:[]
	},
	initialize: function(container, options)
	{
		this.gallery 		= $(container);
		this.galleryID		= container;
		this.currentIndex	= 0;
		this.setOptions(options);
		this.nextButton 	= this.gallery.getElement(this.options.nextButton);
		this.previousButton = this.gallery.getElement(this.options.previousButton);
		this.playButton		= this.gallery.getElement(this.options.playButton);
		this.stopButton		= this.gallery.getElement(this.options.stopButton);
		this.loading		= this.gallery.getElement(this.options.loading);
		this.interval     	= null;
		this.buttonOnclick	= true;
		this.currentSlide	= null;
		this.buttonStatus 	= false;
		
		if(this.buttonStatus == false){
			this.hoverButton();
			this.button();
		}
		
		if(this.options.slides.length > 0){
			var image = this.loadImage();
			this.currentSlide.setStyles({'opacity' : 1, 'filter' : 'alpha(opacity=100)'});
		}
		
		if(this.nextButton)
		{
			this.nextButton.addEvent('click', function(){
				this.next();
			}.bind(this));
		}
		
		if(this.previousButton)
		{
			this.previousButton.addEvent('click', function(){
				this.previous();
			}.bind(this));
		}
		
		if(this.playButton)
		{
			this.playButton.addEvent('click', function()
			{
				this.nextButton.setStyles({'display':'none'});
				this.previousButton.setStyles({'display':'none'});
				
				this.playButton.setStyles({'display':'none'});
				this.stopButton.setStyles({'display':'block'});
				
				this.play();
			}.bind(this));
		}
		
		if(this.stopButton)
		{
			this.stopButton.addEvent('click', function()
			{
				this.nextButton.setStyles({'display':'block'});
				this.previousButton.setStyles({'display':'block'});
				
				this.stopButton.setStyles({'display':'none'});
				this.playButton.setStyles({'display':'block'});
				
				$clear(this.interval);
				this.interval = null;
			}.bind(this));
		}
	},
	
	hoverButton : function()
	{
		this.gallery.addEvents({
			'mouseenter': function()
			{
				if(this.interval !=  null){
					this.stopButton.setStyles({'display':'block'});
				}else{
					this.nextButton.setStyles({'display':'block'});
					this.previousButton.setStyles({'display':'block'});
					this.playButton.setStyles({'display':'block'});
				}
			}.bind(this),
			'mouseleave': function()
			{
				if(this.interval != null){
					this.stopButton.setStyles({'display':'none'});
				}else{
					this.nextButton.setStyles({'display':'none'});
					this.previousButton.setStyles({'display':'none'});
					this.playButton.setStyles({'display':'none'});
				}
			}.bind(this)
		});
	},
	
	button: function()
	{
		var galleryWidth 	= this.gallery.offsetWidth.toInt();
		var galleryHeight  	= this.gallery.offsetHeight.toInt();
		var width  			= galleryWidth/10;
		if (width <= 0) return;
		if(width > 50)
		{
			var marginTop 	= (width-50)/2;
			this.nextButton.getElement('img').setStyles({'margin-top':marginTop});
			this.previousButton.getElement('img').setStyles({'margin-top':marginTop});
			this.playButton.getElement('img').setStyles({'margin-top':marginTop});
			this.stopButton.getElement('img').setStyles({'margin-top':marginTop});
			this.loading.getElement('img').setStyles({'margin-top':marginTop});
		}
		else
		{
			this.nextButton.setStyles({'border-radius':'3px 0px 0px 3px', '-moz-border-radius':'3px 0px 0px 3px'});
			this.previousButton.setStyles({'border-radius':'0px 3px 3px 0px', '-moz-border-radius':'0px 3px 3px 0px'});
			this.playButton.setStyles({'border-radius':'3px 3px 0px 0px', '-moz-border-radius':'3px 3px 0px 0px'});
			this.stopButton.setStyles({'border-radius':'3px 3px 0px 0px', '-moz-border-radius':'3px 3px 0px 0px'});
			this.loading.getElement('img').setStyles({'width':width-2, 'margin-top':2/2});
			this.loading.setStyles({'border-radius':'2px', '-moz-border-radius':'2px'});
		}
		
		var top 			= (galleryHeight - width)/2;
		var right 			= 0;
		var left 			= 0;
		this.nextButton.setStyles({'width' : width, 'height' : width, 'top' : top, 'right' : right});
		this.previousButton.setStyles({'width' : width, 'height' : width, 'top' : top, 'left' : left});
		
		var top 			= (galleryHeight - width)/2;
		var left 			= (galleryWidth - width)/2;
		this.loading.setStyles({'width' : width, 'height' : width, 'top' : top, 'left' : left});
		
		if(this.options.positionController == 'bottom'){
			var bottom = 0;
			this.playButton.setStyles({'width' : width, 'height' : width, 'left' : left, 'bottom' : bottom});
			this.stopButton.setStyles({'width' : width, 'height' : width, 'left' : left, 'bottom' : bottom});
		}else{
			var top = 0;
			this.playButton.setStyles({'width' : width, 'height' : width, 'left' : left, 'top' : top});
			this.stopButton.setStyles({'width' : width, 'height' : width, 'left' : left, 'top' : top});
		}
		
		this.buttonStatus = true;
	},
	
	loadImage : function()
	{
		this.loading.setStyles({'display':'block'});
		var img = new Asset.images(this.options.slides[this.currentIndex],
				{
					onComplete:function(){
						this.currentSlide.removeProperty('width');
						this.currentSlide.removeProperty('height');
						this.createSlide();
						this.loading.setStyles({'display':'none'});
					}.bind(this)
				});
		return this.currentSlide = img[0];
	},
	
	createSlide : function()
	{
		this.currentSlide.addClass('slide');
		
		var linkElement = new Element('a',
		{
			'href':(this.options.links[this.currentIndex] != '') ? this.options.links[this.currentIndex] : '#',
			'target':'_blank'
		});

		this.currentSlide.injectInside(linkElement);
		linkElement.injectInside(this.galleryID);
		this.resize(this.currentSlide);
	},
	
	fade : function()
	{
		this.loading.setStyles({'display':'block'});
		
		this.currentFx =  new Fx.Style(this.currentSlide, "opacity",
		{
			duration: this.options.duration, 
			transition: Fx.Transitions.linear, 
			wait: false, 
			fps: this.options.fps,
			onComplete : function()
			{
				var nextFx =  new Fx.Style(this.currentSlide, "opacity", 
				{
					duration: this.options.duration, 
					transition: Fx.Transitions.linear, 
					wait:false, 
					fps:this.options.fps,
					onComplete: function(){
						this.buttonOnclick = true;
					}.bind(this)
				}).start(0, 1);
			}.bind(this)
		});
		
		if(this.options.direction == 'next'){
			this.currentIndex = (this.currentIndex < this.options.slides.length - 1 ? this.currentIndex + 1 : 0);
		}else{
			this.currentIndex = (this.currentIndex == 0 ? this.options.slides.length - 1 : this.currentIndex - 1);
		}
		
		var img = new Asset.images(this.options.slides[this.currentIndex],
		{
			onComplete:function()
			{
				this.currentSlide.removeProperty('width');
				this.currentSlide.removeProperty('height');
				this.createSlide();
				this.loading.setStyles({'display':'none'});
				this.currentFx.start(1,0);
			}.bind(this)
		});
		
		this.currentSlide = img[0];
		
	},
	
	play : function(){
		this.options.direction = 'next';
		this.interval = this.fade.bind(this).periodical(this.options.changeSlideDuration); 
	},
	
	next : function()
	{
		 this.options.direction = 'next';
		 if(this.buttonOnclick == true){
			 this.buttonOnclick = false;
			 this.fade();
		 }
		 return false;
	},
	
	previous: function()
	{
		 this.options.direction = 'previous';
		 if(this.buttonOnclick == true){
			 this.buttonOnclick = false;
			 this.fade();
		 }
		 return false;
	},
	
	resize: function(img)
	{
		var gallery 	  = $(this.galleryID);
		var galleryW      = gallery.offsetWidth.toInt();
		var galleryH 	  = gallery.offsetHeight.toInt();
		var imageW		  = img.offsetWidth.toInt();
		var imageH		  = img.offsetHeight.toInt();
		var topOffset	  = 0;
		var leftOffset    = 0;
		var imageRatio    = imageW/imageH;
		var galleryRatio  = galleryW/galleryH;
		
		if(imageW == 0 || imageH == 0){
			return true;
		}
		
		if(imageW < galleryW && imageH < galleryH)
		{
			img.style.height  	= imageH + "px";
			img.style.width   	= imageW + "px";
			var top 			= (galleryH - imageH)/2;
			var left 			= (galleryW - imageW)/2;
			img.style.top 		= top  + "px";
			img.style.left 		= left + "px";
		}
		else
		{
			if(imageRatio > galleryRatio)
			{
				img.style.height   	= galleryH + "px";
				var resizedW 	  	= img.offsetWidth.toInt();
				var tmpLeftOffset 	=  (resizedW - galleryW)/2;						
				leftOffset    		=  -tmpLeftOffset;
			}
			else
			{
				img.style.width   	= galleryW + "px";
				var resizedH 	 	= img.offsetHeight.toInt();
				var tmpTopOffset 	=  (resizedH - galleryH)/2;						
				topOffset    		=  -tmpTopOffset;
			}
			img.style.left 	= leftOffset + "px";
			img.style.top 	= topOffset + "px";
		}		
	}
});
JSNISSlideGallery.implement(new Options, new Events);