<?php
session_start();

// Ambil data dari sessionStorage menggunakan JavaScript
if (isset($_SESSION['fullname']) && isset($_SESSION['email'])) {
    $fullname = $_SESSION['fullname'];
    $email = $_SESSION['email'];
    // Proses data lebih lanjut
} else {
    // Redirect ke halaman login jika data tidak ada
    header('Location: http://localhost:8002/signin.html');
    exit();
}
?>
<html>
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
   Fishervice
  </title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="ph_page.css">
  <!-- Firebase SDK for older versions (if not using ES modules) -->
  <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-firestore.js"></script>

 </head>
 <body>
        <div class="main-content">
        <!-- Ganti background video dari link eksternal ke video lokal -->
        <video autoplay muted loop>
            <source src="background/background.mp4" type="video/mp4">
            Your browser does not support the video tag.
    </video>

  <div class="content">
   <div class="header">
    <h1>
    <div id="backButton" class="back-button">
     <i class="fas fa-arrow-left"></i>
     <span class="back-text">Back</span> </div>
     <img alt="Fishervice Logo" src="https://iili.io/2Z190Cl.png"/>
     Fishervice
    </h1>
    <div class="user" style="white-space: nowrap;"> 
     Hi, <?php echo htmlspecialchars($fullname); ?>!</h1>
    </div>
   </div>
   <div class="pH">
    <h2 style="text-align: center;">
     Grafik pH
    </h2>
    <div class="cards">
        <div class="card">
            <!-- Grafik interaktif -->
            <canvas id="pHChart" width="400" height="200"></canvas>
        
            <!-- pH saat ini -->
            <h3 class="pH-title">
                pH saat ini
            </h3>
            
            <!-- Nilai pH -->
            <p id="currentPHValue" class="pH-value">
                0
            </p>       
     </div>
    </div>
   </div>
   <div class="analyst">
    <h2 style="text-align: center;">
     Analyst
    </h2>
    <div class="cards">
     <div class="card">
      <p id="AnalystMessagePH">
        pH air yang ideal untuk sebagian besar jenis ikan air tawar berada dalam rentang 6.5 hingga 8. Kondisi ini menciptakan lingkungan yang optimal bagi ikan untuk tumbuh dan berkembang. 
      </p>
     </div>
    </div>
   </div>
  </div>
  <div class="footer">
    <p>
        <i class="fas fa-envelope">
        </i>
        Email: Fishervice4@gmail.com
       </p>
       <p>
        <i class="fas fa-map-marker-alt">
        </i>
        Location: Telkom University, Bandung, Jawa Barat
       </p>
       <p>
        <i class="fas fa-phone">
        </i>
        Phone: 089520701494 (Muhammad Hasbi Nurhadi)
       </p>
  </div>
 <script>
// Ambil email dari PHP session
const userEmail = '<?php echo $_SESSION['email']; ?>';

