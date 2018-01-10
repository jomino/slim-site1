CREATE TABLE `utypes` (
  `id_utype` tinyint unsigned NOT NULL auto_increment,
  `ref_utype` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_utype`),
  KEY `ref_utype` (`ref_utype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `utypes` (`id_utype`, `ref_utype`)
    VALUES (1, 'other'),
        (2, 'tenant'),
        (3, 'owner'),
        (4, 'syndic');