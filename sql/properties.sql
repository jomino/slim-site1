CREATE TABLE `properties` (
  `id_prop` int(8) unsigned NOT NULL auto_increment,
  `id_ptype` tinyint unsigned NOT NULL default 1,
  `id_ref` varchar(16) NOT NULL default '',
  `price` smallint(5) NOT NULL default 0,
  `name` varchar(128) NOT NULL default '',
  `street` varchar(128) NOT NULL default '',
  `num` varchar(16) NOT NULL default '',
  `cp` varchar(8) NOT NULL default '',
  `ville` varchar(48) NOT NULL default '',
  `id_cty` smallint(5) NOT NULL default 56,
  `datein` datetime NOT NULL,
  `datemod` datetime NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY  (`id_prop`),
  KEY `id_ptype` (`id_ptype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;