CREATE TABLE `banks` (
  `id_bank` smallint(5) unsigned NOT NULL auto_increment,
  `from` smallint(3) NOT NULL default 0,
  `to` smallint(3) NOT NULL default 0,
  `bic` varchar(32) NOT NULL default '',
  `name` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`id_bank`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;