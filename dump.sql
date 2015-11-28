-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 28. Nov 2015 um 22:09
-- Server Version: 5.5.43-0ubuntu0.14.04.1
-- PHP-Version: 5.6.10-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `c1_tool`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `st_backlinkCategories`
--

CREATE TABLE IF NOT EXISTS `st_backlinkCategories` (
  `backlinkCategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `backlinkCategory` varchar(255) NOT NULL,
  PRIMARY KEY (`backlinkCategoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `st_backlinkCategories`
--

INSERT INTO `st_backlinkCategories` (`backlinkCategoryID`, `backlinkCategory`) VALUES
(1, 'Innerhalb eines Textes'),
(2, 'Bildlink'),
(3, 'Eingebettet in iFrame'),
(4, 'Sidebar-Link'),
(5, 'Footer-Link'),
(6, 'Kommentarlink');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `st_backlinkRelations`
--

CREATE TABLE IF NOT EXISTS `st_backlinkRelations` (
  `backlinkRelationID` int(11) NOT NULL AUTO_INCREMENT,
  `backlinkRelation` varchar(255) NOT NULL,
  PRIMARY KEY (`backlinkRelationID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `st_backlinkRelations`
--

INSERT INTO `st_backlinkRelations` (`backlinkRelationID`, `backlinkRelation`) VALUES
(1, 'dofollow'),
(2, 'nofollow');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `st_backlinks`
--

CREATE TABLE IF NOT EXISTS `st_backlinks` (
  `backlinkID` int(11) NOT NULL AUTO_INCREMENT,
  `backlinkSource` text NOT NULL,
  `backlinkTarget` text NOT NULL,
  `backlinkCategory` int(11) NOT NULL,
  `backlinkSourceCategory` int(11) NOT NULL,
  `backlinkRelation` int(11) NOT NULL,
  `backlinkLinktext` text,
  `backlinkDateAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `backlinkProject` int(11) NOT NULL,
  `backlinkComment` text,
  PRIMARY KEY (`backlinkID`),
  KEY `backlinkCategory` (`backlinkCategory`),
  KEY `backlinkProject` (`backlinkProject`),
  KEY `backlinkSourceCategory` (`backlinkSourceCategory`),
  KEY `backlinkRelation` (`backlinkRelation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `st_backlinkSourceCategories`
--

CREATE TABLE IF NOT EXISTS `st_backlinkSourceCategories` (
  `backlinkSourceID` int(11) NOT NULL AUTO_INCREMENT,
  `backlinkSource` varchar(255) NOT NULL,
  PRIMARY KEY (`backlinkSourceID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `st_backlinkSourceCategories`
--

INSERT INTO `st_backlinkSourceCategories` (`backlinkSourceID`, `backlinkSource`) VALUES
(1, 'Forum'),
(2, 'Blog'),
(3, 'Wikipedia'),
(4, 'Verzeichnis'),
(5, 'Website'),
(6, 'Social Media');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `st_keywords`
--

CREATE TABLE IF NOT EXISTS `st_keywords` (
  `keywordID` int(11) NOT NULL AUTO_INCREMENT,
  `keywordCategoryID` int(11) DEFAULT NULL,
  `keywordName` varchar(255) NOT NULL,
  `parentProjectID` int(11) NOT NULL,
  `keywordUpdateHour` int(11) NOT NULL,
  `keywordAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `keywordUpdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`keywordID`),
  UNIQUE KEY `keywordName` (`keywordName`,`parentProjectID`),
  KEY `parentProjectID` (`parentProjectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `st_keywordsCategories`
--

CREATE TABLE IF NOT EXISTS `st_keywordsCategories` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(255) NOT NULL,
  PRIMARY KEY (`categoryID`),
  UNIQUE KEY `categoryName` (`categoryName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `st_keywordsCategories`
--

INSERT INTO `st_keywordsCategories` (`categoryID`, `categoryName`) VALUES
(2, 'Informationen'),
(3, 'Kategorie'),
(1, 'Produkte');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `st_projects`
--

CREATE TABLE IF NOT EXISTS `st_projects` (
  `projectID` int(11) NOT NULL AUTO_INCREMENT,
  `projectIsParent` int(11) NOT NULL DEFAULT '0',
  `parentProjectID` int(11) DEFAULT NULL,
  `projectURL` varchar(255) NOT NULL,
  `projectDefault` int(11) NOT NULL DEFAULT '0',
  `projectAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `projectID` (`projectID`),
  UNIQUE KEY `projectIsParent` (`projectIsParent`,`projectURL`),
  KEY `projectParentID` (`parentProjectID`),
  KEY `projectIsParent_2` (`projectIsParent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `st_rankings`
--

CREATE TABLE IF NOT EXISTS `st_rankings` (
  `rankingID` int(11) NOT NULL AUTO_INCREMENT,
  `keywordID` int(11) NOT NULL,
  `projectID` int(11) NOT NULL,
  `rankingPosition` int(11) DEFAULT NULL,
  `rankingURL` text NOT NULL,
  `rankingAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rankingAddedDay` date NOT NULL,
  PRIMARY KEY (`rankingID`),
  UNIQUE KEY `keywordID_2` (`keywordID`,`projectID`,`rankingAddedDay`),
  KEY `keywordID` (`keywordID`),
  KEY `projectID` (`projectID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `st_settings`
--

CREATE TABLE IF NOT EXISTS `st_settings` (
  `optionName` varchar(255) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `option` (`optionName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `st_settings`
--

INSERT INTO `st_settings` (`optionName`, `value`) VALUES
('cronjobHours', '0,1,2,3,4,5,6,7,8'),
('pauseStatic', '60'),
('pauseVariable', '30');

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `st_backlinks`
--
ALTER TABLE `st_backlinks`
  ADD CONSTRAINT `st_backlinks_ibfk_1` FOREIGN KEY (`backlinkCategory`) REFERENCES `st_backlinkCategories` (`backlinkCategoryID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `st_backlinks_ibfk_2` FOREIGN KEY (`backlinkProject`) REFERENCES `st_projects` (`projectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `st_backlinks_ibfk_3` FOREIGN KEY (`backlinkSourceCategory`) REFERENCES `st_backlinkSourceCategories` (`backlinkSourceID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `st_backlinks_ibfk_4` FOREIGN KEY (`backlinkRelation`) REFERENCES `st_backlinkRelations` (`backlinkRelationID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `st_keywords`
--
ALTER TABLE `st_keywords`
  ADD CONSTRAINT `st_keywords_ibfk_2` FOREIGN KEY (`parentProjectID`) REFERENCES `st_projects` (`projectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `st_projects`
--
ALTER TABLE `st_projects`
  ADD CONSTRAINT `st_projects_ibfk_1` FOREIGN KEY (`parentProjectID`) REFERENCES `st_projects` (`projectID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `st_rankings`
--
ALTER TABLE `st_rankings`
  ADD CONSTRAINT `st_rankings_ibfk_1` FOREIGN KEY (`keywordID`) REFERENCES `st_keywords` (`keywordID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `st_rankings_ibfk_2` FOREIGN KEY (`projectID`) REFERENCES `st_projects` (`projectID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
