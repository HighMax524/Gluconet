document.addEventListener("DOMContentLoaded", function () {
  console.log("Informations script loaded");

  // Sélection du sexe
  const genderButtons = document.querySelectorAll(".gender-btn");
  const sexeInput = document.getElementById("sexe");

  if (genderButtons.length > 0) {
    genderButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        genderButtons.forEach(b => b.classList.remove("selected"));
        btn.classList.add("selected");

        if (sexeInput) {
          sexeInput.value = btn.getAttribute("data-gender");
          console.log("Sexe sélectionné:", sexeInput.value);
        } else {
          console.error("Input #sexe not found");
        }
      });
    });
  }

  // Sélection du type de diabète
  const diabetesButtons = document.querySelectorAll(".diabetes-btn");
  const diabeteInput = document.getElementById("type_diabete");

  if (diabetesButtons.length > 0) {
    diabetesButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        diabetesButtons.forEach(b => b.classList.remove("selected"));
        btn.classList.add("selected");

        if (diabeteInput) {
          diabeteInput.value = btn.getAttribute("data-type");
          console.log("Diabète sélectionné:", diabeteInput.value);
        } else {
          console.error("Input #type_diabete not found");
        }
      });
    });
  }
});

const ageInput = document.getElementById("age");
const tailleInput = document.getElementById("taille");
const poidsInput = document.getElementById("poids");

ageInput.addEventListener("input", () => {
    let val = parseInt(ageInput.value, 10);
if (isNaN(val) || val < 0) ageInput.value = 0;
        else if (val > 120) ageInput.value = 120;
});

tailleInput.addEventListener("input", () => {
    let val = parseInt(tailleInput.value, 10);
if (isNaN(val) || val < 50) tailleInput.value = 50;
        else if (val > 250) tailleInput.value = 250;
});

poidsInput.addEventListener("input", () => {
    let val = parseInt(poidsInput.value, 10);
if (isNaN(val) || val < 10) poidsInput.value = 10;
        else if (val > 300) poidsInput.value = 300;
});