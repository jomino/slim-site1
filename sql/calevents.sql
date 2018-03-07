CREATE TABLE `calevents` (
 `id_cev` int(11) unsigned NOT NULL auto_increment,
 `id_cal` int(8) unsigned NOT NULL DEFAULT 0,
 `id_cevtype` tinyint unsigned NOT NULL DEFAULT 1,
 `start` datetime NOT NULL,
 `end` datetime NOT NULL,
 `title` varchar(128) NOT NULL DEFAULT '',
 `description` text NOT NULL DEFAULT '',
  PRIMARY KEY  (`id_cev`),
  KEY `id_cal` (`id_cal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;