CREATE TABLE `calendars` (
  `id_cal` int(8) unsigned NOT NULL auto_increment,
  `id_cli` int(8) unsigned NOT NULL,
  `id_caltype` smallint(3) NOT NULL DEFAULT '1',
  `title` varchar(128) NOT NULL DEFAULT '',
  `description` tinytext NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;