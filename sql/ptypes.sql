CREATE TABLE `ptypes` (
  `id_ptype` tinyint unsigned NOT NULL auto_increment,
  `ref_ptype` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_ptype`),
  KEY `ref_ptype` (`ref_ptype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `ptypes` (`id_ptype`, `ref_ptype`)
    VALUES (1, 'other'),
        (2, 'appart'),
        (3, 'house');