-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 10 août 2025 à 08:44
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `payment`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `codecli` varchar(50) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `sexe` varchar(150) NOT NULL,
  `quartier` varchar(150) NOT NULL,
  `niveau` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`codecli`, `nom`, `sexe`, `quartier`, `niveau`, `email`) VALUES
('C001', 'Fenitra', 'M', 'Tsimenatsy', '2', 'fenitraony@gmail.com'),
('C002', 'Lucianah', 'F', 'Tsimenatsy', '1', 'lucianah@gmail.com'),
('C003', 'Jessica', 'F', 'Tsimenatsy', '2', 'jessicaharena@gmail.com'),
('C004', 'Didis', 'M', 'Tsimenatsy', '2', 'didisgamos@gmail.com'),
('C005', 'Claude', 'M', 'Amboriky', '1', 'claudeghadi@gmail.com'),
('C006', 'Elliot', 'M', 'Anketa', '2', 'ravelosambatrasafidyelliot@gmail.com'),
('C007', 'Fitiavana', 'F', 'Tsianaloka', '2', 'fythiavana@gmail.com'),
('C008', 'Sarobidy', 'M', 'Sanfil', '2', 'herimalalaandriamboavonjy@gmail.com'),
('C009', 'Gamos', 'M', 'Sanfil', '2', 'herllandysamoroschristy2022@gmail.com'),
('C010', 'Dillane', 'M', 'Tsianaloka', '3', 'dillane@gmail.com'),
('C33', 'Volatiana Marielle', 'F', 'Presidence', '1', 'dollar.marielle@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `compteur`
--

CREATE TABLE `compteur` (
  `codecompteur` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `pu` int(11) NOT NULL,
  `codecli` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compteur`
--

INSERT INTO `compteur` (`codecompteur`, `type`, `pu`, `codecli`) VALUES
('C23', 'EAU', 200, 'C33'),
('C24', 'ELEC', 1000, 'C33'),
('CT001', 'ELEC', 475, 'C005'),
('CT002', 'ELEC', 475, 'C004'),
('CT003', 'ELEC', 475, 'C006'),
('CT004', 'EAU', 600, 'C001'),
('CT005', 'ELEC', 475, 'C007'),
('CT006', 'EAU', 600, 'C003'),
('CT007', 'ELEC', 475, 'C003'),
('CT008', 'ELEC', 475, 'C003'),
('CT009', 'EAU', 600, 'C002'),
('CT010', 'ELEC', 475, 'C008'),
('CT011', 'ELEC', 475, 'C003'),
('CT012', 'ELEC', 475, 'C009'),
('CT013', 'EAU', 600, 'C009'),
('CT014', 'ELEC', 475, 'C010'),
('CT015', 'ELEC', 475, 'C010'),
('CT016', 'EAU', 600, 'C010'),
('CT017', 'EAU', 600, 'C010'),
('CT018', 'EAU', 600, 'C010');

-- --------------------------------------------------------

--
-- Structure de la table `payer`
--

CREATE TABLE `payer` (
  `idpaye` varchar(50) NOT NULL,
  `codecli` varchar(50) NOT NULL,
  `datepaie` date NOT NULL,
  `montant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `payer`
--

INSERT INTO `payer` (`idpaye`, `codecli`, `datepaie`, `montant`) VALUES
('P001', 'C005', '2025-01-12', 4750),
('P002', 'C010', '2025-01-12', 10450),
('P003', 'C006', '2025-01-25', 4750),
('P004', 'C003', '2025-01-13', 5400),
('P005', 'C003', '2025-02-11', 6650),
('P006', 'C010', '2025-02-11', 7600),
('P007', 'C001', '2025-03-17', 6000),
('P008', 'C003', '2025-02-26', 3800),
('P009', 'C007', '2025-02-06', 5225),
('P010', 'C003', '2025-03-13', 4275),
('P011', 'C010', '2025-01-27', 18000),
('P012', 'C010', '2025-02-12', 10200),
('P013', 'C002', '2025-01-13', 7200),
('P014', 'C010', '2025-03-19', 7800),
('P015', 'C008', '2025-03-19', 5700);

-- --------------------------------------------------------

--
-- Structure de la table `releve_eau`
--

CREATE TABLE `releve_eau` (
  `codeEau` varchar(50) NOT NULL,
  `codecompteur` varchar(50) NOT NULL,
  `valeur2` int(11) NOT NULL,
  `date_releve2` date NOT NULL,
  `date_presentation2` date NOT NULL,
  `date_limite_paie2` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `releve_eau`
--

INSERT INTO `releve_eau` (`codeEau`, `codecompteur`, `valeur2`, `date_releve2`, `date_presentation2`, `date_limite_paie2`) VALUES
('R001', 'C23', 50, '2025-03-24', '2025-04-01', '2025-04-05'),
('REA001', 'CT006', 9, '2025-01-03', '2025-01-04', '2025-01-31'),
('REA002', 'CT009', 12, '2025-01-03', '2025-01-04', '2025-01-31'),
('REA003', 'CT004', 10, '2025-03-01', '2025-03-02', '2025-03-31'),
('REA004', 'CT013', 6, '2025-02-02', '2025-02-03', '2025-02-28'),
('REA005', 'CT016', 30, '2025-01-02', '2025-01-03', '2025-01-31'),
('REA006', 'CT017', 17, '2025-02-01', '2025-02-02', '2025-02-28'),
('REA007', 'CT018', 13, '2025-03-01', '2025-03-02', '2025-03-31');

-- --------------------------------------------------------

--
-- Structure de la table `releve_elec`
--

CREATE TABLE `releve_elec` (
  `codeElec` varchar(50) NOT NULL,
  `codecompteur` varchar(50) NOT NULL,
  `valeur1` int(11) NOT NULL,
  `date_releve` date NOT NULL,
  `date_presentation` date NOT NULL,
  `date_limite_paie` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `releve_elec`
--

INSERT INTO `releve_elec` (`codeElec`, `codecompteur`, `valeur1`, `date_releve`, `date_presentation`, `date_limite_paie`) VALUES
('R001', 'C24', 100, '2025-02-24', '2025-02-01', '2025-02-05'),
('REL001', 'CT001', 10, '2025-01-02', '2025-01-03', '2025-01-31'),
('REL002', 'CT002', 20, '2025-02-02', '2025-02-03', '2025-02-28'),
('REL003', 'CT003', 10, '2025-01-02', '2025-01-03', '2025-01-31'),
('REL004', 'CT007', 14, '2025-02-02', '2025-02-03', '2025-02-28'),
('REL005', 'CT008', 8, '2025-02-02', '2025-02-03', '2025-02-28'),
('REL006', 'CT005', 11, '2025-02-02', '2025-02-03', '2025-02-28'),
('REL007', 'CT010', 12, '2025-03-01', '2025-03-02', '2025-03-31'),
('REL008', 'CT011', 9, '2025-03-01', '2025-03-02', '2025-03-31'),
('REL009', 'CT012', 9, '2025-03-01', '2025-03-02', '2025-03-31'),
('REL010', 'CT014', 22, '2025-01-02', '2025-01-03', '2025-01-31'),
('REL011', 'CT015', 16, '2025-02-02', '2025-02-03', '2025-02-28');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `idUser` int(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`idUser`, `name`, `phone`, `email`, `password`) VALUES
(6, 'Didis', '+261 34 97 487 75', 'herllandysamoroschristy@gmail.com', '$2y$10$ED0aCK3wGBHg.OHB5Idrf.to4Ks6u52AzeMqYMpuZ1/hukFPSq23a');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`codecli`);

--
-- Index pour la table `compteur`
--
ALTER TABLE `compteur`
  ADD PRIMARY KEY (`codecompteur`),
  ADD KEY `codecli` (`codecli`);

--
-- Index pour la table `payer`
--
ALTER TABLE `payer`
  ADD PRIMARY KEY (`idpaye`),
  ADD KEY `codecli` (`codecli`);

--
-- Index pour la table `releve_eau`
--
ALTER TABLE `releve_eau`
  ADD PRIMARY KEY (`codeEau`),
  ADD KEY `codecompteur` (`codecompteur`);

--
-- Index pour la table `releve_elec`
--
ALTER TABLE `releve_elec`
  ADD PRIMARY KEY (`codeElec`),
  ADD KEY `codecompteur` (`codecompteur`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `idUser` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `compteur`
--
ALTER TABLE `compteur`
  ADD CONSTRAINT `compteur_ibfk_1` FOREIGN KEY (`codecli`) REFERENCES `client` (`codecli`) ON DELETE CASCADE;

--
-- Contraintes pour la table `payer`
--
ALTER TABLE `payer`
  ADD CONSTRAINT `payer_ibfk_1` FOREIGN KEY (`codecli`) REFERENCES `client` (`codecli`) ON DELETE CASCADE;

--
-- Contraintes pour la table `releve_eau`
--
ALTER TABLE `releve_eau`
  ADD CONSTRAINT `releve_eau_ibfk_1` FOREIGN KEY (`codecompteur`) REFERENCES `compteur` (`codecompteur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `releve_elec`
--
ALTER TABLE `releve_elec`
  ADD CONSTRAINT `releve_elec_ibfk_1` FOREIGN KEY (`codecompteur`) REFERENCES `compteur` (`codecompteur`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
