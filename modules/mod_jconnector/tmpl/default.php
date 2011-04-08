<?php
//*----------------------------------------------------------------------
//jConnector (TM)
//*----------------------------------------------------------------------
//This source file is subject to the GNU General Public License (GPL)
//which is available online at http://www.gnu.org/copyleft/gpl.html
//*----------------------------------------------------------------------
//Authors: Web Scribble Solutions, Inc. (info@webscribble.com)
//Copyright 2009 Web Scribble Solutions, Inc. All rights reserved.
//Support: http://help.webscribble.com/display/jconnector/Home
//*----------------------------------------------------------------------
// jConnector is a trademark of Web Scribble Solutions, Inc.
//*----------------------------------------------------------------------

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>

<?php if (!$facebook_id){ ?>
<fb:login-button onlogin='window.location="<?php echo $uri_base; ?>modules/mod_jconnector/jconnector_server.php";' size="large" background="white" length="long"></fb:login-button>
<noscript><a href="http://www.webscribble.com/jconnector">jConnector</a>: by <a href="http://www.webscribble.com/">Web Scribble</a></noscript>
<?php }elseif(!$has_permission){ ?>
<fb:prompt-permission perms="email">Would you like to receive emails from our application?</fb:prompt-permission>
<?}?>

<script type="text/javascript">
var fb_api_key = "<?php echo $fb_key; ?>";
if (fb_api_key>"") FB.init("<?php echo $fb_key; ?>", "modules/mod_jconnector/xd_receiver.htm");
</script>