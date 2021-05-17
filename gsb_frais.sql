-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 17, 2021 at 05:24 AM
-- Server version: 5.7.11
-- PHP Version: 7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gsb_frais`
--

-- --------------------------------------------------------

--
-- Table structure for table `etat`
--

CREATE TABLE `etat` (
  `id` char(2) NOT NULL,
  `libelle` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `etat`
--

INSERT INTO `etat` (`id`, `libelle`) VALUES
('CL', 'Saisie clôturée'),
('CR', 'Fiche créée, saisie en cours'),
('RB', 'Remboursée'),
('VA', 'Validée et mise en paiement');

-- --------------------------------------------------------

--
-- Table structure for table `fichefrais`
--

CREATE TABLE `fichefrais` (
  `idVisiteur` char(4) NOT NULL,
  `mois` char(6) NOT NULL,
  `nbJustificatifs` int(11) DEFAULT NULL,
  `montantValide` decimal(10,2) DEFAULT NULL,
  `dateModif` date DEFAULT NULL,
  `idEtat` char(2) DEFAULT 'CR'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fichefrais`
--

INSERT INTO `fichefrais` (`idVisiteur`, `mois`, `nbJustificatifs`, `montantValide`, `dateModif`, `idEtat`) VALUES
('5r2', '2', 2, '150.00', '2020-02-09', 'RB'),
('a17', '4', 12, NULL, '2021-04-26', 'CR'),
('a55', '1', 5, NULL, '2021-05-14', 'CR'),
('chn', '1', 5, '250.00', '2077-01-12', 'CL'),
('usb', '1', 4, '250.00', '2021-05-14', 'CR'),
('usb', '2', 4, '1340.00', '2021-05-15', 'VA'),
('usb', '5', 1, '1340.00', '2021-05-14', 'RB');

-- --------------------------------------------------------

--
-- Table structure for table `fraisforfait`
--

