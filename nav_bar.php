<br>
<div class="navbar">
    <div class="logo">
        <a href="index.php">
            <img src="res/logo_nom_site.png" alt="Logo Gluconet" />
        </a>
    </div>

    <div class="nav-buttons">
        <button class="nav-button" onclick="window.location.href = 'index.php'">Accueil</button>
        <?php
        $dashboardLink = 'track.php';
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'medecin') {
            $dashboardLink = 'medecin_dashboard.php';
        }
        ?>
        <button class="nav-button" onclick="window.location.href = '<?php echo $dashboardLink; ?>'">Tableau de
            bord</button>
        <button class="nav-button" onclick="window.location.href = 'aide.php'">Aide</button>
    </div>

    <div class="user-icon">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php
            $profileLink = 'profil.php';
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'medecin') {
                $profileLink = 'profil_medecin.php';
            }
            ?>
            <a href="<?php echo $profileLink; ?>" class="user-profile-link" title="Accéder à mon profil">
                <span class="material-symbols-outlined">person</span>
                <span><?php echo htmlspecialchars($_SESSION['user_prenom'] ?? ''); ?></span>
            </a>
            <a href="backend/deconnexion.php" class="logout-btn">Déconnexion</a>
        <?php else: ?>
            <a href="connexion.php" class="login-link">
                <span class="material-symbols-outlined">
                    login
                </span>
            </a>
        <?php endif; ?>
    </div>
</div>