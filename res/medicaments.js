document.addEventListener("DOMContentLoaded", () => {

    let editId = null;

    const heureInput = document.getElementById("heure");
    const freqInput = document.getElementById("frequence");
    const jourSemaineLabel = document.getElementById("jourSemaineLabel");
    const jourSemaineSelect = document.getElementById("jourSemaine");
    const dateMensuelleLabel = document.getElementById("dateMensuelleLabel");
    const dateMensuelleInput = document.getElementById("dateMensuelle");
    const listeBody = document.querySelector("#listeRappels tbody");
    const result = document.getElementById("result");

    function chargerRappels() {
        fetch("backend/traitement_rappels_medicaments.php")
            .then(res => res.json())
            .then(data => afficherRappels(data))
            .catch(err => console.error("Erreur fetch GET :", err));
    }

    function afficherRappels(rappels) {
        listeBody.innerHTML = "";
        if (rappels.length === 0) {
            const tr = document.createElement("tr");
            tr.innerHTML = `<td colspan="4" style="font-style:italic;color:#555;">Aucun rappel actif</td>`;
            listeBody.appendChild(tr);
            return;
        }

        rappels.forEach(r => {
            const tr = document.createElement("tr");
            let jour = "";
                if (r.frequence === "Hebdomadaire") {
                    jour = r.jour_semaine ? r.jour_semaine : "-";
                } else if (r.frequence === "Mensuel") {
                    jour = r.date_mensuelle ? `Jour ${r.date_mensuelle}` : "-";
                }

            tr.innerHTML = `
                <td>${r.heure}</td>
                <td>${r.frequence}</td>
                <td>${jour}</td>
                <td>
                    <button class="edit-btn" onclick="edit(${r.id})">✏️</button>
                    <button class="delete-btn" onclick="del(${r.id})">🗑️</button>
                </td>
            `;
            listeBody.appendChild(tr);
        });
    }

    freqInput.addEventListener("change", () => {
        if (freqInput.value === "Hebdomadaire") {
            jourSemaineLabel.style.display = "inline-block";
            dateMensuelleLabel.style.display = "none";
        } else if (freqInput.value === "Mensuel") {
            dateMensuelleLabel.style.display = "inline-block";
            jourSemaineLabel.style.display = "none";
        } else {
            jourSemaineLabel.style.display = "none";
            dateMensuelleLabel.style.display = "none";
        }
    });

    dateMensuelleInput.addEventListener("input", () => {
        let val = parseInt(dateMensuelleInput.value, 10);
        if (isNaN(val) || val < 1) dateMensuelleInput.value = 1;
        else if (val > 31) dateMensuelleInput.value = 31;
    });

    document.getElementById("saveRappel").addEventListener("click", () => {
        const heure = heureInput.value;
        const frequence = freqInput.value;
        const jour_semaine = frequence === "Hebdomadaire" ? jourSemaineSelect.value : null;
        const date_mensuelle = frequence === "Mensuel" ? dateMensuelleInput.value : null;

        if (!heure) {
            result.textContent = "Veuillez saisir une heure.";
            return;
        }

        const action = editId ? "update" : "create";

        fetch(`backend/traitement_rappels_medicaments.php?action=${action}`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: editId, heure, frequence, jour_semaine, date_mensuelle })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                editId = null;
                heureInput.value = "";
                freqInput.value = "Quotidien";
                jourSemaineLabel.style.display = "none";
                dateMensuelleLabel.style.display = "none";
                result.textContent = "Rappel enregistré avec succès !";
                chargerRappels();
            } else {
                result.textContent = "Erreur lors de l'enregistrement.";
                console.error(data);
            }
        })
        .catch(err => {
            console.error("Erreur fetch POST :", err);
            result.textContent = "Erreur lors de l'enregistrement.";
        });
    });

    window.edit = function(id) {
        fetch("backend/traitement_rappels_medicaments.php")
            .then(res => res.json())
            .then(data => {
                const r = data.find(r => r.id === id);
                if (!r) return;
                editId = r.id;
                heureInput.value = r.heure;
                freqInput.value = r.frequence;

                if (r.frequence === "Hebdomadaire") {
                    jourSemaineLabel.style.display = "inline-block";
                    dateMensuelleLabel.style.display = "none";
                    jourSemaineSelect.value = r.jour_semaine || "Lundi";
                } else if (r.frequence === "Mensuel") {
                    dateMensuelleLabel.style.display = "inline-block";
                    jourSemaineLabel.style.display = "none";
                    dateMensuelleInput.value = r.date_mensuelle || 1;
                } else {
                    jourSemaineLabel.style.display = "none";
                    dateMensuelleLabel.style.display = "none";
                }
            });
    }

    window.del = function(id) {
        fetch("backend/traitement_rappels_medicaments.php?action=delete", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) chargerRappels();
            else console.error(data);
        })
        .catch(err => console.error(err));
    }

    chargerRappels();
});
