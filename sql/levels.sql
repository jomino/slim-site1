CREATE TABLE `levels` (
  `id_lvl` tinyint unsigned NOT NULL auto_increment,
  `ref_lvl` varchar(8) NOT NULL default '',
  PRIMARY KEY  (`id_lvl`),
  KEY `ref_lvl` (`ref_lvl`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `levels` (`id_lvl`, `ref_lvl`)
    VALUES (1, 'default'),
        (2, 'admin'),
        (3, 'redac'),
        (4, 'edit');