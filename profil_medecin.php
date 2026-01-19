<?php
session_start();
require_once 'backend/db_connect.php';

// Vérification de la session et du rôle
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'medecin') {
    header("Location: connexion.php");
    exit();
}

require_once 'backend/recuperation_profil_medecin.php';

$profilData = getProfilMedecinData($conn);

if (isset($profilData['redirect'])) {
    header("Location: " . $profilData['redirect']);
    exit();
}

$medecin_info = $profilData['medecin_info'];
$message_success = isset($_GET['success_update']) ? "Profil mis à jour avec succès !" : "";
$message_error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil Médecin - GlucoNet</title>
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/profil_medecin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href='res/logo_site.png' rel='icon'>
</head>

<body>

    <?php include 'nav_bar.php'; ?>

    <div class="profile-container">

        <?php if ($message_success): ?>
            <div class="alert-success"
                style="margin-bottom: 20px; background: #d4edda; color: #155724; padding: 15px; border-radius: 10px;">
                <span class="material-symbols-outlined"
                    style="vertical-align: middle; margin-right: 5px;">check_circle</span>
                <?php echo $message_success; ?>
            </div>
        <?php endif; ?>

        <?php if ($message_error): ?>
            <div class="alert-error"
                style="margin-bottom: 20px; background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px;">
                <span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 5px;">error</span>
                <?php echo $message_error; ?>
            </div>
        <?php endif; ?>

        <div class="profile-card">
            <div class="user-header">
                <div class="user-avatar">
                    <span class="material-symbols-outlined">medical_services</span>
                </div>
                <h2>Dr. <?php echo htmlspecialchars($medecin_info['prenom'] . ' ' . $medecin_info['nom']); ?></h2>
                <p style="color: #666;">Médecin Généraliste / Spécialiste</p>
                <button onclick="openEditModal()" class="btn-edit">
                    <span class="material-symbols-outlined">edit</span> Modifier mes informations
                </button>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Numéro RPPS</span>
                    <div class="info-value">
                        <span class="material-symbols-outlined">badge</span>
                        <?php echo htmlspecialchars($medecin_info['RPPS']); ?>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-label">Email</span>
                    <div class="info-value">
                        <span class="material-symbols-outlined">mail</span>
                        <?php echo htmlspecialchars($medecin_info['email']); ?>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-label">Établissement</span>
                    <div class="info-value">
                        <span class="material-symbols-outlined">apartment</span>
                        <?php echo htmlspecialchars($medecin_info['etablissement']); ?>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-label">Téléphone Pro</span>
                    <div class="info-value">
                        <span class="material-symbols-outlined">call</span>
                        <?php echo htmlspecialchars($medecin_info['telephone_pro']); ?>
                    </div>
                </div>

                <div class="info-item" style="grid-column: 1 / -1;">
                    <span class="info-label">Adresse Professionnelle</span>
                    <div class="info-value">
                        <span class="material-symbols-outlined">location_on</span>
                        <?php echo htmlspecialchars($medecin_info['adresse_pro']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modification -->
    <div id="editModal" class="modal"
        style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
        <div class="modal-content"
            style="background-color: #fefefe; padding: 30px; border-radius: 15px; width: 90%; max-width: 600px; position: relative; max-height: 90vh; overflow-y: auto;">
            <span class="close" onclick="closeEditModal()"
                style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h2 style="color: var(--primary-dark); margin-bottom: 20px; text-align: center;">Modifier mon profil</h2>

            <form action="backend/traitement_modification_medecin.php" method="POST">

                <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px;">Prénom</label>
                        <input type="text" name="prenom"
                            value="<?php echo htmlspecialchars($medecin_info['prenom']); ?>" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px;">Nom</label>
                        <input type="text" name="nom" value="<?php echo htmlspecialchars($medecin_info['nom']); ?>"
                            required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($medecin_info['email']); ?>"
                        required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">Établissement</label>
                    <input type="text" name="etablissement"
                        value="<?php echo htmlspecialchars($medecin_info['etablissement']); ?>" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px;">Téléphone Professionnel</label>
                    <input type="tel" name="telephone_pro"
                        value="<?php echo htmlspecialchars($medecin_info['telephone_pro']); ?>" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 5px;">Adresse Professionnelle</label>
                    <textarea name="adresse_pro" rows="3" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;"><?php echo htmlspecialchars($medecin_info['adresse_pro']); ?></textarea>
                </div>

                <button type="submit" class="btn-edit"
                    style="width: 100%; justify-content: center; margin-top: 0;">Enregistrer les modifications</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function openEditModal() {
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function (event) {
            var modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>