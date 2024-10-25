    var ctx = document.getElementById('TurbidityChart').getContext('2d');
    var pHChart = new Chart(ctx, {
        type: 'line', // Jenis grafik, bisa 'line', 'bar', dll.
        data: {
            labels: ['Oktober', 'November', 'Desember', 'Januari'], // Label untuk sumbu X
            datasets: [{
                label: 'Nilai Turbidity',
                data: [0, 25, 50, 75, 100], // Data Turbidity
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
    document.getElementById('backButton').addEventListener('click', function() {
        console.log('Tombol Back diklik'); // Log ini untuk debugging
        window.location.href = 'http://localhost:8002/dashboardlogin.php'; // URL yang sesuai
    });