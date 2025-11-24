noUiSlider.create(document.getElementById('slider'), {
  start: [0.5, 1.5],
  connect: true,
  tooltips: [
    { to: value => value.toFixed(2) },
    { to: value => value.toFixed(2) }
  ],
  range: {
    'min': 0,
    'max': 2
  }
});

fetch('../nav_bar.html')
      .then(response => response.text())
      .then(data => {
        document.getElementById('nav_bar').innerHTML = data;
      });