CREATE TABLE `vptypes` (
  `id_vptype` tinyint unsigned,
  `ref_vptype` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_vptype`),
  KEY `ref_vptype` (`ref_vptype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `vptypes` (`id_vptype`, `ref_vptype`)
    VALUES (0, 'default'),
        (1, 'monthly'),
        (2, 'quarterly'),
        (3, 'ondemand');