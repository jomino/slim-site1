-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mer 29 Novembre 2017 à 19:49
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
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` int(8) UNSIGNED NOT NULL,
  `id_lang` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `id_civil` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `id_eciv` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `id_stat` tinyint(3) UNSIGNED NOT NULL DEFAULT '2',
  `id_utype` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `id_ref` varchar(32) NOT NULL DEFAULT '',
  `pnom` varchar(50) NOT NULL DEFAULT '',
  `nom` varchar(100) NOT NULL DEFAULT '',
  `soc` varchar(40) NOT NULL DEFAULT '',
  `street` varchar(128) NOT NULL DEFAULT '',
  `num` varchar(16) NOT NULL DEFAULT '',
  `cp` varchar(8) NOT NULL DEFAULT '',
  `ville` varchar(30) NOT NULL DEFAULT '',
  `id_cty` smallint(5) UNSIGNED NOT NULL DEFAULT '56',
  `born` date DEFAULT NULL,
  `wborn` varchar(50) NOT NULL DEFAULT '',
  `natio` varchar(50) NOT NULL DEFAULT '',
  `numnat` varchar(50) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `datein` date DEFAULT NULL,
  `datemod` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `id_ref` (`id_ref`),
  ADD KEY `nom` (`nom`),
  ADD KEY `id_status` (`id_stat`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(8) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