// Konfigurasi Firebase
const firebaseConfig = {
    apiKey: "AIzaSyDrvM0uqhKR25Qd8va-5Ts_5zcMPYMv0h8",
  authDomain: "ph-container-73dbc.firebaseapp.com",
  projectId: "ph-container-73dbc",
  storageBucket: "ph-container-73dbc.firebasestorage.app",
  messagingSenderId: "1092227667658",
  appId: "1:1092227667658:web:06039be24f9d8cdbd60633"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Initialize Firestore
const db = firebase.firestore();

// Array lokal untuk menyimpan data pH dan timestamp
let pHData = {
    labels: [], // Untuk timestamp
    values: []  // Untuk nilai pH
};

// Inisialisasi Chart.js
const pHChartData = {
    labels: pHData.labels, // Label timestamp
    datasets: [{
        label: 'Nilai pH',
        data: pHData.values, // Data pH
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderWidth: 1
    }]
};

const ctx = document.getElementById('pHChart').getContext('2d');
const pHChart = new Chart(ctx, {
    type: 'line',
    data: pHChartData,
    options: {
        scales: {
            y: {
                beginAtZero: false,
                suggestedMax: 20 // Disesuaikan dengan data
            }
        }
    }
});

// Fungsi untuk mengonversi timestamp menjadi objek Date
function parseTimestamp(timestamp) {
    const parts = timestamp.split('-');
    const year = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1; // Month dimulai dari 0 (Januari = 0)
    const day = parseInt(parts[2], 10);
    const hours = parseInt(parts[3], 10);
    const minutes = parseInt(parts[4], 10);
    const seconds = parseInt(parts[5], 10);
    
    return new Date(year, month, day, hours, minutes, seconds);
}

// Fungsi untuk menambahkan data baru ke chart
function addDataToChart(timestamp, pHValue) {
    // Cek apakah timestamp sudah ada di grafik
    if (!pHData.labels.includes(timestamp)) {
        pHData.labels.push(timestamp); // Tambahkan timestamp ke labels
        pHData.values.push(pHValue);   // Tambahkan nilai pH ke values

        // Batasi jumlah data hanya 20
        if (pHData.labels.length > 20) {
            pHData.labels.shift(); // Hapus elemen pertama (data tertua)
            pHData.values.shift(); // Hapus elemen pertama (data tertua)
        }

        // Perbarui chart
        pHChart.update();
    }
}

// Fungsi untuk memperbarui nilai pH saat ini
function updateCurrentPHValue(pHValue) {
    const currentPHValueElement = document.getElementById('currentPHValue');
    currentPHValueElement.textContent = pHValue.toFixed(2); // Format dengan 2 desimal
}

// Fungsi untuk memperbarui pesan pH berdasarkan nilai pH
function updateAnalystMessagePH(pH) {
    const analystMessagePHElement = document.getElementById('AnalystMessagePH');

    if (pH >= 6.5 && pH <= 8.0) {
        analystMessagePHElement.textContent = "pH kolam Anda berada dalam rentang ideal. Kondisi ini mendukung pertumbuhan ikan yang sehat dan optimal. Ikan dapat menyerap nutrisi dengan baik dan sistem kekebalan tubuh mereka juga bekerja dengan efisien.";
    } else if ((pH >= 6.0 && pH < 6.5) || (pH > 8.0 && pH <= 8.5)) {
        analystMessagePHElement.textContent = "pH kolam Anda mulai menyimpang dari kondisi ideal. Kondisi ini dapat menyebabkan stres pada ikan dan membuat mereka rentan terhadap penyakit. Perlu dilakukan pengecekan lebih lanjut dan penyesuaian pH secara bertahap.";
    } else {
        analystMessagePHElement.textContent = "pH kolam Anda berada di luar rentang yang aman bagi kehidupan ikan. Kondisi ini sangat berbahaya dan dapat menyebabkan kematian pada ikan dalam waktu singkat. Segera lakukan tindakan untuk menstabilkan pH kolam.";
    }
}

// Fungsi untuk memuat data awal dari Firestore
async function loadInitialData() {
    try {
        const querySnapshot = await db.collection(userEmail)
            .orderBy('timestamp')  // Urutkan berdasarkan timestamp
            .get(); // Ambil seluruh dokumen dari koleksi
        
        querySnapshot.forEach((doc) => {
            const data = doc.data(); // Ambil seluruh data dokumen
            console.log('Data yang diterima:', data);  // Cek data yang diterima

            // Cek apakah timestamp dan pHValue ada langsung dalam data (bukan di dalam fields)
            if (data.timestamp && data.pHValue) {
                const timestamp = data.timestamp;
                const pHValue = parseFloat(data.pHValue); // Parsing angka

                if (timestamp && !isNaN(pHValue)) {
                    const parsedTimestamp = parseTimestamp(timestamp); // Mengonversi timestamp ke Date
                    addDataToChart(parsedTimestamp.toLocaleString(), pHValue); // Ubah format timestamp agar bisa dibaca
                }
            } else {
                console.error('Data tidak lengkap di dokumen:', data);  // Log data yang tidak lengkap
            }
        });

        // Perbarui nilai pH saat ini dengan data terakhir
        if (pHData.values.length > 0) {
            const latestPH = pHData.values[pHData.values.length - 1];
            updateCurrentPHValue(latestPH);
            updateAnalystMessagePH(latestPH); // Perbarui pesan pH
        }

        pHChart.update(); // Perbarui grafik setelah data awal dimuat
    } catch (error) {
        console.error('Gagal memuat data awal:', error);
    }
}

// Fungsi untuk memuat data baru secara real-time
db.collection(userEmail)
    .orderBy('timestamp')  // Urutkan berdasarkan timestamp
    .onSnapshot((querySnapshot) => {
        querySnapshot.docChanges().forEach((change) => {
            if (change.type === "added") {
                const data = change.doc.data(); // Ambil seluruh data dokumen
                console.log('Data baru yang diterima:', data);  // Cek data baru yang diterima
                
                // Cek apakah timestamp dan pHValue ada langsung dalam data (bukan di dalam fields)
                if (data.timestamp && data.pHValue) {
                    const timestamp = data.timestamp;
                    const pHValue = parseFloat(data.pHValue);

                    if (timestamp && !isNaN(pHValue)){
                        const parsedTimestamp = parseTimestamp(timestamp); // Mengonversi timestamp ke Date
                        addDataToChart(parsedTimestamp.toLocaleString(), pHValue); // Ubah format timestamp agar bisa dibaca
                        updateCurrentPHValue(pHValue); // Perbarui nilai pH saat ini
                        updateAnalystMessagePH(pHValue); // Perbarui pesan pH
                    } 
                } else {
                    console.error('Data Firestore tidak lengkap:', data);  // Log data yang tidak lengkap
                }
            }
        });

        pHChart.update();  // Perbarui grafik setelah menerima data baru
    });


// Ambil data awal
loadInitialData();

    document.getElementById('backButton').addEventListener('click', function() {
    console.log('Tombol Back diklik'); // Log ini untuk debugging
    window.location.href = 'http://localhost:8002/dashboardlogin.php'; // URL yang sesuai
});
</script>
 </body>
</html>