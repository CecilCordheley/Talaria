-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


-- -----------------------------------------------------
-- Schema talaria
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `talaria` DEFAULT CHARACTER SET utf8 ;
USE `talaria` ;

-- -----------------------------------------------------
-- Table `talaria`.`service`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`service` (
  `idService` INT(11) NOT NULL AUTO_INCREMENT,
  `libService` VARCHAR(45) NULL DEFAULT NULL,
  `desc_service` VARCHAR(255) NULL DEFAULT NULL,
  `create_enable` TINYINT(1) NOT NULL DEFAULT '0',
  `update_enable` TINYINT(1) NOT NULL DEFAULT '0',
  `isActif` TINYINT(1) NULL DEFAULT '1',
  `archivable` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idService`))
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `talaria`.`type_agent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`type_agent` (
  `idTypeAgent` INT(11) NOT NULL AUTO_INCREMENT,
  `libTypeAgent` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`idTypeAgent`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `talaria`.`agent_tbl`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`agent_tbl` (
  `idAgent` INT(11) NOT NULL AUTO_INCREMENT,
  `NomAgent` VARCHAR(45) NULL DEFAULT NULL,
  `PrenomAgent` VARCHAR(45) NULL DEFAULT NULL,
  `mailAgent` VARCHAR(125) NOT NULL,
  `mdpAgent` CHAR(44) NULL DEFAULT NULL,
  `validiteMdp` DATE NULL DEFAULT NULL,
  `refAgent` CHAR(5) NOT NULL,
  `uuidAgent` VARCHAR(45) NULL DEFAULT NULL,
  `typeAgent` INT(11) NOT NULL,
  `service` INT(11) NULL DEFAULT NULL,
  `dataAgent` JSON NULL DEFAULT NULL,
  `blockAgent` DATE NULL DEFAULT NULL,
  PRIMARY KEY USING BTREE (`idAgent`),
  UNIQUE INDEX `mailAgent_UNIQUE` (`mailAgent` ASC) VISIBLE,
  INDEX `fk_agent_tbl_type_agent_idx` (`typeAgent` ASC) VISIBLE,
  INDEX `fk_agent_tbl_service1_idx` (`service` ASC) VISIBLE,
  CONSTRAINT `fk_agent_tbl_service1`
    FOREIGN KEY (`service`)
    REFERENCES `talaria`.`service` (`idService`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_agent_tbl_type_agent`
    FOREIGN KEY (`typeAgent`)
    REFERENCES `talaria`.`type_agent` (`idTypeAgent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `talaria`.`agent_archive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`agent_archive` (
  `idArchive` INT(11) NOT NULL AUTO_INCREMENT,
  `idAgentOriginal` INT(11) NOT NULL,
  `NomAgent` VARCHAR(45) NULL DEFAULT NULL,
  `PrenomAgent` VARCHAR(45) NULL DEFAULT NULL,
  `mailAgent` VARCHAR(125) NULL DEFAULT NULL,
  `refAgent` CHAR(5) NULL DEFAULT NULL,
  `typeAgent` INT(11) NULL DEFAULT NULL,
  `service` INT(11) NULL DEFAULT NULL,
  `archivedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dataAgent` JSON NULL DEFAULT NULL,
  PRIMARY KEY (`idArchive`),
  INDEX `fk_agent_archive_agent_tbl1_idx` (`idAgentOriginal` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `talaria`.`etat_ticket`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`etat_ticket` (
  `idEtatTicket` INT(11) NOT NULL AUTO_INCREMENT,
  `libEtatTicket` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idEtatTicket`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `talaria`.`licence_exception`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`licence_exception` (
  `idLicence` INT(11) NOT NULL AUTO_INCREMENT,
  `uuidLicence` CHAR(36) NOT NULL,
  `agent` INT(11) NOT NULL,
  `dateAttribution` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estActive` TINYINT(1) NOT NULL DEFAULT '1',
  `isAutoAttribution` TINYINT(1) NULL DEFAULT '0',
  PRIMARY KEY (`idLicence`),
  UNIQUE INDEX `uuidLicence` (`uuidLicence` ASC),
  INDEX `agent` (`agent` ASC) VISIBLE)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `talaria`.`journal_licence`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`journal_licence` (
  `idJournal` INT(11) NOT NULL AUTO_INCREMENT,
  `licence` INT(11) NOT NULL,
  `action` VARCHAR(255) NOT NULL,
  `dateAction` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cible` INT(11) NULL DEFAULT NULL,
  `type_cible` ENUM('ticket', 'service', 'agent') NOT NULL DEFAULT 'ticket',
  `commentaire` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`idJournal`),
  INDEX `licence` (`licence` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `talaria`.`quota_exception_admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`quota_exception_admin` (
  `idAgent` INT(11) NOT NULL,
  `quota_max` INT(11) NOT NULL DEFAULT '3',
  `quota_utilise` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idAgent`),
  INDEX `idAgent` (`idAgent` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `talaria`.`service_archive`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`service_archive` (
  `idArchive` INT(11) NOT NULL AUTO_INCREMENT,
  `idServiceOriginal` INT(11) NOT NULL,
  `libService` VARCHAR(45) NULL DEFAULT NULL,
  `archivedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dataService` JSON NULL DEFAULT NULL,
  PRIMARY KEY (`idArchive`),
  INDEX `fk_service_archive_service1_idx` (`idServiceOriginal` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `talaria`.`type_ticket`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`type_ticket` (
  `idTypeTicket` INT(11) NOT NULL AUTO_INCREMENT,
  `libTypeTicket` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idTypeTicket`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `talaria`.`ticket_tbl`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`ticket_tbl` (
  `idTicket` INT(11) NOT NULL AUTO_INCREMENT,
  `contentTicket` TEXT NOT NULL,
  `dateTicket` DATE NOT NULL,
  `auteur` INT(11) NOT NULL,
  `service` INT(11) NULL DEFAULT NULL,
  `objetTicket` VARCHAR(25) NOT NULL,
  `prioriteTicket` ENUM('basse', 'normale', 'haute') NOT NULL DEFAULT 'normale',
  `dataTicket` JSON NULL DEFAULT NULL,
  `typeTicket` INT(11) NULL DEFAULT NULL,
  `agentResponsable` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY USING BTREE (`idTicket`),
  INDEX `fk_ticket_tbl_agent_tbl1_idx` (`auteur` ASC),
  INDEX `fk_ticket_tbl_service1_idx` (`service` ASC),
  INDEX `fk_ticket_tnl_type_ticket` (`typeTicket` ASC),
  INDEX `fk_ticket_tbl_agent_tbl2_idx` (`agentResponsable` ASC),
  CONSTRAINT `fk_ticket_tbl_agent_tbl1`
    FOREIGN KEY (`auteur`)
    REFERENCES `talaria`.`agent_tbl` (`idAgent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_tbl_agent_tbl2`
    FOREIGN KEY (`agentResponsable`)
    REFERENCES `talaria`.`agent_tbl` (`idAgent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_tbl_service1`
    FOREIGN KEY (`service`)
    REFERENCES `talaria`.`service` (`idService`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_tnl_type_ticket`
    FOREIGN KEY (`typeTicket`)
    REFERENCES `talaria`.`type_ticket` (`idTypeTicket`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `talaria`.`ticket_has_etat`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `talaria`.`ticket_has_etat` (
  `Etat_Ticket_idEtatTicket` INT(11) NOT NULL,
  `ticket_tbl_idTicket` INT(11) NOT NULL,
  `dateEtat` DATETIME NOT NULL,
  `commentEtat` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`Etat_Ticket_idEtatTicket`, `ticket_tbl_idTicket`, `dateEtat`),
  INDEX `fk_Etat_Ticket_has_ticket_tbl_ticket_tbl1_idx` (`ticket_tbl_idTicket` ASC),
  INDEX `fk_Etat_Ticket_has_ticket_tbl_Etat_Ticket1_idx` (`Etat_Ticket_idEtatTicket` ASC),
  CONSTRAINT `fk_Etat_Ticket_has_ticket_tbl_Etat_Ticket1`
    FOREIGN KEY (`Etat_Ticket_idEtatTicket`)
    REFERENCES `talaria`.`etat_ticket` (`idEtatTicket`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Etat_Ticket_has_ticket_tbl_ticket_tbl1`
    FOREIGN KEY (`ticket_tbl_idTicket`)
    REFERENCES `talaria`.`ticket_tbl` (`idTicket`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
