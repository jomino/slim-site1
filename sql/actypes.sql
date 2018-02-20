CREATE TABLE `actypes` (
  `id_actype` tinyint unsigned,
  `seats` tinyint unsigned,
  `ref_actype` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id_actype`),
  KEY `ref_actype` (`ref_actype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `actypes` (`id_actype`, `seats`, `ref_actype`)
    VALUES (1, 0, 'unlimited'),
        (2, 1, 'free'),
        (3, 10, 'limited10'),
        (4, 15, 'limited15'),
        (5, 20, 'limited20'),
        (6, 25, 'limited25'),
        (7, 50, 'limited50');