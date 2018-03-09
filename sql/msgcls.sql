CREATE TABLE `msgcls` (
  `id_cls` tinyint unsigned NOT NULL auto_increment,
  `ref_cls` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_cls`),
  KEY `ref_cls` (`ref_cls`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `msgcls` (`id_cls`, `ref_cls`)
    VALUES (1, 'normal'),
        (2, 'important');    