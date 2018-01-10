CREATE TABLE `clients` (
  `id_cli` int(8) unsigned NOT NULL auto_increment,
  `id_user` int(8) unsigned NOT NULL default 0,
  `id_grp` tinyint unsigned NOT NULL default 1,
  `id_level` tinyint unsigned NOT NULL default 1,
  `id_clit` tinyint unsigned NOT NULL default 1,
  `connected` tinyint unsigned NOT NULL default 0,
  `uri` varchar(128) NOT NULL default '',
  `log` varchar(128) NOT NULL default '',
  `pwd` varchar(32) NOT NULL default '',
  `least` datetime NOT NULL,
  PRIMARY KEY  (`id_cli`),
  KEY `id_grp` (`id_grp`),
  KEY `least` (`least`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;