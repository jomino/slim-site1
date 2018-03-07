CREATE TABLE `calendars` (
  `id_cal` int(8) unsigned NOT NULL auto_increment,
  `id_cli` int(8) unsigned NOT NULL DEFAULT 0,
  `id_caltype` smallint(3) NOT NULL DEFAULT 1,
  `title` varchar(128) NOT NULL DEFAULT '',
  `description` tinytext NOT NULL DEFAULT '',
  PRIMARY KEY  (`id_cal`),
  KEY `id_cli` (`id_cli`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;