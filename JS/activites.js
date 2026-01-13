let selectedActivity = null;

const metValues = {
    marche: 4,
    course: 8,
    natation: 6,
    velo: 6,
    musculation: 5,
    autres: 4
};

const buttons = document.querySelectorAll(".activity");
const durationInput = document.getElementById("duration");
const result = document.getElementById("result");

buttons.forEach(button => {
    button.addEventListener("click", () => {

        // Désélection
        if (selectedActivity === button.dataset.activity) {
            button.classList.remove("active");
            selectedActivity = null;
            result.textContent = "";
            return;
        }

        buttons.forEach(b => b.classList.remove("active"));
        button.classList.add("active");
        selectedActivity = button.dataset.activity;
    });
});

document.getElementById("calculate").addEventListener("click", () => {
    const minutes = parseInt(durationInput.value);

    if (!selectedActivity) {
        result.textContent = "Veuillez sélectionner une activité.";
        return;
    }

    if (!minutes || minutes <= 0) {
        result.textContent = "Veuillez entrer une durée valide.";
        return;
    }

    if (!userWeight || userWeight <= 0) {
        result.textContent = "Poids utilisateur introuvable.";
        return;
    }

    const calories =
        metValues[selectedActivity] *
        userWeight *
        (minutes / 60);

    result.textContent =
        `🔥 Vous avez brûlé environ ${Math.round(calories)} calories.`;
});
