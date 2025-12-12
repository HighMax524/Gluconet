// Sélection du sexe
const genderButtons = document.querySelectorAll(".gender-btn");

genderButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    genderButtons.forEach(b => b.classList.remove("selected"));
    btn.classList.add("selected");
  });
});

// Sélection du type de diabète
const diabetesButtons = document.querySelectorAll(".diabetes-btn");

diabetesButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    diabetesButtons.forEach(b => b.classList.remove("selected"));
    btn.classList.add("selected");
  });
});