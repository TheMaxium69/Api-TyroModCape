-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 16 juil. 2024 à 16:27
-- Version du serveur : 8.0.31
-- Version de PHP : 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tyrocapemod`
--

-- --------------------------------------------------------

--
-- Structure de la table `capes`
--

DROP TABLE IF EXISTS `capes`;
CREATE TABLE IF NOT EXISTS `capes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `isAnimated` tinyint(1) NOT NULL,
  `isShop` tinyint(1) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `capes`
--

INSERT INTO `capes` (`id`, `name`, `url`, `isAnimated`, `isShop`, `createdAt`) VALUES
(1, 'Minecraft Dungeons Hero', 'dungeonsHerocape.png', 0, 1, '2024-07-16 11:44:38'),
(2, 'Minecon 2011', 'minecon2011.png', 0, 1, '2024-07-16 11:44:53'),
(3, 'Owner Cape', 'ownerCape.png', 0, 0, '2024-07-16 11:45:11'),
(4, 'Minecraft Market Place', 'MinecraftMarketPlace.png', 0, 1, '2024-07-16 15:26:35');

-- --------------------------------------------------------

--
-- Structure de la table `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE IF NOT EXISTS `players` (
  `id` int NOT NULL AUTO_INCREMENT,
  `playerName` varchar(255) NOT NULL,
  `idCapes` int NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idCapes` (`idCapes`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `players`
--

INSERT INTO `players` (`id`, `playerName`, `idCapes`, `dateAdded`) VALUES
(4, 'Luigi_Guyot', 2, '2024-07-16 14:15:39'),
(5, 'TheMaximeSan', 3, '2024-07-16 14:21:16'),
(6, 'TheMaxium69', 1, '2024-07-16 14:24:33'),
(8, 'TheMaximeSan', 2, '2024-07-16 15:10:14');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `players_ibfk_1` FOREIGN KEY (`idCapes`) REFERENCES `capes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
