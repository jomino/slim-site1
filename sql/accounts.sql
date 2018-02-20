CREATE TABLE `accounts` (
  `id_acc` int(8) unsigned NOT NULL auto_increment,
  `id_cli` int(8) unsigned NOT NULL default 0,
  `id_cat` tinyint unsigned NOT NULL default 1,
  `id_actype` tinyint unsigned NOT NULL default 1,
  PRIMARY KEY  (`id_acc`),
  KEY `id_actype` (`id_actype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;