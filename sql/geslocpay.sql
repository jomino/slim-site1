CREATE TABLE `geslocpay` (
  `idpay` int(8) unsigned NOT NULL auto_increment,
  `idgesloc` int(8) NOT NULL default '0',
  `paytype` tinyint(4) NOT NULL default '0',
  `agence` varchar(128) NOT NULL default '',
  `dt_debit` date NOT NULL default '0000-00-00',
  `dt_credit` date NOT NULL default '0000-00-00',
  `dt_revers` date NOT NULL default '0000-00-00',
  `debitsum` decimal(10,2) NOT NULL default '0.00',
  `creditsum` decimal(10,2) NOT NULL default '0.00',
  `rem` tinytext NOT NULL,
  `refpay` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`idpay`),
  KEY `idgesloc` (`idgesloc`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
