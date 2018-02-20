CREATE TABLE `category` (
  `id_cat` tinyint unsigned NOT NULL auto_increment,
  `ref_cat` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_cat`),
  KEY `ref_cat` (`ref_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `category` (`id_cat`, `ref_cat`)
    VALUES (1, 'default'),
        (2, 'users'),
        (3, 'properties'),
        (4, 'contracts');