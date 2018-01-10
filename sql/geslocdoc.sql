CREATE TABLE `geslocdoc` (
  `iddoc` int(8) NOT NULL auto_increment,
  `idgesloc` int(8) NOT NULL,
  `agence` varchar(128) NOT NULL default '',
  `doctype` tinyint NOT NULL default '0',
  `dt_in` date NULL,
  `filedatas` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`iddoc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;