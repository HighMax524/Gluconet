<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="res/style.css">
    <link href='res/logo_site.png' rel='icon'>
    <title>FAQ - Gluconet</title>

    <style>
        :root{
            --bg:#e6f7e9;
            --card:#ffffff;
            --primary:#2e8b57;
            --secondary:#7bd389;
            --text:#123524;
            --muted:#4f7d63;
            --border:#cdebd7;
        }

        body{
            margin:0;
            font-family: Arial, Helvetica, sans-serif;
            background:linear-gradient(135deg,#b7e4c7,#95d5b2);
            color:var(--text);
        }

        .container{
            max-width:900px;
            margin:auto;
            padding:40px 16px;
        }

        h1{
            text-align:center;
            color:var(--primary);
            margin-bottom:10px;
        }

        .subtitle{
            text-align:center;
            color:var(--muted);
            margin-bottom:30px;
        }

        .faq{
            background:var(--card);
            border-radius:16px;
            box-shadow:0 10px 25px rgba(0,0,0,.15);
            overflow:hidden;
        }

        details{
            border-bottom:1px solid var(--border);
            padding:0 20px;
        }

        details:last-child{
            border-bottom:none;
        }

        summary{
            list-style:none;
            cursor:pointer;
            padding:18px 0;
            font-weight:600;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        summary::-webkit-details-marker{
            display:none;
        }

        summary span{
            color:var(--primary);
        }

        details[open] summary span{
            color:#1b5e3a;
        }

        .answer{
            padding:0 0 18px 0;
            color:var(--text);
        }

        footer{
            text-align:center;
            margin-top:20px;
            font-size:14px;
            color:var(--muted);
        }
    </style>
</head>

<body>

<div class="container">
    <h1>FAQ – Gluconet</h1>
    <p class="subtitle">
        Questions fréquentes sur la plateforme Gluconet et son utilisation
    </p>

    <section class="faq">

        <details>
            <summary>
                <span>Qu’est-ce que Gluconet ?</span> ➜
            </summary>
            <div class="answer">
                Gluconet est une plateforme conçue pour aider les personnes diabétiques
                à mieux s’organiser et à suivre leur santé au quotidien.
            </div>
        </details>

        <details>
            <summary>
                <span>À qui s’adresse Gluconet ?</span> ➜
            </summary>
            <div class="answer">
                Gluconet s’adresse aux personnes atteintes de diabète, quel que soit leur âge,
                ainsi qu’aux professionnels de santé souhaitant un meilleur suivi.
            </div>
        </details>

        <details>
            <summary>
                <span>Que puis-je suivre avec Gluconet ?</span> ➜
            </summary>
            <div class="answer">
                Vous pouvez suivre votre glycémie, votre alimentation,
                votre activité physique et vos traitements médicaux.
            </div>
        </details>

        <details>
            <summary>
                <span>Gluconet remplace-t-il un médecin ?</span> ➜
            </summary>
            <div class="answer">
                Non. Gluconet est un outil d’aide au suivi et à l’organisation,
                mais il ne remplace en aucun cas un avis médical.
            </div>
        </details>

        <details>
            <summary>
                <span>Mes données de santé sont-elles sécurisées ?</span> ➜
            </summary>
            <div class="answer">
                Oui, la sécurité et la confidentialité des données de santé
                sont une priorité pour Gluconet.
            </div>
        </details>

        <details>
            <summary>
                <span>Comment commencer à utiliser Gluconet ?</span> ➜
            </summary>
            <div class="answer">
                Il suffit de créer un compte, puis de renseigner vos informations
                afin de commencer le suivi quotidien.
            </div>
        </details>

    </section>
</div>
<?php include 'footer.php'; ?>

</body>
</html>
