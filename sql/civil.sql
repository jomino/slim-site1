CREATE TABLE `civil` (
  `id_civil` tinyint unsigned NOT NULL auto_increment,
  `ref_civil` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_civil`),
  KEY `ref_civil` (`ref_civil`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `civil` (`id_civil`, `ref_civil`)
    VALUES (1, 'nc'),
        (2, 'mister'),
        (3, 'missis');