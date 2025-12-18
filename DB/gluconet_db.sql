-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 15 déc. 2025 à 10:59
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
-- Base de données : `gluconet_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `activites`
--

CREATE TABLE `activites` (
  `id_activite` int(11) NOT NULL,
  `id_patient` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `duree` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `maladie_chronique`
--

CREATE TABLE `maladie_chronique` (
  `id_maladie` int(11) NOT NULL,
  `id_patient` int(11) NOT NULL,
  `maladie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `medecin`
--

CREATE TABLE `medecin` (
  `RPPS` varchar(20) NOT NULL,
  `etablissement` varchar(255) NOT NULL,
  `adresse_pro` text NOT NULL,
  `telephone_pro` varchar(20) NOT NULL,
  `Utilisateur_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `medecin_specialite`
--

CREATE TABLE `medecin_specialite` (
  `id_medecin` varchar(20) NOT NULL,
  `id_specialite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `date_heure` datetime NOT NULL DEFAULT current_timestamp(),
  `recu` tinyint(1) NOT NULL DEFAULT 0,
  `contenu` text NOT NULL,
  `fichier_joint` varchar(255) DEFAULT NULL,
  `id_emetteur` int(11) NOT NULL,
  `id_destinataire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mesureglycemie`
--

CREATE TABLE `mesureglycemie` (
  `id` int(11) NOT NULL,
  `id_patient` int(11) NOT NULL,
  `valeur` decimal(5,2) NOT NULL,
  `date_heure` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `patient`
--

CREATE TABLE `patient` (
  `id_utilisateur` int(11) NOT NULL,
  `seuil_alerte_bas` decimal(5,2) DEFAULT NULL,
  `seuil_alerte_haut` decimal(5,2) DEFAULT NULL,
  `type_diabete` enum('Type 1','Type 2') NOT NULL,
  `date_diagnostic` date NOT NULL,
  `age` int(11) NOT NULL,
  `sexe` enum('Homme','Femme','Autre') NOT NULL,
  `taille` decimal(4,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `poids`
--

CREATE TABLE `poids` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `poids` decimal(5,2) NOT NULL,
  `date_heure` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rappel`
--

CREATE TABLE `rappel` (
  `id` int(11) NOT NULL,
  `id_patient` int(11) NOT NULL,
  `type` enum('Medicament','Mesure','Consultation','Autre') NOT NULL,
  `heure` time NOT NULL,
  `frequence` enum('Quotidien','Hebdomadaire','Mensuel','Ponctuel') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `relation_patient_medecin`
--

CREATE TABLE `relation_patient_medecin` (
  `id` int(11) NOT NULL,
  `id_patient` int(11) NOT NULL,
  `id_medecin` varchar(20) NOT NULL,
  `statut` enum('En attente','Approuve','Refuse') NOT NULL DEFAULT 'En attente',
  `date_demande` datetime NOT NULL DEFAULT current_timestamp(),
  `date_reponse` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `repas`
--

CREATE TABLE `repas` (
  `id` int(11) NOT NULL,
  `id_patient` int(11) NOT NULL,
  `descriptions` text NOT NULL,
  `calories` int(11) NOT NULL,
  `date_heure` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `specialite`
--

CREATE TABLE `specialite` (
  `id_specialite` int(11) NOT NULL,
  `nom_specialite` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_inscription` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activites`
--
ALTER TABLE `activites`
  ADD PRIMARY KEY (`id_activite`),
  ADD KEY `id_patient` (`id_patient`);

--
-- Index pour la table `maladie_chronique`
--
ALTER TABLE `maladie_chronique`
  ADD PRIMARY KEY (`id_maladie`),
  ADD KEY `id_patient` (`id_patient`);

--
-- Index pour la table `medecin`
--
ALTER TABLE `medecin`
  ADD PRIMARY KEY (`RPPS`),
  ADD KEY `Utilisateur_id` (`Utilisateur_id`);

--
-- Index pour la table `medecin_specialite`
--
ALTER TABLE `medecin_specialite`
  ADD PRIMARY KEY (`id_medecin`,`id_specialite`),
  ADD KEY `id_specialite` (`id_specialite`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_emetteur` (`id_emetteur`),
  ADD KEY `id_destinataire` (`id_destinataire`);

--
-- Index pour la table `mesureglycemie`
--
ALTER TABLE `mesureglycemie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_patient` (`id_patient`);

--
-- Index pour la table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- Index pour la table `poids`
--
ALTER TABLE `poids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `rappel`
--
ALTER TABLE `rappel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_patient` (`id_patient`);

--
-- Index pour la table `relation_patient_medecin`
--
ALTER TABLE `relation_patient_medecin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_patient` (`id_patient`),
  ADD KEY `id_medecin` (`id_medecin`);

--
-- Index pour la table `repas`
--
ALTER TABLE `repas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_patient` (`id_patient`);

--
-- Index pour la table `specialite`
--
ALTER TABLE `specialite`
  ADD PRIMARY KEY (`id_specialite`),
  ADD UNIQUE KEY `nom_specialite` (`nom_specialite`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `email_2` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activites`
--
ALTER TABLE `activites`
  MODIFY `id_activite` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `maladie_chronique`
--
ALTER TABLE `maladie_chronique`
  MODIFY `id_maladie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mesureglycemie`
--
ALTER TABLE `mesureglycemie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `poids`
--
ALTER TABLE `poids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rappel`
--
ALTER TABLE `rappel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `relation_patient_medecin`
--
ALTER TABLE `relation_patient_medecin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `repas`
--
ALTER TABLE `repas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `specialite`
--
ALTER TABLE `specialite`
  MODIFY `id_specialite` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activites`
--
ALTER TABLE `activites`
  ADD CONSTRAINT `fk_activite_patient` FOREIGN KEY (`id_patient`) REFERENCES `patient` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `maladie_chronique`
--
ALTER TABLE `maladie_chronique`
  ADD CONSTRAINT `fk_maladie_patient` FOREIGN KEY (`id_patient`) REFERENCES `patient` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `medecin`
--
ALTER TABLE `medecin`
  ADD CONSTRAINT `fk_medecin_utilisateur` FOREIGN KEY (`Utilisateur_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `medecin_specialite`
--
ALTER TABLE `medecin_specialite`
  ADD CONSTRAINT `fk_ms_medecin` FOREIGN KEY (`id_medecin`) REFERENCES `medecin` (`RPPS`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ms_specialite` FOREIGN KEY (`id_specialite`) REFERENCES `specialite` (`id_specialite`) ON DELETE CASCADE;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_message_destinataire` FOREIGN KEY (`id_destinataire`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_message_emetteur` FOREIGN KEY (`id_emetteur`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `mesureglycemie`
--
ALTER TABLE `mesureglycemie`
  ADD CONSTRAINT `fk_mesure_patient` FOREIGN KEY (`id_patient`) REFERENCES `patient` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `fk_patient_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `poids`
--
ALTER TABLE `poids`
  ADD CONSTRAINT `fk_poids_patient` FOREIGN KEY (`id_utilisateur`) REFERENCES `patient` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `rappel`
--
ALTER TABLE `rappel`
  ADD CONSTRAINT `fk_rappel_patient` FOREIGN KEY (`id_patient`) REFERENCES `patient` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `relation_patient_medecin`
--
ALTER TABLE `relation_patient_medecin`
  ADD CONSTRAINT `fk_rpm_medecin` FOREIGN KEY (`id_medecin`) REFERENCES `medecin` (`RPPS`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_rpm_patient` FOREIGN KEY (`id_patient`) REFERENCES `patient` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `repas`
--
ALTER TABLE `repas`
  ADD CONSTRAINT `fk_repas_patient` FOREIGN KEY (`id_patient`) REFERENCES `patient` (`id_utilisateur`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
