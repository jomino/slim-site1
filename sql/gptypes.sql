CREATE TABLE `gptypes` (
  `id_gptype` tinyint unsigned NOT NULL auto_increment,
  `ref_gptype` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_gptype`),
  KEY `ref_gptype` (`ref_gptype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `gptypes` (`id_gptype`, `ref_gptype`)
    VALUES (0, 'rent'),
        (1, 'insurance'),
        (2, 'heating'),
        (6, 'countdown'),
        (8, 'maintains'),
        (3, 'allowances'),
        (7, 'cleaning'),
        (4, 'recall'),
        (5, 'other');