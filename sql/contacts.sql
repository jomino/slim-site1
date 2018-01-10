CREATE TABLE `contacts` (
  `id_ctc` int(8) unsigned NOT NULL auto_increment,
  `id_user` int(8) unsigned NOT NULL default 0,
  `id_ctype` tinyint unsigned NOT NULL default 1,
  `contact` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`id_ctc`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;