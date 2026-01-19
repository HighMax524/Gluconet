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