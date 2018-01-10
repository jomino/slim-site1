-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mer 29 Novembre 2017 à 19:38
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ipefix`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_cli` int(8) UNSIGNED NOT NULL,
  `id_user` int(8) UNSIGNED NOT NULL DEFAULT '0',
  `id_grp` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `id_lvl` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `id_clit` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `connected` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `uri` varchar(256) NOT NULL DEFAULT '',
  `log` varchar(128) NOT NULL DEFAULT '',
  `pwd` varchar(32) NOT NULL DEFAULT '',
  `least` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `clients`
--

INSERT INTO `clients` (`id_cli`, `id_user`, `id_grp`, `id_lvl`, `id_clit`, `connected`, `uri`, `log`, `pwd`, `least`) VALUES
(1, 1, 1, 1, 1, 1, 'www.scriptimmo.com', 'oli', '3db9007f5acd91bf68373c0128dc0724', '2017-11-29 15:39:59');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_cli`),
  ADD KEY `id_grp` (`id_grp`),
  ADD KEY `least` (`least`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_cli` int(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
