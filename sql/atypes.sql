CREATE TABLE `atypes` (
  `id_atype` tinyint unsigned,
  `ref_atype` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_atype`),
  KEY `ref_atype` (`ref_atype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `atypes` (`id_atype`, `ref_atype`)
    VALUES (0, 'default'),
        (1, 'abandon'),
        (2, 'risk');