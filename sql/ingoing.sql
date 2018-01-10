CREATE TABLE `ingoing` (
  `id_ingo` int(8) unsigned NOT NULL auto_increment,
  `id_cat` tinyint unsigned NOT NULL default 1,
  `id_ref` int(8) unsigned NOT NULL default 0,
  `id_cli` int(8) unsigned NOT NULL default 0,
  PRIMARY KEY  (`id_ingo`),
  KEY `id_cat` (`id_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;