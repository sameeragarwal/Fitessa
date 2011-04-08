/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISUtils = {
	getCookie: function(cookieName)
	{
		var name, value;
		var arrayCookie = document.cookie.split(";");
		
		for (var i=0; i < arrayCookie.length; i++)
		{
			name 	= arrayCookie[i].substr(0,arrayCookie[i].indexOf("="));
			value 	= arrayCookie[i].substr(arrayCookie[i].indexOf("=")+1);
			name 	= name.replace(/^\s+|\s+$/g,"");
			
			if (name == cookieName)
		    {
				return unescape(value);
		    }
		}
	},

	setCookie: function(cookieName, value, exdays)
	{
		var exdate = new Date();
		exdate.setDate(exdate.getDate() + exdays);
		var cookieValue = escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
		document.cookie = cookieName + "=" + cookieValue;
	}
};