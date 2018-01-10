CREATE TABLE `comptes` (
  `id_cpt` int(8) unsigned NOT NULL auto_increment,
  `id_user` int(8) unsigned NOT NULL default 0,
  `id_bank` smallint(5) unsigned NOT NULL default 0,
  `compte` varchar(128) NOT NULL default 'BE00000000000000',
  PRIMARY KEY  (`id_cpt`),
  KEY `id_bank` (`id_bank`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;