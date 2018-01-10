CREATE TABLE `groups` (
  `id_grp` tinyint unsigned NOT NULL auto_increment,
  `ref_grp` varchar(8) NOT NULL default '',
  PRIMARY KEY  (`id_grp`),
  KEY `ref_grp` (`ref_grp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `groups` (`id_grp`, `ref_grp`)
    VALUES (1, 'default'),
        (2, 'admins'),
        (3, 'whise'),
        (4, 'c21');