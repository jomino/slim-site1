-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mer 29 Novembre 2017 à 19:39
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
-- Structure de la table `clitypes`
--

CREATE TABLE `clitypes` (
  `id_clit` tinyint(3) UNSIGNED NOT NULL,
  `ref_clit` varchar(16) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `clitypes`
--

INSERT INTO `clitypes` (`id_clit`, `ref_clit`) VALUES
(1, 'default'),
(2, 'affiliate'),
(3, 'shared');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `clitypes`
--
ALTER TABLE `clitypes`
  ADD PRIMARY KEY (`id_clit`),
  ADD KEY `ref_clit` (`ref_clit`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `clitypes`
--
ALTER TABLE `clitypes`
  MODIFY `id_clit` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
