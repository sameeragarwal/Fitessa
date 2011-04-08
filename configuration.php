<?php
class JConfig {
/* Site Settings */
var $offline = '0';
var $offline_message = 'This site is down for maintenance.<br /> Please check back again soon.';
var $sitename = 'Fitessa';
var $editor = 'tinymce';
var $list_limit = '20';
var $legacy = '0';
/* Debug Settings */
var $debug = '0';
var $debug_lang = '0';
/* Database Settings */
var $dbtype = 'mysql';
var $host = 'localhost';
var $user = 'teama_sameer';
var $password = 'actinide';
var $db = 'teama_db';
var $dbprefix = 'jos_';
/* Server Settings */
var $live_site = '';
var $secret = 'wPi4QjKRkOFou8cf';
var $gzip = '0';
var $error_reporting = '-1';
var $helpurl = 'http://help.joomla.org';
var $xmlrpc_server = '0';
var $ftp_host = '127.0.0.1';
var $ftp_port = '21';
var $ftp_user = '';
var $ftp_pass = '';
var $ftp_root = '/public_html/joomla';
var $ftp_enable = '1';
var $force_ssl = '0';
/* Locale Settings */
var $offset = '0';
var $offset_user = '0';
/* Mail Settings */
var $mailer = 'mail';
var $mailfrom = 'sameerag@cs.berkeley.edu';
var $fromname = 'Fitessa';
var $sendmail = '/usr/sbin/sendmail';
var $smtpauth = '0';
var $smtpsecure = 'none';
var $smtpport = '25';
var $smtpuser = '';
var $smtppass = '';
var $smtphost = 'localhost';
/* Cache Settings */
var $caching = '0';
var $cachetime = '15';
var $cache_handler = 'file';
/* Meta Settings */
var $MetaDesc = 'Joomla! - the dynamic portal engine and content management system';
var $MetaKeys = 'joomla, Joomla';
var $MetaTitle = '1';
var $MetaAuthor = '1';
/* SEO Settings */
var $sef           = '0';
var $sef_rewrite   = '0';
var $sef_suffix    = '0';
/* Feed Settings */
var $feed_limit   = 10;
var $feed_email   = 'author';
var $log_path = '/home/teama/public_html/joomla/logs';
var $tmp_path = '/home/teama/public_html/joomla/tmp';
/* Session Setting */
var $lifetime = '15';
var $session_handler = 'database';
}
?>