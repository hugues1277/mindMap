-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Ven 07 Décembre 2018 à 16:24
-- Version du serveur :  10.1.26-MariaDB-0+deb9u1
-- Version de PHP :  7.0.30-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `mindMap`
--

-- --------------------------------------------------------

--
-- Structure de la table `mindMap`
--

CREATE TABLE `mindMap` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `json` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `mindMap`
--

INSERT INTO `mindMap` (`id`, `name`, `json`) VALUES
(771305311, 'MindMap', '{\"setting\":{\"bubbleColor\":\"rgb(0, 105, 181)\",\"lineColor\":\"rgb(0, 0, 0)\"},\"bubble\":[{\"id\":\"0\",\"x\":4000.0000610351562,\"y\":4000,\"title\":\"MindMap\",\"text\":\"This%20mindMap%20application%20allows%20to%20visually%20organize%20your%20information%20and%20shows%20the%20relationships%20between%20elements.\",\"url\":\"https%3A%2F%2Fen.wikipedia.org%2Fwiki%2FMind_map\",\"color\":\"rgb(245, 195, 59)\"},{\"id\":\"27561612\",\"x\":3755.0000610351562,\"y\":3968,\"title\":\"Create%20Bubbles\",\"text\":\"%3Cbr%3E\",\"url\":\"\",\"color\":\"rgb(85, 183, 116)\"},{\"id\":\"124231612\",\"x\":3896.0000610351562,\"y\":3885,\"title\":\"Update\",\"text\":\"%3Cbr%3E\",\"url\":\"\",\"color\":\"rgb(120, 160, 181)\"},{\"id\":\"209421612\",\"x\":3830.0000610351562,\"y\":4074,\"title\":\"Cange%20color\",\"text\":\"\",\"url\":\"\",\"color\":\"rgb(243, 83, 105)\"},{\"id\":\"370521612\",\"x\":4026.0000610351562,\"y\":3895,\"title\":\"Add%20Details\",\"text\":\"\",\"url\":\"\",\"color\":\"rgb(0, 105, 181)\"}],\"link\":[{\"begin\":\"27561612\",\"end\":\"0\",\"legend\":\"\",\"color\":\"rgb(0, 0, 0)\"},{\"begin\":\"124231612\",\"end\":\"0\",\"legend\":\"\",\"color\":\"rgb(0, 0, 0)\"},{\"begin\":\"492341612\",\"end\":\"0\",\"legend\":\"\",\"color\":\"rgb(0, 0, 0)\"},{\"begin\":\"209421612\",\"end\":\"0\",\"legend\":\"\",\"color\":\"rgb(0, 0, 0)\"},{\"begin\":\"370521612\",\"end\":\"0\",\"legend\":\"\",\"color\":\"rgb(0, 0, 0)\"},{\"begin\":\"492341612\",\"end\":\"370521612\",\"legend\":\"\",\"color\":\"rgb(0, 0, 0)\"}]}');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `mindMap`
--
ALTER TABLE `mindMap`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `mindMap`
--
ALTER TABLE `mindMap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=771305312;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
