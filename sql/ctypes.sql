CREATE TABLE `ctypes` (
  `id_ctype` tinyint unsigned NOT NULL auto_increment,
  `ref_ctype` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_ctype`),
  KEY `ref_ctype` (`ref_ctype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `ctypes` (`id_ctype`, `ref_ctype`)
    VALUES (1, 'none'),
        (2, 'phone'),
        (3, 'email'),
        (4, 'fax');