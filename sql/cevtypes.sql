CREATE TABLE `cevtypes` (
  `id_cevtype` tinyint unsigned NOT NULL auto_increment,
  `ref_cevtype` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_cevtype`),
  KEY `ref_cevtype` (`ref_cevtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cevtypes` (`id_cevtype`, `ref_cevtype`)
    VALUES (1, 'default'),
        (2, 'rdv'),
        (3, 'reminder');