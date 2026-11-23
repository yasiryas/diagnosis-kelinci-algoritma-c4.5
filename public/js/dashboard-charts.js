(function () {
    'use strict';

    const chartData = window.dkChartData;
    if (!chartData) {
        return;
    }

    const composition = document.getElementById('dkCompositionChart');
    if (composition && chartData.doughnut) {
        new Chart(composition.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: chartData.doughnut.labels,
                datasets: [{
                    data: chartData.doughnut.values,
                    backgroundColor: chartData.doughnut.colors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'bottom' }
            }
        });
    }

    const consultations = document.getElementById('dkConsultationChart');
    if (consultations && chartData.bar) {
        new Chart(consultations.getContext('2d'), {
            type: 'bar',
            data: {
                labels: chartData.bar.labels,
                datasets: [{
                    label: 'Jumlah konsultasi',
                    data: chartData.bar.values,
                    backgroundColor: chartData.bar.color
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
                }
            }
        });
    }
})();
