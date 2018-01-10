CREATE TABLE `etatciv` (
  `id_eciv` tinyint unsigned NOT NULL auto_increment,
  `ref_eciv` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_eciv`),
  KEY `ref_eciv` (`ref_eciv`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `etatciv` (`id_eciv`, `ref_eciv`)
    VALUES (1, 'nc'),
        (2, 'alone'),
        (3, 'maried'),
        (4, 'splitted'),
        (5, 'divorced'),
        (6, 'widower');