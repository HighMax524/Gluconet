<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="res/style.css">
    <title>Role</title>
</head>

<body>
    <?php include 'nav_bar.php'; ?>
    <div class="role_form">
        <h1>Vous êtes ?</h1>
        <div class="form_role_container">
            <form id="form_inscr" action="backend/traitement_role.php" method="post">
                <label for="role_patient" class="role-option"
                    style="display:inline-flex;flex-direction:column;align-items:center;margin:0 12px;">
                    <input id="role_patient" type="radio" name="role" value="patient" required>
                    <img src="res/patient.png" alt="patient">
                    <span style="margin-top:8px; text-align:center; display:block;">Patient</span>
                </label>

                <label for="role_medecin" class="role-option"
                    style="display:inline-flex;flex-direction:column;align-items:center;margin:0 12px;">
                    <input id="role_medecin" type="radio" name="role" value="medecin">
                    <img src="res/medecin.png" alt="médecin">
                    <span style="margin-top:8px; text-align:center; display:block;">Médecin</span>
                </label>
                <br>
                <br>
                <div class="submit_role">
                    <button type="submit" class="boutton_form">Valider</button>
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>