<?php 
	$url = JURI::base().'components/com_imageshow/assets/swf';
	$objJSNFlex = JSNISFactory::getObj('classes.jsn_is_flex');
	$token = $objJSNFlex->getToken();
	$showlistID = JRequest::getVar('showlist_id');
?>
<script type="text/javascript" src="<?php echo dirname(JURI::base()); ?>/components/com_imageshow/jscript/swfobject.js"></script>
<script type="text/javascript">
	var url = '<?php echo $url;?>';
	var flashvars = {baseurl:'<?php echo JURI::base()."index.php"; ?>', siteurl:'<?php echo dirname(JURI::base()); ?>',showlistid:'<?php echo (int)$showlistID;?>', option:'com_imageshow', controller:'flex',token:'<?php echo $token; ?>'};
	var params = {bgcolor:'#FFFFFF', allowFullScreen:'true',allowScriptAccess:'sameDomain', quality:'high'};
	
	swfobject.embedSWF(
			url+"/CairngormImageshow.swf", 
			"flash", 
			"1610", "580", "9.0.0", 
			url+"/playerProductInstall.swf",
			 flashvars, params);
</script>

<div id="flash">
	<p>In order to view this page you need Flash Player 9+ support!</p>
	<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
</div>
<noscript>
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="1600" height="768" id="Index">
                <param name="movie" value="<?php echo $url;?>/CairngormImageshow.swf" />
                <param name="quality" value="high" />
                <param name="bgcolor" value="#ffffff" />
                <param name="allowScriptAccess" value="sameDomain" />
                <param name="allowFullScreen" value="true" />
                <!--[if !IE]>-->
                <object type="application/x-shockwave-flash" data="<?php echo $url;?>/Index.swf" width="1600" height="768">
                    <param name="quality" value="high" />
                    <param name="bgcolor" value="#ffffff" />
                    <param name="allowScriptAccess" value="sameDomain" />
                    <param name="allowFullScreen" value="true" />
                <!--<![endif]-->
                <!--[if gte IE 6]>-->
                <p> 
                	Either scripts and active content are not permitted to run or Adobe Flash Player version
                	10.0.0 or greater is not installed.
                </p>
                <!--<![endif]-->
                    <a href="http://www.adobe.com/go/getflashplayer">
                        <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" />
                    </a>
                <!--[if !IE]>-->
                </object>
                <!--<![endif]-->
            </object>
    </noscript>		

