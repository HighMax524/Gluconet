let selectedActivity = null;

const metValues = {
    marche: 4,
    course: 8,
    natation: 6,
    velo: 6,
    musculation: 5,
    autres: 4
};

const activityButtons = document.querySelectorAll(".activity");
const result = document.getElementById("result");
const durationInput = document.getElementById("duration");

activityButtons.forEach(button => {
    button.addEventListener("click", () => {

        // Si on clique sur la même activité → désélection
        if (selectedActivity === button.dataset.activity) {
            button.classList.remove("active");
            selectedActivity = null;
            result.textContent = "";
            return;
        }

        // Sinon, on sélectionne la nouvelle activité
        activityButtons.forEach(btn => btn.classList.remove("active"));
        button.classList.add("active");
        selectedActivity = button.dataset.activity;
    });
});

document.getElementById("calculate").addEventListener("click", () => {
    const duration = parseInt(durationInput.value);

    if (!selectedActivity) {
        result.textContent = "Veuillez sélectionner une activité.";
        return;
    }

    if (!duration || duration <= 0) {
        result.textContent = "Veuillez entrer une durée valide.";
        return;
    }

    const calories = metValues[selectedActivity] * duration;
    result.textContent = ` Vous avez brûlé environ ${calories} calories !`;
});
