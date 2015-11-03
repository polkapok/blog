-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 03 Novembre 2015 à 12:50
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `billets`
--

CREATE TABLE IF NOT EXISTS `billets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL COMMENT 'titre du billet',
  `auteur` varchar(99) NOT NULL COMMENT 'auteur du billet',
  `texte` text NOT NULL COMMENT 'texte du billet',
  `date_crea` datetime NOT NULL COMMENT 'date de creation du billet',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `billets`
--

INSERT INTO `billets` (`id`, `titre`, `auteur`, `texte`, `date_crea`) VALUES
(1, 'Premier billet', 'Pop2', 'Premier billet<br />\r\nPremier billet', '2015-10-24 17:23:00'),
(2, 'Deuxième', 'Pipo', 'Deuxième billet. Deuxième billet. \r\nDeuxième billet. ', '2015-10-26 16:20:00'),
(3, 'Troisième2', 'Pop2', 'Troisième billet 2...', '2015-10-26 18:40:00');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE IF NOT EXISTS `commentaires` (
  `id_comm` int(11) NOT NULL AUTO_INCREMENT,
  `id_ext_billet` int(11) NOT NULL COMMENT 'id du billet correspondant',
  `auteur_comm` varchar(99) NOT NULL COMMENT 'auteur du commentaire',
  `date_comm` datetime NOT NULL COMMENT 'date du commentaire',
  `commentaire` text NOT NULL COMMENT 'texte du comm',
  PRIMARY KEY (`id_comm`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `commentaires`
--

INSERT INTO `commentaires` (`id_comm`, `id_ext_billet`, `auteur_comm`, `date_comm`, `commentaire`) VALUES
(1, 3, 'pipo', '2015-10-31 13:48:57', 'mon comment sur le troisieme');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
