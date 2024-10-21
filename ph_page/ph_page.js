// script.js
const grafik = document.getElementById('grafik-pH');
const data = document.getElementById('data-pH');

// Data pH
const pHData = [
  { tanggal: '2023-01-01', pH: 7.0 },
  { tanggal: '2023-01-02', pH: 7.1 },
  { tanggal: '2023-01-03', pH: 7.2 },
  { tanggal: '2023-01-04', pH: 7.3 },
  { tanggal: '2023-01-05', pH: 7.4 },
];

// Grafik pH
const ctx = grafik.getContext('2d');
const chart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: pHData.map(data => data.tanggal),
    datasets: [{
      label: 'pH',
      data: pHData.map(data => data.pH),
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

// Tabel data pH
pHData.forEach(data => {
  const row = document.createElement('tr');
  row.innerHTML = `
    <td >${data.tanggal}</td>
    <td>${data.pH}</td>
  `;
  data.appendChild(row);
});