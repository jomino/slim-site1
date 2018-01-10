CREATE TABLE `dtypes` (
  `id_dtype` tinyint unsigned NOT NULL auto_increment,
  `ref_dtype` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_dtype`),
  KEY `ref_dtype` (`ref_dtype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `dtypes` (`id_dtype`, `ref_dtype`)
    VALUES (1, 'service'),
        (2, 'lease'),
        (3, 'insurance'),
        (4, 'state'),
        (5, 'guarantee'),
        (6, 'other');