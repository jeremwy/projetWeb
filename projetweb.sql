-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 23 avr. 2020 à 18:16
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `projetweb`
--

-- --------------------------------------------------------

--
-- Structure de la table `chat`
--

DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `partie` varchar(255) COLLATE utf8_bin NOT NULL,
  `message` varchar(500) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `partie`
--

DROP TABLE IF EXISTS `partie`;
CREATE TABLE IF NOT EXISTS `partie` (
  `id` varchar(25) COLLATE utf8_bin NOT NULL,
  `nom` varchar(255) COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  `maitre` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `chefPompier` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `chefPolicier` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `chefMedecin` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `horloge` int(11) NOT NULL DEFAULT 0,
  `enCours` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `partie`
--

INSERT INTO `partie` (`id`, `nom`, `date`, `maitre`, `chefPompier`, `chefPolicier`, `chefMedecin`, `horloge`, `enCours`) VALUES
('partie1-5ea1ae82f3251', 'partie1', '2020-04-23 17:04:34', 'aTOPdVMrpA7dvMBWzfwb4wmXN', NULL, NULL, NULL, 3561, 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `nom` varchar(50) COLLATE utf8_bin NOT NULL,
  `prenom` varchar(50) COLLATE utf8_bin NOT NULL,
  `id` varchar(25) COLLATE utf8_bin NOT NULL,
  `motDePasse` varchar(255) COLLATE utf8_bin NOT NULL,
  `inscription` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`nom`,`prenom`),
  UNIQUE KEY `unique_id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`nom`, `prenom`, `id`, `motDePasse`, `inscription`) VALUES
('test', 'test', 'aTOPdVMrpA7dvMBWzfwb4wmXN', '$2y$10$v10x/xQ8IGIN5DuT4Xk7iO9KaTNtuwtPf8mxmL0nGwpe2lqjeEGIO', '2020-02-12 16:32:32'),
('seb', 'seb', 'XfbBHzJAMppNyb80HQLgLWDGm', '$2y$10$kCf4BItjV5XiHwVMgaWBK.TML5X9eCaGlVdWpGNLXPrSMDIEZO5te', '2020-04-08 13:30:49');

-- --------------------------------------------------------

--
-- Structure de la table `victime`
--

DROP TABLE IF EXISTS `victime`;
CREATE TABLE IF NOT EXISTS `victime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partie` varchar(255) COLLATE utf8_bin NOT NULL,
  `nom` varchar(255) COLLATE utf8_bin NOT NULL,
  `prenom` varchar(255) COLLATE utf8_bin NOT NULL,
  `etat` int(11) NOT NULL,
  `blessures` varchar(255) COLLATE utf8_bin NOT NULL,
  `vie` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=266 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `victime`
--

INSERT INTO `victime` (`id`, `partie`, `nom`, `prenom`, `etat`, `blessures`, `vie`) VALUES
(265, 'partie1-5ea1ae82f3251', 'Chevalier', 'Jules', 3, 'Saignement important, Saignement moyen', -0.8),
(263, 'partie1-5ea1ae82f3251', 'Lecomte', 'Christian', 2, 'Brûlures légères, Saignement moyen', 1453.6),
(264, 'partie1-5ea1ae82f3251', 'Bernard', 'Jules', 3, 'Saignement important, Petit saignement, Brûlures légères, Brûlures graves', -0.8),
(261, 'partie1-5ea1ae82f3251', 'Legrand', 'Thierry', 0, 'Traumatisme psychologique', 10000),
(262, 'partie1-5ea1ae82f3251', 'Legrand', 'Jules', 0, '', 10000);

-- --------------------------------------------------------

--
-- Structure de la table `voiture`
--

DROP TABLE IF EXISTS `voiture`;
CREATE TABLE IF NOT EXISTS `voiture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partie` varchar(255) COLLATE utf8_bin NOT NULL,
  `fonction` varchar(255) COLLATE utf8_bin NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `z` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `voiture`
--

INSERT INTO `voiture` (`id`, `partie`, `fonction`, `x`, `y`, `z`) VALUES
(28, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(27, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(26, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(24, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(25, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(23, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(29, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(30, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(31, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(32, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(33, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(34, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(35, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(36, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(37, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(38, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(39, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(40, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(41, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(42, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(43, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(44, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(45, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(46, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(47, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(48, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(49, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(50, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(51, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(52, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(53, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(54, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(55, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(56, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(57, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(58, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0),
(59, 'partie1-5ea1ae82f3251', 'pompier', 0, 5, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
