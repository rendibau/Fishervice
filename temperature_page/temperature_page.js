// script.js
const grafik = document.getElementById('grafik-temperature');
const data = document.getElementById('data-temperature');

// Data temperature
const temperatureData = [
  { tanggal: '2023-01-01', temperature: 7.0 },
  { tanggal: '2023-01-02', temperature: 7.1 },
  { tanggal: '2023-01-03', temperature: 7.2 },
  { tanggal: '2023-01-04', temperature: 7.3 },
  { tanggal: '2023-01-05', temperature: 7.4 },
];

// Grafik temperature
const ctx = grafik.getContext('2d');
const chart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: temperatureData.map(data => data.tanggal),
    datasets: [{
      label: 'temperature',
      data: temperatureData.map(data => data.temperature),
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

// Tabel data temperature
temperatureData.forEach(data => {
  const row = document.createElement('tr');
  row.innerHTML = `
    <td >${data.tanggal}</td>
    <td>${data.temperature}</td>
  `;
  data.appendChild(row);
});