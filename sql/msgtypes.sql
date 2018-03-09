CREATE TABLE `msgtypes` (
  `id_msgtype` tinyint unsigned NOT NULL auto_increment,
  `ref_msgtype` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_msgtype`),
  KEY `ref_msgtype` (`ref_msgtype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `msgtypes` (`id_msgtype`, `ref_msgtype`)
    VALUES (1, 'draft'),
        (2, 'sent'),
        (3, 'received');    