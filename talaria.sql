-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 10 mai 2025 à 06:26
-- Version du serveur : 5.7.40
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `talaria`
--

-- --------------------------------------------------------

--
-- Structure de la table `agent_tbl`
--

DROP TABLE IF EXISTS `agent_tbl`;
CREATE TABLE IF NOT EXISTS `agent_tbl` (
  `idAgent` int(11) NOT NULL AUTO_INCREMENT,
  `NomAgent` varchar(45) DEFAULT NULL,
  `PrenomAgent` varchar(45) DEFAULT NULL,
  `mailAgent` varchar(125) NOT NULL,
  `mdpAgent` char(44) DEFAULT NULL,
  `validiteMdp` date DEFAULT NULL,
  `refAgent` char(5) NOT NULL,
  `uuidAgent` varchar(45) DEFAULT NULL,
  `typeAgent` int(11) NOT NULL,
  `service` int(11) DEFAULT NULL,
  `dataAgent` json DEFAULT NULL,
  PRIMARY KEY (`idAgent`) USING BTREE,
  UNIQUE KEY `mailAgent_UNIQUE` (`mailAgent`),
  KEY `fk_agent_tbl_type_agent_idx` (`typeAgent`),
  KEY `fk_agent_tbl_service1_idx` (`service`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `agent_tbl`
--

INSERT INTO `agent_tbl` (`idAgent`, `NomAgent`, `PrenomAgent`, `mailAgent`, `mdpAgent`, `validiteMdp`, `refAgent`, `uuidAgent`, `typeAgent`, `service`, `dataAgent`) VALUES
(2, 'DESMARTIN', 'Paul', 'DesmartinPaul@mail.com', 'aK3oxZYrzYfxC265zQWUgVmzBpnbzpRZsTN7Ee7QuyI=', '2025-06-07', 'DESPA', '681b11d37d79e', 1, NULL, NULL),
(4, 'MANAGER1', 'FranÃ§ois', 'MANAGER1@mail.com', 'aK3oxZYrzYfxC265zQWUgVmzBpnbzpRZsTN7Ee7QuyI=', '2025-06-07', 'MAN01', '681b24f32b7dd', 2, 1, NULL),
(5, 'MANAGER2', 'Alain', 'MANAGER2@mail.com', 'aK3oxZYrzYfxC265zQWUgVmzBpnbzpRZsTN7Ee7QuyI=', '2025-06-08', 'MAN02', '681b3ed75255e', 2, 2, NULL),
(6, 'Agent1', 'Luci', 'AGENT1@mail.com', 'aK3oxZYrzYfxC265zQWUgVmzBpnbzpRZsTN7Ee7QuyI=', '2025-06-07', 'AG001', '681b6b9269755', 3, 1, NULL),
(7, 'AGENT2', 'Mireille', 'AGENT2@mail.com', NULL, '2025-06-08', 'AG002', '681cd5c595f7b', 3, 2, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `etat_ticket`
--

DROP TABLE IF EXISTS `etat_ticket`;
CREATE TABLE IF NOT EXISTS `etat_ticket` (
  `idEtatTicket` int(11) NOT NULL AUTO_INCREMENT,
  `libEtatTicket` varchar(45) NOT NULL,
  PRIMARY KEY (`idEtatTicket`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `etat_ticket`
--

INSERT INTO `etat_ticket` (`idEtatTicket`, `libEtatTicket`) VALUES
(1, 'default'),
(2, 'validé'),
(3, 'transmi'),
(4, 'traité'),
(5, 'echoué');

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `idService` int(11) NOT NULL AUTO_INCREMENT,
  `libService` varchar(45) DEFAULT NULL,
  `create_enable` tinyint(1) NOT NULL DEFAULT '0',
  `update_enable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idService`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`idService`, `libService`, `create_enable`, `update_enable`) VALUES
(1, 'Relation Client', 1, 0),
(2, 'Remboursement', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `ticket_has_etat`
--

DROP TABLE IF EXISTS `ticket_has_etat`;
CREATE TABLE IF NOT EXISTS `ticket_has_etat` (
  `Etat_Ticket_idEtatTicket` int(11) NOT NULL,
  `ticket_tbl_idTicket` int(11) NOT NULL,
  `dateEtat` datetime NOT NULL,
  `commentEtat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Etat_Ticket_idEtatTicket`,`ticket_tbl_idTicket`,`dateEtat`),
  KEY `fk_Etat_Ticket_has_ticket_tbl_ticket_tbl1_idx` (`ticket_tbl_idTicket`),
  KEY `fk_Etat_Ticket_has_ticket_tbl_Etat_Ticket1_idx` (`Etat_Ticket_idEtatTicket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ticket_has_etat`
--

INSERT INTO `ticket_has_etat` (`Etat_Ticket_idEtatTicket`, `ticket_tbl_idTicket`, `dateEtat`, `commentEtat`) VALUES
(1, 2, '2025-05-08 00:00:00', NULL),
(1, 3, '2025-05-08 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ticket_tbl`
--

DROP TABLE IF EXISTS `ticket_tbl`;
CREATE TABLE IF NOT EXISTS `ticket_tbl` (
  `idTicket` int(11) NOT NULL AUTO_INCREMENT,
  `contentTicket` text NOT NULL,
  `dateTicket` date NOT NULL,
  `auteur` int(11) NOT NULL,
  `service` int(11) DEFAULT NULL,
  `objetTicket` varchar(25) NOT NULL,
  `prioriteTicket` enum('basse','normale','haute') NOT NULL DEFAULT 'normale',
  `dataTicket` json DEFAULT NULL,
  `typeTicket` int(11) DEFAULT NULL,
  `agentResponsable` int(11) DEFAULT NULL,
  PRIMARY KEY (`idTicket`) USING BTREE,
  KEY `fk_ticket_tbl_agent_tbl1_idx` (`auteur`),
  KEY `fk_ticket_tbl_service1_idx` (`service`),
  KEY `fk_ticket_tnl_type_ticket` (`typeTicket`),
  KEY `fk_ticket_tbl_agent_tbl2_idx` (`agentResponsable`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ticket_tbl`
--

INSERT INTO `ticket_tbl` (`idTicket`, `contentTicket`, `dateTicket`, `auteur`, `service`, `objetTicket`, `prioriteTicket`, `dataTicket`, `typeTicket`, `agentResponsable`) VALUES
(2, 'L\'adhÃ©rent attend toujours son remboursement', '2025-05-08', 6, 2, 'demande de rbt', 'normale', '{\"adh\": \"3068923\"}', 2, NULL),
(3, 'Pouvez-vous effectuer le rbt de l\'adh du 26-01-2025', '2025-05-08', 6, 2, 'reclamation rbt', 'normale', '{\"adh\": \"306\"}', 3, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `type_agent`
--

DROP TABLE IF EXISTS `type_agent`;
CREATE TABLE IF NOT EXISTS `type_agent` (
  `idTypeAgent` int(11) NOT NULL AUTO_INCREMENT,
  `libTypeAgent` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idTypeAgent`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `type_agent`
--

INSERT INTO `type_agent` (`idTypeAgent`, `libTypeAgent`) VALUES
(1, 'ADMIN'),
(2, 'MANAGER'),
(3, 'AGENT');

-- --------------------------------------------------------

--
-- Structure de la table `type_ticket`
--

DROP TABLE IF EXISTS `type_ticket`;
CREATE TABLE IF NOT EXISTS `type_ticket` (
  `idTypeTicket` int(11) NOT NULL AUTO_INCREMENT,
  `libTypeTicket` varchar(45) NOT NULL,
  PRIMARY KEY (`idTypeTicket`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `type_ticket`
--

INSERT INTO `type_ticket` (`idTypeTicket`, `libTypeTicket`) VALUES
(1, 'Information'),
(2, 'RÃ©clamation'),
(3, 'Intervention');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agent_tbl`
--
ALTER TABLE `agent_tbl`
  ADD CONSTRAINT `fk_agent_tbl_service1` FOREIGN KEY (`service`) REFERENCES `service` (`idService`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_tbl_type_agent` FOREIGN KEY (`typeAgent`) REFERENCES `type_agent` (`idTypeAgent`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ticket_has_etat`
--
ALTER TABLE `ticket_has_etat`
  ADD CONSTRAINT `fk_Etat_Ticket_has_ticket_tbl_Etat_Ticket1` FOREIGN KEY (`Etat_Ticket_idEtatTicket`) REFERENCES `etat_ticket` (`idEtatTicket`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Etat_Ticket_has_ticket_tbl_ticket_tbl1` FOREIGN KEY (`ticket_tbl_idTicket`) REFERENCES `ticket_tbl` (`idTicket`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ticket_tbl`
--
ALTER TABLE `ticket_tbl`
  ADD CONSTRAINT `fk_ticket_tbl_agent_tbl1` FOREIGN KEY (`auteur`) REFERENCES `agent_tbl` (`idAgent`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ticket_tbl_agent_tbl2` FOREIGN KEY (`agentResponsable`) REFERENCES `agent_tbl` (`idAgent`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ticket_tbl_service1` FOREIGN KEY (`service`) REFERENCES `service` (`idService`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ticket_tnl_type_ticket` FOREIGN KEY (`typeTicket`) REFERENCES `type_ticket` (`idTypeTicket`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
