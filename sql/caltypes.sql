CREATE TABLE `caltypes` (
  `id_caltype` tinyint unsigned NOT NULL auto_increment,
  `ref_caltype` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_caltype`),
  KEY `ref_caltype` (`ref_caltype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `caltypes` (`id_caltype`, `ref_caltype`)
    VALUES (1, 'local'),
        (2, 'google'),
        (3, 'shared');