/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISImageShow = {
	alternativeContent:function(){
		var objSWF = swfobject.getFlashPlayerVersion();
		var version = objSWF.major; 
		if(version == 0)
		{	
			var elementFlashObj = JSNISExtUtils.getElementsByClass(document, 'div', 'jsnis-gallery');
			for(var i = 0; i < elementFlashObj.length; i++)
			{
				JSNISExtUtils.addClass(elementFlashObj[i], 'jsnis-gallery no-swfobj-flash');
			}
			
			var elementAlternative = JSNISExtUtils.getElementsByClass(document, 'div', 'jsnis-altcontent');
			for(var i = 0; i < elementAlternative.length; i++)
			{
				JSNISExtUtils.addClass(elementAlternative[i], 'jsnis-altcontent no-flash');
			}
		}
		return true;
	},
	
	scaleResize: function(imageW, imageH, imgID)
	{
		var imageElement = document.getElementById(imgID);
		var parent 		 = imageElement.parentNode;		
		var galleryW     = parent.offsetWidth.toInt();
		var galleryH 	 = parent.offsetHeight.toInt();
		var topOffset	 = 0;
		var leftOffset   = 0;
		var imageRatio    = imageW/imageH;
		var galleryRatio  = galleryW/galleryH;
		if(galleryW == 0 || galleryH == 0){
			return;
		}
		if(imageW < galleryW && imageH < galleryH)
		{
			imageElement.style.display = "block";
			imageElement.style.height  = imageH + "px";
			imageElement.style.width   = imageW + "px";
			var top = (galleryH - imageH)/2;
			var left = (galleryW - imageW)/2;
			imageElement.style.top = top  + "px";
			imageElement.style.left = left + "px";
		}
		else
		{
			if(imageRatio > galleryRatio)
			{
				imageElement.style.display = "block";
				imageElement.style.height   = galleryH + "px";
				var resizedW 	  = imageElement.offsetWidth.toInt();
				var tmpLeftOffset =  (resizedW - galleryW)/2;						
				leftOffset    =  -tmpLeftOffset;
			}
			else
			{
				imageElement.style.display = "block";
				imageElement.style.width   = galleryW + "px";
				var resizedH 	 = imageElement.offsetHeight.toInt();
				var tmpTopOffset =  (resizedH - galleryH)/2;						
				topOffset    =  -tmpTopOffset;
			}
			imageElement.style.left = leftOffset + "px";
			imageElement.style.top = topOffset + "px";
		}		
	}
	
};