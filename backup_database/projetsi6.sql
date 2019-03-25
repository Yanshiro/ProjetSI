-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mar 12 Mars 2019 à 13:24
-- Version du serveur :  5.7.14
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `projetsi6`
--

-- --------------------------------------------------------

--
-- Structure de la table `droit`
--

CREATE TABLE `droit` (
  `id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `droit_utilisateur`
--

CREATE TABLE `droit_utilisateur` (
  `id` int(11) NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `idRole` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE `enseignant` (
  `id` int(11) NOT NULL,
  `Nom` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `enseignant`
--

INSERT INTO `enseignant` (`id`, `Nom`) VALUES
(1, 'dsss');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `entreprise` varchar(100) NOT NULL,
  `niveau` varchar(100) NOT NULL,
  `groupe` int(10) NOT NULL,
  `idEnseignant` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `nom`, `prenom`, `entreprise`, `niveau`, `groupe`, `idEnseignant`) VALUES
(2, 'qsdqsd', 'qsdsq', 'qzqd', '2', 2, 1),
(16, 'RRR', 'RRR', 'RRR', 'RRR', 1, 1),
(12, 'lo', 'lo', 'lo', 'lo', 2, 1),
(13, 'lo', 'lo', 'lo', 'lo', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `pseudo`
--

CREATE TABLE `pseudo` (
  `id` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `id_enseignant` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ro`
--

CREATE TABLE `ro` (
  `id` int(11) NOT NULL,
  `Matiere` decimal(10,0) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `ro`
--

INSERT INTO `ro` (`id`, `Matiere`) VALUES
(1, '12');

-- --------------------------------------------------------

--
-- Structure de la table `systemejointure`
--

CREATE TABLE `systemejointure` (
  `id` int(11) NOT NULL,
  `Table1` varchar(255) NOT NULL,
  `Champ1` varchar(255) NOT NULL,
  `Table2` varchar(255) NOT NULL,
  `Champ2` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `systemejointure`
--

INSERT INTO `systemejointure` (`id`, `Table1`, `Champ1`, `Table2`, `Champ2`) VALUES
(2, 'etudiant', 'idEnseignant', 'enseignant', 'id');

-- --------------------------------------------------------

--
-- Structure de la table `table`
--

CREATE TABLE `table` (
  `id` int(11) NOT NULL,
  `table` varchar(255) NOT NULL,
  `tableSysteme` tinyint(1) NOT NULL DEFAULT '0',
  `idDroit` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='logicielTable';

--
-- Contenu de la table `table`
--

INSERT INTO `table` (`id`, `table`, `tableSysteme`, `idDroit`) VALUES
(11, 'droit', 1, 1),
(2, 'etudiant', 0, 1),
(3, 'droit_utilisateur', 1, 1),
(4, 'pseudo', 1, 1),
(25, 'RO', 0, 1),
(6, 'utilisateur', 1, 1),
(21, 'enseignant', 0, 1),
(22, 'SystemeJointure', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `test` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `login`, `mdp`, `test`) VALUES
(2, 'hugo', '3faf7ed52fa83d583fc670a96bcf92da270d0767', NULL);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `droit`
--
ALTER TABLE `droit`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `droit_utilisateur`
--
ALTER TABLE `droit_utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idEnseignant` (`idEnseignant`);

--
-- Index pour la table `pseudo`
--
ALTER TABLE `pseudo`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ro`
--
ALTER TABLE `ro`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `systemejointure`
--
ALTER TABLE `systemejointure`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `table`
--
ALTER TABLE `table`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `droit`
--
ALTER TABLE `droit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `droit_utilisateur`
--
ALTER TABLE `droit_utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `enseignant`
--
ALTER TABLE `enseignant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `pseudo`
--
ALTER TABLE `pseudo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `ro`
--
ALTER TABLE `ro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `systemejointure`
--
ALTER TABLE `systemejointure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `table`
--
ALTER TABLE `table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
