CREATE TABLE `lang` (
  `id_lang` tinyint unsigned NOT NULL auto_increment,
  `ref_lang` char(2) NOT NULL default '',
  PRIMARY KEY  (`id_lang`),
  KEY `ref_lang` (`ref_lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `lang` (`id_lang`, `ref_lang`)
    VALUES (1, 'en'),
        (2, 'fr'),
        (3, 'nl');