CREATE TABLE IF NOT EXISTS `#__imageshow_configuration` (
  `configuration_id` int(11) unsigned NOT NULL auto_increment,
  `configuration_title` varchar(255) default NULL,
  `flickr_api_key` char(150) default NULL,
  `flickr_secret_key` char(150) default NULL,
  `flickr_username` char(50) default NULL,
  `flickr_caching` tinyint(1) default '0',
  `flickr_cache_expiration` char(30) default NULL,
  `flickr_image_size` tinyint(2) default '0',
  `root_image_folder` char(255) default NULL,
  `picasa_user_name` char(100) default NULL,
  `source_type` tinyint(2) default '0',
  `published` tinyint(1) default '0',
  PRIMARY KEY  (`configuration_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__imageshow_images` (
  `image_id` int(11) NOT NULL auto_increment,
  `showlist_id` int(11) NOT NULL,
  `image_extid` varchar(255) default NULL,
  `album_extid` varchar(255) default NULL,
  `image_small` varchar(255) default NULL,
  `image_medium` varchar(255) default NULL,
  `image_big` text,
  `image_title` varchar(255) default NULL,
  `image_description` text,
  `image_link` varchar(255) default NULL,
  `ordering` int(11) default '0',
  `custom_data` tinyint(1) default '0',
  `sync` tinyint(1) default '0',
  `image_size` varchar(25) default NULL,
  PRIMARY KEY  (`image_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__imageshow_log` (
  `log_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `url` varchar(255) default NULL,
  `result` varchar(255) default NULL,
  `screen` varchar(100) default NULL,
  `action` varchar(50) default NULL,
  `time_created` datetime NULL default null,
  PRIMARY KEY  (`log_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__imageshow_showcase` (
  `showcase_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `showcase_title` varchar(255) DEFAULT NULL,
  `published` tinyint(1) DEFAULT '0',
  `ordering` int(11) DEFAULT '0',
  `background_color` char(30) DEFAULT NULL,
  `general_overall_width` char(30) DEFAULT NULL,
  `general_overall_height` char(30) DEFAULT NULL,
  `general_round_corner_radius` char(30) DEFAULT NULL,
  `general_border_stroke` char(30) DEFAULT NULL,
  `general_border_color` char(30) DEFAULT NULL,
  `general_number_images_preload` char(30) DEFAULT NULL,
  `general_open_link_in` char(30) DEFAULT NULL,
  `general_link_source` char(30) DEFAULT NULL,
  `general_title_source` char(30) DEFAULT NULL,
  `general_des_source` char(30) DEFAULT NULL,
  `general_images_order` char(30) DEFAULT NULL,
  `theme_name` varchar(255) default NULL,
  `theme_id` int(11) default '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`showcase_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__imageshow_showlist` (
  `showlist_id` int(11) NOT NULL auto_increment,
  `showlist_title` varchar(255) default NULL,
  `published` tinyint(1) default '0',
  `ordering` int(11) default '0',
  `access` tinyint(3) default NULL,
  `hits` int(11) default NULL,
  `description` text,
  `showlist_link` text,
  `alter_autid` int(11) default '0',
  `alter_id` int(11) default NULL,
  `alter_image_path` varchar(255) DEFAULT NULL, 
  `alter_module_id` int(11) default NULL,
  `seo_module_id` int(11) default NULL,
  `seo_article_id` int(11) default NULL,
  `date_create` datetime default NULL,
  `showlist_source` tinyint(2) default '0',
  `configuration_id` int(11) default '0',
  `authorization_status` tinyint(1) default '0',
  `alternative_status` tinyint(2) default '0',
  `seo_status` tinyint(2) default '0',
  `date_modified` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`showlist_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

DROP TABLE IF EXISTS `#__imageshow_messages`;
CREATE TABLE `#__imageshow_messages` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_screen` varchar(150) DEFAULT NULL,
  `published` tinyint(1) DEFAULT '1',
  `ordering` int(11) DEFAULT '0',
  PRIMARY KEY (`msg_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__imageshow_parameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `general_swf_library` tinyint(1) DEFAULT '0',
  `root_url` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

ALTER TABLE `#__imageshow_showlist` CHANGE `showlist_title` `showlist_title` VARCHAR( 255 ) CHARACTER SET utf8 NULL DEFAULT NULL;
ALTER TABLE `#__imageshow_showcase` CHANGE `showcase_title` `showcase_title` VARCHAR( 255 ) CHARACTER SET utf8 NULL DEFAULT NULL;
ALTER TABLE `#__imageshow_configuration` CHANGE `root_image_folder` `root_image_folder` CHAR( 255 ) CHARACTER SET utf8 NULL DEFAULT NULL;
ALTER TABLE `#__imageshow_configuration` CHANGE `configuration_title` `configuration_title` VARCHAR( 255 ) CHARACTER SET utf8 NULL DEFAULT NULL;
ALTER TABLE `#__imageshow_images` CHANGE `ordering` `ordering` INT( 11 ) NULL DEFAULT '0';