/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version 2.0
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var JSNISImageManager = {
	initialize: function()
	{
		o = this._getUriObject(window.self.location.href);
		//console.log(o);
		q = $H(this._getQueryObject(o.query));
		this.editor = decodeURIComponent(q.get('e_name'));

		// Setup image manager fields object
		this.fields			= new Object();
		this.fields.url		= $("f_url");

		// Setup image listing objects
		this.folderlist = $('folderlist');

		this.frame		= window.frames['imageframe'];
		this.frameurl	= this.frame.location.href;

		// Setup imave listing frame
		this.imageframe = $('imageframe');
		this.imageframe.manager = this;
		this.imageframe.addEvent('load', function(){ JSNISImageManager.onloadimageview(); });

		// Setup folder up button
		this.upbutton = $('upbutton');
		this.upbutton.removeEvents('click');
		this.upbutton.addEvent('click', function(){ JSNISImageManager.upFolder(); });
	},

	onloadimageview: function()
	{
		// Update the frame url
		this.frameurl = this.frame.location.href;

		var folder = this.getImageFolder();
		for(var i = 0; i < this.folderlist.length; i++)
		{
			if(folder == this.folderlist.options[i].value) {
				this.folderlist.selectedIndex = i;
				break;
			}
		}

		a = this._getUriObject($('uploadForm').getProperty('action'));
		//console.log(a);
		q = $H(this._getQueryObject(a.query));
		q.set('folder', folder);
		var query = [];
		q.each(function(v, k){
			if ($chk(v)) {
				this.push(k+'='+v);
			}
		}, query);
		a.query = query.join('&');
		$('uploadForm').setProperty('action', a.scheme+'://'+a.domain+a.path+'?'+a.query);
	},

	getImageFolder: function()
	{
		var url 	= this.frame.location.search.substring(1);
		var args	= this.parseQuery(url);

		return args['folder'];
	},

	onok: function(act)
	{
		var url		= this.fields.url.getValue();
		
		if(act == 'custom' || act =='background' || act =='pattern'){
			window.parent.$('imgpanel_bg_value_first').value = url;
			window.parent.$('imgpanel_bg_value_first').onchange();
			return false;
		}
		
		if(act == 'watermark'){
			window.parent.$('imgpanel_watermark_path').value = url;
			window.parent.$('imgpanel_watermark_path').onchange();
			return false;
		}
	},

	setFolder: function(folder)
	{
		for(var i = 0; i < this.folderlist.length; i++)
		{
			if(folder == this.folderlist.options[i].value) {
				this.folderlist.selectedIndex = i;
				break;
			}
		}
		this.frame.location.href='index.php?option=com_imageshow&controller=media&view=imageslist&tmpl=component&e_name=text&theme=themeclassic&event=loadMediaImagesList&folder=' + folder;
	},

	getFolder: function() {
		return this.folderlist.getValue();
	},

	upFolder: function()
	{
		var currentFolder = this.getFolder();
		if(currentFolder.length < 2) {
			return false;
		}

		var folders = currentFolder.split('/');
		var search = '';

		for(var i = 0; i < folders.length - 1; i++) {
			search += folders[i];
			search += '/';
		}

		// remove the trailing slash
		search = search.substring(0, search.length - 1);

		for(var i = 0; i < this.folderlist.length; i++)
		{
			var thisFolder = this.folderlist.options[i].value;

			if(thisFolder == search)
			{
				this.folderlist.selectedIndex = i;
				var newFolder = this.folderlist.options[i].value;
				this.setFolder(newFolder);
				break;
			}
		}
	},

	populateFields: function(file)
	{
		$("f_url").value = image_base_path+file;
	},

	showMessage: function(text)
	{
		var message  = $('message');
		var messages = $('messages');

		if(message.firstChild)
			message.removeChild(message.firstChild);

		message.appendChild(document.createTextNode(text));
		messages.style.display = "block";
	},

	parseQuery: function(query)
	{
		var params = new Object();
		if (!query) {
			return params;
		}
		var pairs = query.split(/[;&]/);
		for ( var i = 0; i < pairs.length; i++ )
		{
			var KeyVal = pairs[i].split('=');
			if ( ! KeyVal || KeyVal.length != 2 ) {
				continue;
			}
			var key = unescape( KeyVal[0] );
			var val = unescape( KeyVal[1] ).replace(/\+ /g, ' ');
			params[key] = val;
	   }
	   return params;
	},

	refreshFrame: function()
	{
		this._setFrameUrl();
	},

	_setFrameUrl: function(url)
	{
		if ($chk(url)) {
			this.frameurl = url;
		}
		this.frame.location.href = this.frameurl;
	},

	_getQueryObject: function(q) {
		var vars = q.split(/[&;]/);
		var rs = {};
		if (vars.length) vars.each(function(val) {
			var keys = val.split('=');
			if (keys.length && keys.length == 2) rs[encodeURIComponent(keys[0])] = encodeURIComponent(keys[1]);
		});
		return rs;
	},

	_getUriObject: function(u){
		var bits = u.match(/^(?:([^:\/?#.]+):)?(?:\/\/)?(([^:\/?#]*)(?::(\d*))?)((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[\?#]|$)))*\/?)?([^?#\/]*))?(?:\?([^#]*))?(?:#(.*))?/);
		return (bits)
			? bits.associate(['uri', 'scheme', 'authority', 'domain', 'port', 'path', 'directory', 'file', 'query', 'fragment'])
			: null;
	}
};

window.addEvent('domready', function(){
	JSNISImageManager.initialize();
});
