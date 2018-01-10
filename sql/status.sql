CREATE TABLE `status` (
  `id_stat` tinyint unsigned NOT NULL auto_increment,
  `ref_stat` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_stat`),
  KEY `ref_stat` (`ref_stat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `status` (`id_stat`, `ref_stat`)
    VALUES (1, 'activ'),
        (2, 'waiting'),
        (3, 'suspended');