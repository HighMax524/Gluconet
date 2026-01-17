// track.js - Gestion du tableau de bord Gluconet

document.addEventListener('DOMContentLoaded', function () {
    // Récupération de la configuration injectée depuis PHP
    const config = window.GluconetConfig || {};

    const currentGlucose = config.lastGlucose !== "N/A" ? parseFloat(config.lastGlucose) : null;
    const alertMin = parseFloat(config.alertMin);
    const alertMax = parseFloat(config.alertMax);
    const chartLabels = config.chartLabels || [];
    const chartData = config.chartData || [];
    const displayDate = config.displayDate || '';

    // --- Configuration du Graphique ---
    const ctx = document.getElementById('myGlucoseChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(75, 192, 192, 0.5)');
    gradient.addColorStop(1, 'rgba(75, 192, 192, 0.0)');

    let glucoseChart = null;

    if (Chart) {
        glucoseChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Glycémie du ' + displayDate + ' (g/L)',
                    data: chartData,
                    borderColor: '#4bc0c0',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4bc0c0',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        suggestedMin: 0.5,
                        suggestedMax: 1.5,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: { color: '#666' }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: { color: '#666' }
                    }
                },
                plugins: {
                    legend: {
                        labels: { color: '#333' }
                    }
                }
            }
        });
    }

    // --- Notification Logic ---
    const alertButton = document.querySelector('#alerte_box button');
    const alertBox = document.getElementById('alerte_box');

    // --- STATE MANAGEMENT ---
    let isAlertEnabled = localStorage.getItem('gluco_alerts_enabled') === 'true';

    function updateButtonUI() {
        if (isAlertEnabled) {
            alertButton.innerHTML = '<span id="alert_bell">🔔</span> Alert ON';
            alertButton.style.background = '#2e7d32'; // Green
            alertButton.style.color = 'white';
        } else {
            alertButton.innerHTML = '<span id="alert_bell" style="filter: grayscale(1);">🔕</span> Alert OFF';
            alertButton.style.background = '#d32f2f'; // Red/Grey
            alertButton.style.color = 'white';

            // Remove visual alert if disabled
            alertBox.style.boxShadow = "none";
            alertBox.style.border = "none";
        }
    }

    // Init UI
    updateButtonUI();

    function checkAndNotify(valueToCheck) {
        const val = valueToCheck !== undefined ? valueToCheck : currentGlucose;

        // Must be enabled AND have a value
        if (!isAlertEnabled || val === null || isNaN(val)) return;

        if (val < alertMin || val > alertMax) {
            // Visual Alert
            alertBox.style.boxShadow = "0 0 15px red";
            alertBox.style.border = "2px solid red";

            // Browser Notification
            if (Notification.permission === "granted") {
                const title = "⚠️ Alerte Glycémie !";
                const msg = `Votre taux de glycémie (${val} g/L) est hors des seuils recommandés (${alertMin} - ${alertMax}).`;
                // Simple debounce could be implemented here
                // new Notification(title, { body: msg, icon: 'res/logo_site.png' });
            }
        } else {
            // Reset if back to normal
            alertBox.style.boxShadow = "none";
            alertBox.style.border = "none";
        }
    }

    // Click Handler
    alertButton.addEventListener('click', function () {
        if (!isAlertEnabled) {
            // Turning ON
            if (!("Notification" in window)) {
                alert("Ce navigateur ne supporte pas les notifications.");
                return;
            }

            if (Notification.permission !== "granted") {
                Notification.requestPermission().then(function (permission) {
                    if (permission === "granted") {
                        isAlertEnabled = true;
                        localStorage.setItem('gluco_alerts_enabled', 'true');
                        updateButtonUI();
                        checkAndNotify();
                    }
                });
            } else {
                // Already granted, just enable
                isAlertEnabled = true;
                localStorage.setItem('gluco_alerts_enabled', 'true');
                updateButtonUI();
                checkAndNotify();
            }
        } else {
            // Turning OFF
            isAlertEnabled = false;
            localStorage.setItem('gluco_alerts_enabled', 'false');
            updateButtonUI();
            // No need to checkAndNotify, UI update already clears styles
        }
    });

    // Check immediately on load if enabled
    if (isAlertEnabled) {
        checkAndNotify();
    }

    // --- AUTO REFRESH LOGIC ---
    function refreshData() {
        fetch('backend/get_glucose_data.php')
            .then(response => response.json())
            .then(data => {
                if (!data.success) return;

                const newVal = parseFloat(data.current_glucose);

                // Update DOM
                const glucoseEl = document.querySelector('#taux_glucose');
                if (glucoseEl) glucoseEl.innerHTML = data.current_glucose + '<br><span style="font-size:0.5em">g/L</span>';

                const maxEl = document.querySelector('#max_value_box p');
                if (maxEl) maxEl.innerText = data.max + ' g/L';

                const minEl = document.querySelector('#min_value_box p');
                if (minEl) minEl.innerText = data.min + ' g/L';

                // Update Chart
                if (glucoseChart) {
                    glucoseChart.data.labels = data.labels;
                    glucoseChart.data.datasets[0].data = data.data;
                    glucoseChart.data.datasets[0].label = 'Glycémie du ' + data.date + ' (g/L)';
                    glucoseChart.update();
                }

                // Re-check alerts with new value
                if (!isNaN(newVal)) {
                    checkAndNotify(newVal);
                }
            })
            .catch(err => console.error("Auto-refresh error:", err));
    }

    // Refresh every 5 seconds
    setInterval(refreshData, 5000);
});