CREATE TABLE `fraisforfait` (
  `id` char(3) CHARACTER SET latin1 NOT NULL,
  `libelle` char(20) CHARACTER SET latin1 DEFAULT NULL,
  `montant` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `fraisforfait`
--

INSERT INTO `fraisforfait` (`id`, `libelle`, `montant`) VALUES
('ETP', 'Forfait Etape', '110.00'),
('KM', 'Frais Kilométrique', '0.62'),
('NUI', 'Nuitée Hôtel', '80.00'),
('REP', 'Repas Restaurant', '25.00');

-- --------------------------------------------------------

--
-- Table structure for table `lignefraisforfait`
--

CREATE TABLE `lignefraisforfait` (
  `idVisiteur` char(4) NOT NULL,
  `mois` char(6) NOT NULL,
  `idFraisForfait` char(3) NOT NULL,
  `quantite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lignefraisforfait`
--

INSERT INTO `lignefraisforfait` (`idVisiteur`, `mois`, `idFraisForfait`, `quantite`) VALUES
('a55', '1', 'ETP', 5),
('a55', '1', 'KM', 35),
('a55', '1', 'NUI', 8),
('a55', '1', 'REP', 1),
('chn', '1', 'ETP', 5),
('chn', '1', 'KM', 35),
('chn', '1', 'NUI', 8),
('chn', '1', 'REP', 12),
('usb', '1', 'ETP', 5),
('usb', '1', 'KM', 0),
('usb', '1', 'NUI', 7),
('usb', '1', 'REP', 12),
('usb', '2', 'ETP', 5),
('usb', '2', 'KM', 6),
('usb', '2', 'NUI', 7),
('usb', '2', 'REP', 8),
('usb', '5', 'ETP', 10),
('usb', '5', 'KM', 0),
('usb', '5', 'NUI', 3),
('usb', '5', 'REP', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lignefraishorsforfait`
--

CREATE TABLE `lignefraishorsforfait` (
  `id` int(11) NOT NULL,
  `idVisiteur` char(4) NOT NULL,
  `mois` char(6) NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lignefraishorsforfait`
--

INSERT INTO `lignefraishorsforfait` (`id`, `idVisiteur`, `mois`, `libelle`, `date`, `montant`) VALUES
(7, 'chn', '1', 'Cadeau', '2077-01-25', '350.00'),
(8, 'usb', '1', 'transport', '2021-04-04', '25.00'),
(15, 'usb', '5', 'donation', '2021-05-14', '120.00');

-- --------------------------------------------------------

--
-- Table structure for table `pwdreset`
--

CREATE TABLE `pwdreset` (
  `pwdResetid` int(11) NOT NULL,
  `pwdResetEmail` text NOT NULL,
  `pwdResetSelector` text NOT NULL,
  `pwdResetToken` longtext NOT NULL,
  `pwdResetExpires` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pwdreset`
--

INSERT INTO `pwdreset` (`pwdResetid`, `pwdResetEmail`, `pwdResetSelector`, `pwdResetToken`, `pwdResetExpires`) VALUES
(2, '', '7a9095dcca802f80', '$2y$10$c6okFc0YrTlP1cUmqVgRG.6Dls56ewfUAqx3.0kYpHVw28zcOrabG', '1621029309'),
(9, 'khalqallahwalid@gmail.com', '9080017f2e1e34a4', '$2y$10$MNY5mEixEfse/vNx9MPHJOxHYNG7lBSoc/zGEgOBdkK4CIjlaBHNa', '1621102892');

-- --------------------------------------------------------

--
-- Table structure for table `visiteur`
--

CREATE TABLE `visiteur` (
  `id` char(4) NOT NULL,
  `nom` char(30) DEFAULT NULL,
  `prenom` char(30) DEFAULT NULL,
  `login` char(20) DEFAULT NULL,
  `email` text NOT NULL,
  `mdp` char(200) DEFAULT NULL,
  `adresse` char(30) DEFAULT NULL,
  `cp` char(5) DEFAULT NULL,
  `ville` char(30) DEFAULT NULL,
  `dateEmbauche` date DEFAULT NULL,
  `portrait` varchar(250) NOT NULL,
  `comptable` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `visiteur`
--

INSERT INTO `visiteur` (`id`, `nom`, `prenom`, `login`, `email`, `mdp`, `adresse`, `cp`, `ville`, `dateEmbauche`, `portrait`, `comptable`) VALUES
('2lc', 'aiden', 'pearce', 'aiden10', 'aiden_pearce@WD.com', '$2y$10$bdARILS9T7H8CWCGsxIaBuhVqDAqfODyWB1zMJTlOX6J.9b1WmbpK', '41 mad street', '48584', 'chicago', '2020-11-11', '', 0),
('5r2', 'admin', 'adm', 'admin', 'admin@gmail.com', '$2y$10$zjY9omX2hb9ue9X8myvA9ePubBeuvu594ausD3uiAK1a/Eu3J3BmK', '15 rue blabla', '75011', 'paris', '2020-12-05', '', 0),
('68d', 'khalqallah', 'Walid', 'whale_aid', 'khalqallahwalid@gmail.com', '$2y$10$HjPPzywt7H281JXQFXqi.uieO0asDdYaYyIhUw5uA5yVMylOydlw.', '16 rue du docteur roux', '95100', 'Argenteuil', '2021-05-14', 'whale_aid.png', 0),
('9vy', 'Qui eu perspiciatis', 'Veritatis ullamco nu', 'Enim maxime sed cons', 'vosaca@mailinator.com', '$2y$10$PScgeHNWW6R7WtUgGtqplutgDE8Et6j0AlSy.cyUjEcOXAM5YjUJe', 'Voluptas ut harum vo', '94729', 'Aut consequat Molli', '2021-03-24', 'Enim maxime sed cons.jpg', 0),
('a131', 'Villechalane', 'Louis', 'lvillachane', '', 'jux7g', '8 rue des Charmes', '46000', 'Cahors', '2005-12-21', '', 0),
('a17', 'Andre', 'David', 'dandre', '', 'oppg5', '1 rue Petit', '46200', 'Lalbenque', '1998-11-23', '', 0),
('a55', 'Bedos', 'Christian', 'cbedos', '', 'gmhxd', '1 rue Peranud', '46250', 'Montcuq', '1995-01-12', '', 0),
('a93', 'Tusseau', 'Louis', 'ltusseau', '', 'ktp3s', '22 rue des Ternes', '46123', 'Gramat', '2000-05-01', '', 0),
('b13', 'Bentot', 'Pascal', 'pbentot', '', 'doyw1', '11 allée des Cerises', '46512', 'Bessines', '1992-07-09', '', 0),
('b16', 'Bioret', 'Luc', 'lbioret', '', 'hrjfs', '1 Avenue gambetta', '46000', 'Cahors', '1998-05-11', '', 0),
('b19', 'Bunisset', 'Francis', 'fbunisset', '', '4vbnd', '10 rue des Perles', '93100', 'Montreuil', '1987-10-21', '', 0),
('b25', 'Bunisset', 'Denise', 'dbunisset', '', 's1y1r', '23 rue Manin', '75019', 'paris', '2010-12-05', '', 0),
('b28', 'Cacheux', 'Bernard', 'bcacheux', '', 'uf7r3', '114 rue Blanche', '75017', 'Paris', '2009-11-12', '', 0),
('b34', 'Cadic', 'Eric', 'ecadic', '', '6u8dc', '123 avenue de la République', '75011', 'Paris', '2008-09-23', '', 0),
('b4', 'Charoze', 'Catherine', 'ccharoze', '', 'u817o', '100 rue Petit', '75019', 'Paris', '2005-11-12', '', 0),
('b50', 'Clepkens', 'Christophe', 'cclepkens', '', 'bw1us', '12 allée des Anges', '93230', 'Romainville', '2003-08-11', '', 0),
('b59', 'Cottin', 'Vincenne', 'vcottin', '', '2hoh9', '36 rue Des Roches', '93100', 'Monteuil', '2001-11-18', '', 0),
('c14', 'Daburon', 'François', 'fdaburon', '', '7oqpv', '13 rue de Chanzy', '94000', 'Créteil', '2002-02-11', '', 0),
('c3', 'De', 'Philippe', 'pde', '', 'gk9kx', '13 rue Barthes', '94000', 'Créteil', '2010-12-14', '', 0),
('c54', 'Debelle', 'Michel', 'mdebelle', '', 'od5rt', '181 avenue Barbusse', '93210', 'Rosny', '2006-11-23', '', 0),
('chn', 'user', 'userr', 'user123', 'user@user.com', '$2y$10$idBMdD42t3Eh8ZjrkyCQXOF30glyMRNsntZIhriqO5WfCXPT37vwy', '147useradresse', '14789', 'usercity', '2020-12-16', '', 0),
('d13', 'Debelle', 'Jeanne', 'jdebelle', '', 'nvwqq', '134 allée des Joncs', '44000', 'Nantes', '2000-05-11', '', 0),
('d51', 'Debroise', 'Michel', 'mdebroise', '', 'sghkb', '2 Bld Jourdain', '44000', 'Nantes', '2001-04-17', '', 0),
('e22', 'Desmarquest', 'Nathalie', 'ndesmarquest', '', 'f1fob', '14 Place d Arc', '45000', 'Orléans', '2005-11-12', '', 0),
('e24', 'Desnost', 'Pierre', 'pdesnost', '', '4k2o5', '16 avenue des Cèdres', '23200', 'Guéret', '2001-02-05', '', 0),
('e39', 'Dudouit', 'Frédéric', 'fdudouit', '', '44im8', '18 rue de l église', '23120', 'GrandBourg', '2000-08-01', '', 0),
('e49', 'Duncombe', 'Claude', 'cduncombe', '', 'qf77j', '19 rue de la tour', '23100', 'La souteraine', '1987-10-10', '', 0),
('e5', 'Enault-Pascreau', 'Céline', 'cenault', '', 'y2qdu', '25 place de la gare', '23200', 'Gueret', '1995-09-01', '', 0),
('e52', 'Eynde', 'Valérie', 'veynde', '', 'i7sn3', '3 Grand Place', '13015', 'Marseille', '1999-11-01', '', 0),
('f21', 'Finck', 'Jacques', 'jfinck', '', 'mpb3t', '10 avenue du Prado', '13002', 'Marseille', '2001-11-10', '', 0),
('f39', 'Frémont', 'Fernande', 'ffremont', '', 'xs5tq', '4 route de la mer', '13012', 'Allauh', '1998-10-01', '', 0),
('f4', 'Gest', 'Alain', 'agest', '', 'dywvt', '30 avenue de la mer', '13025', 'Berre', '1985-11-01', '', 0),
('fso', 'Jones', 'Alex', 'jones_alex', 'jones.alex@gmail.com', '$2y$10$UEs2M/KEVIzyY5RlWWRlN.igOtVXvnfJKClUwc0DMhcry8n.4hH8K', '14 adresse test', '75420', 'Paris', '2021-04-26', 'jones_alex.jpg', 1),
('ibr', 'aiden', 'pearce', 'aiden10', 'aiden_pearce@WD.com', '$2y$10$RbT53OU39EdO0o3Em1F7P.xtQPX7X/9Wv3j6HYWcsh3kkZ1G.ne.O', '41 mad street', '48584', 'chicago', '2020-11-11', '', 0),
('iok', 'blabla', 'test', 'jeffreydah', 'admin@gmail.com', '$2y$10$BtR9qEwqhslRk4OBbjs9SuT7LWKdXBIoj0Y9hEd5sVVion.6UiFmW', '37 rue des mathurins', '75008', 'Paris', '2021-03-23', 'cd3035061d7fa18079df74d058b99914d5b2aba67ca04b1f52140ece155f7ed6.jpg', 0),
('qlw', 'bla', 'test', 'testblabla', 'blabla@bla.com', '$2y$10$TfTYI0RpTikANB/s0qGfS.Bkk9wdP4ECMsUn4BX5Koh7Xmjs2fntm', '14 qzlij', '95844', 'new york', '2020-11-11', '', 0),
('r0v', 'test', 'test1', 'test2', 'test@bla.com', '$2y$10$86ucoK/4ItOn6nVMMKt2feNOV2Xw6mL9lFX8IM57llpighOCNtEZa', '15 rue blabla', '48584', 'paris', '2020-11-12', '', 0),
('usb', 'Doe', 'John', 'john_doe', 'joe.doe@gmail.com', '$2y$10$LT/91mZGt1U4QaqOpk5Ve.0pyAL90t5g380q4ewEoAU7SZ7k7ZZBC', '15 rue blabla', '87096', 'paris', '2021-03-25', 'john_doe.jpg', 0),
('uwt', 'test', 'test1', 'test2', '', 'helloworld', '15 rue blabla', '78451', 'paris', '2020-11-11', '', 0),
('yoi', 'hello', 'world', 'helklo', 'hello.world@gmail.com', '$2y$10$XVT.o7PHZ9fecNDw2.U8VuohwjmYhE.KC9fW000roFYT3kkfg9m1G', 'hello 123', '95412', 'paris', '2020-12-16', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `etat`
--
ALTER TABLE `etat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fichefrais`
--
ALTER TABLE `fichefrais`
  ADD PRIMARY KEY (`idVisiteur`,`mois`),
  ADD KEY `idEtat` (`idEtat`);

--
-- Indexes for table `fraisforfait`
--
ALTER TABLE `fraisforfait`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lignefraisforfait`
--
ALTER TABLE `lignefraisforfait`
  ADD PRIMARY KEY (`idVisiteur`,`mois`,`idFraisForfait`),
  ADD KEY `idFraisForfait` (`idFraisForfait`);

--
-- Indexes for table `lignefraishorsforfait`
--
ALTER TABLE `lignefraishorsforfait`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idVisiteur` (`idVisiteur`,`mois`);

--
-- Indexes for table `pwdreset`
--
ALTER TABLE `pwdreset`
  ADD PRIMARY KEY (`pwdResetid`);

--
-- Indexes for table `visiteur`
--
ALTER TABLE `visiteur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lignefraishorsforfait`
--
ALTER TABLE `lignefraishorsforfait`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `pwdreset`
--
ALTER TABLE `pwdreset`
  MODIFY `pwdResetid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `fichefrais`
--
ALTER TABLE `fichefrais`
  ADD CONSTRAINT `fichefrais_ibfk_1` FOREIGN KEY (`idEtat`) REFERENCES `etat` (`id`),
  ADD CONSTRAINT `fichefrais_ibfk_2` FOREIGN KEY (`idVisiteur`) REFERENCES `visiteur` (`id`);

--
-- Constraints for table `lignefraisforfait`
--
ALTER TABLE `lignefraisforfait`
  ADD CONSTRAINT `lignefraisforfait_ibfk_1` FOREIGN KEY (`idVisiteur`,`mois`) REFERENCES `fichefrais` (`idVisiteur`, `mois`),
  ADD CONSTRAINT `lignefraisforfait_ibfk_2` FOREIGN KEY (`idFraisForfait`) REFERENCES `fraisforfait` (`id`);

--
-- Constraints for table `lignefraishorsforfait`
--
ALTER TABLE `lignefraishorsforfait`
  ADD CONSTRAINT `lignefraishorsforfait_ibfk_1` FOREIGN KEY (`idVisiteur`,`mois`) REFERENCES `fichefrais` (`idVisiteur`, `mois`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
