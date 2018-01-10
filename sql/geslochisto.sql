CREATE TABLE `geslochisto` (
  `idhisto` int(9) unsigned NOT NULL auto_increment,
  `idgesloc` int(9) NOT NULL default '0',
  `agence` varchar(255) NOT NULL default '',
  `date` date NULL,
  `rem` text NOT NULL,
  PRIMARY KEY  (`idhisto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;