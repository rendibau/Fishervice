var ctx = document.getElementById('pHChart').getContext('2d');
var pHChart = new Chart(ctx, {
    type: 'line', // Jenis grafik, bisa 'line', 'bar', dll.
    data: {
        labels: ['Oktober', 'November', 'Desember', 'Januari'], // Label untuk sumbu X
        datasets: [{
            label: 'Nilai pH',
            data: [7.0, 7.5, 8.0, 8.3, 8.5, 8.5], // Data pH
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true, // Sumbu Y mulai dari 0
                suggestedMax: 10  // Maksimal nilai sumbu Y
            }
        }
    }
});