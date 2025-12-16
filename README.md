# Gluconet 🩺🩸

<div align="center">
  <img src="res/logo_site.png" alt="Gluconet Logo" width="150"/>
  <br>
  <em>Votre compagnon numérique pour une meilleure gestion du diabète.</em>
</div>

<br>

**Gluconet** est une application web complète conçue pour aider les personnes atteintes de diabète à suivre et analyser leurs données de santé au quotidien, tout en facilitant la communication avec leur médecin traitant.

---

## ✨ Fonctionnalités Clés

Gluconet offre une suite d'outils puissants pour améliorer la qualité de vie des patients :

*   **📊 Suivi Glycémique Avancé** : Visualisez vos niveaux de glucose à l'aide de graphiques interactifs et analysez vos tendances sur la durée.
*   **💊 Gestion des Médicaments** : Configurez des rappels pour ne jamais oublier votre traitement.
*   **👥 Espace Médecin & Patient** : Une plateforme adaptée à deux types d'utilisateurs :
    *   **Patients** : Pour gérer leur santé.
    *   **Médecins** : Pour suivre l'évolution de leurs patients à distance.
*   **🍎 Journal Alimentaire & Activités** : Suivez l'impact de votre alimentation et de vos activités physiques sur votre glycémie.
*   **💬 Messagerie Intégrée** : Communiquez directement et simplement avec votre professionnel de santé.
*   **💳 Offres Flexibles** : Choisissez entre un abonnement **Standard** (4€/mois) ou **Premium** (7€/mois) selon vos besoins.

## 🚀 Technologies Utilisées

Ce projet est développé avec des technologies web standards et robustes :

*   **Frontend** : HTML5, CSS3 (Design responsive et moderne), JavaScript.
*   **Backend** : PHP 8+.
*   **Serveur Web** : Apache (via XAMPP/LAMPP).

## 📂 Structure du Projet

Voici un aperçu des fichiers principaux :

*   `index.php` : Page d'accueil (Landing page).
*   `connexion.php` / `inscription.php` : Authentification utilisateur.
*   `role.php` : Sélection du rôle (Patient ou Médecin).
*   `track.php` : Tableau de bord de suivi glycémique.
*   `page_medicaments.php` : Interface de gestion des médicaments.
*   `page_activites.php` : Journal des activités et alimentation.
*   `paiement.php` : Page de gestion des abonnements.
*   `res/` : Contient les ressources (images, logos, fichiers CSS).

## 🛠️ Installation et Démarrage

Pour faire tourner Gluconet localement :

1.  **Prérequis** : Assurez-vous d'avoir un environnement serveur comme **XAMPP**, **MAMP** ou **LAMP** installé.
2.  **Installation** :
    *   Clonez ce dépôt ou copiez les fichiers dans le dossier racine de votre serveur (ex: `/opt/lampp/htdocs/gluconet` ou `C:\xampp\htdocs\gluconet`).
3.  **Lancement** :
    *   Démarrez les services **Apache** et **MySQL**.
    *   Ouvrez votre navigateur et accédez à : `http://localhost/gluconet/`

## 🤝 Contribuer

Les contributions sont les bienvenues ! Si vous souhaitez améliorer Gluconet, n'hésitez pas à :
1.  Forker le projet.
2.  Créer une branche pour votre fonctionnalité (`git checkout -b feature/NouvelleFeature`).
3.  Commit vos changements (`git commit -m 'Ajout de NouvelleFeature'`).
4.  Push vers la branche (`git push origin feature/NouvelleFeature`).
5.  Ouvrir une Pull Request.

---
<div align="center">
  <small>Développé avec ❤️ pour la santé de tous.</small>
</div>