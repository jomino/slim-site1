CREATE TABLE `messages` (
 `id_msg` int(11) unsigned NOT NULL auto_increment,
 `id_cli` int(8) unsigned NOT NULL DEFAULT 0,
 `id_user` int(8) unsigned NOT NULL DEFAULT 0,
 `id_cls` tinyint unsigned NOT NULL DEFAULT 1,
 `id_msgtype` tinyint unsigned NOT NULL DEFAULT 1,
 `recieved` datetime NOT NULL,
 `sent` datetime NOT NULL,
 `read` tinyint unsigned NOT NULL DEFAULT 0,
 `uid` varchar(255) NOT NULL DEFAULT '',
 `title` varchar(128) NOT NULL DEFAULT '',
 `text` text NOT NULL DEFAULT '',
  PRIMARY KEY  (`id_msg`),
  KEY `id_cli` (`id_cli`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;    