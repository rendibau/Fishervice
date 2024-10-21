// script.js
const grafik = document.getElementById('grafik-turbidity');
const data = document.getElementById('data-turbidity');

// Data turbidity
const turbidityData = [
  { tanggal: '2023-01-01', turbidity: 7.0 },
  { tanggal: '2023-01-02', turbidity: 7.1 },
  { tanggal: '2023-01-03', turbidity: 7.2 },
  { tanggal: '2023-01-04', turbidity: 7.3 },
  { tanggal: '2023-01-05', turbidity: 7.4 },
];

// Grafik turbidity
const ctx = grafik.getContext('2d');
const chart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: turbidity.map(data => data.tanggal),
    datasets: [{
      label: 'turbidity',
      data: turbidityData.map(data => data.turbidity),
      backgroundColor: 'rgba(255, 99, 132, 0.2)',
      borderColor: 'rgba(255, 99, 132, 1)',
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
});

// Tabel data turbidity
turbidityData.forEach(data => {
  const row = document.createElement('tr');
  row.innerHTML = `
    <td >${data.tanggal}</td>
    <td>${data.turbidity}</td>
  `;
  data.appendChild(row);
});