<div class="navbar">
    <div class="logo">
        <img src="/Gluconet/res/logo_nom_site.png" alt="Logo Gluconet" />
    </div>

    <div class="nav-buttons">
        <button class="nav-button" onclick="window.location.href = 'index.php'">Accueil</button>
        <button class="nav-button" onclick="window.location.href = 'track.php'">Tableau de bord</button>
        <button class="nav-button" onclick="window.location.href = 'track.php'">Aide</button>
    </div>

    <div class="user-icon" style="display: flex; align-items: center; gap: 10px;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <span class="material-symbols-outlined"
                title="<?php echo htmlspecialchars($_SESSION['user_nom'] ?? 'Utilisateur'); ?>">
                person
            </span>
            <a href="deconnexion.php"
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