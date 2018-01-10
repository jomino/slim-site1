CREATE TABLE `clitypes` (
  `id_clit` tinyint unsigned NOT NULL auto_increment,
  `ref_clit` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_clit`),
  KEY `ref_clit` (`ref_clit`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `clitypes` (`id_clit`, `ref_clit`)
    VALUES (1, 'default'),
        (2, 'affiliate'),
        (3, 'shared');