<br>
<div class="navbar">
    <div class="logo">
        <a href="index.php">
        <img src="res/logo_nom_site.png" alt="Logo Gluconet" />
        </a>
    </div>

    <div class="nav-buttons">
        <button class="nav-button" onclick="window.location.href = 'index.php'">Accueil</button>
        <button class="nav-button" onclick="window.location.href = 'track.php'">Tableau de bord</button>
        <button class="nav-button" onclick="window.location.href = 'faq.html'">Aide</button>
    </div>

    <div class="user-icon" style="display: flex; align-items: center; gap: 10px;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profil.php"
                style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 5px;"
                title="Accéder à mon profil">
                <span class="material-symbols-outlined">person</span>
                <span><?php echo htmlspecialchars($_SESSION['user_prenom'] ?? ''); ?></span>
            </a>
            <a href="backend/deconnexion.php"
                style="text-decoration: none; color: inherit; font-size: 0.8rem; border: 1px solid currentColor; padding: 2px 8px; border-radius: 4px;">Déconnexion</a>
        <?php else: ?>
            <a href="connexion.php" style="text-decoration: none; color: inherit;">
                <span class="material-symbols-outlined">
                    login
                </span>
            </a>
        <?php endif; ?>
    </div>
</div>