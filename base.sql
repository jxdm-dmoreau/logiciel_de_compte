-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Samedi 21 Juillet 2007 à 11:58
-- Version du serveur: 4.1.9
-- Version de PHP: 4.3.10
-- 
-- Base de données: `compte`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `categories`
-- 

CREATE TABLE `categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- 
-- Contenu de la table `categories`
-- 

INSERT INTO `categories` VALUES (1, 'Loyer');
INSERT INTO `categories` VALUES (2, 'Salaire');
INSERT INTO `categories` VALUES (4, 'Voiture');
INSERT INTO `categories` VALUES (5, 'Telephone');
INSERT INTO `categories` VALUES (6, 'linda');

-- --------------------------------------------------------

-- 
-- Structure de la table `soldes`
-- 

CREATE TABLE `soldes` (
  `id` int(50) NOT NULL default '0',
  `solde_init` double NOT NULL default '0',
  `solde` double NOT NULL default '0',
  `solde_p` double NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Contenu de la table `soldes`
-- 

INSERT INTO `soldes` VALUES (0, 1000, 1096, -3692);

-- --------------------------------------------------------

-- 
-- Structure de la table `transactions`
-- 

CREATE TABLE `transactions` (
  `id` int(10) NOT NULL auto_increment,
  `date` int(20) NOT NULL default '0',
  `id_cat` int(10) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `description` text NOT NULL,
  `debit` float NOT NULL default '0',
  `credit` float NOT NULL default '0',
  `pointage` tinyint(1) NOT NULL default '0',
  `last_update` int(20) NOT NULL default '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

-- 
-- Contenu de la table `transactions`
-- 

INSERT INTO `transactions` VALUES (50, 1187215200, 5, 'virement', 'SMSLinda', 150, 0, 0, 1184511821);
INSERT INTO `transactions` VALUES (38, 1180130400, 1, 'prelevement', 'test', 1, 1, 1, 1180258724);
INSERT INTO `transactions` VALUES (49, 1183759200, 6, 'virement', '', 255, 0, 0, 1184425842);
INSERT INTO `transactions` VALUES (44, 1184364000, 2, 'virement', '', 0, 1800, 1, 1184575224);
INSERT INTO `transactions` VALUES (45, 1184709600, 4, 'virement', '', 100, 0, 0, 1184421676);
INSERT INTO `transactions` VALUES (47, 1184623200, 5, 'virement', '', 10, 0, 0, 1184419286);
INSERT INTO `transactions` VALUES (48, 1185919200, 1, 'virement', '', 550, 0, 0, 1184423081);

-- --------------------------------------------------------

-- 
-- Structure de la table `types`
-- 

CREATE TABLE `types` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `index` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `types`
-- 

INSERT INTO `types` VALUES (1, 'Virement', 'virement');
INSERT INTO `types` VALUES (2, 'Pr?l?vement', 'prelevement');

-- --------------------------------------------------------

-- 
-- Structure de la table `utilisateurs`
-- 

CREATE TABLE `utilisateurs` (
  `id` int(10) NOT NULL auto_increment,
  `login` varchar(20) NOT NULL default '',
  `pass` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `utilisateurs`
-- 

INSERT INTO `utilisateurs` VALUES (1, 'David', '02d4502b3a62f04e4355f7aacf1bbd1d');
        