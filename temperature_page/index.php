<?php
session_start();

// Cek apakah pengguna sudah login (data fullname ada dalam session)
$fullname = $_SESSION['fullname'] ?? 'Guest';

?>
<html>
 <head>
  <title>
   Fishervice
  </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="temperature_page.css">
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
     <button id="backButton" class="back-button">
     <i class="fas fa-arrow-left"></i>
     <span class="back-text">Back</span> </button>
     <img alt="Fishervice Logo" src="https://gcdnb.pbrd.co/images/zFDCP4zQaltQ.png?o=1"/>
     Fishervice
    </h1>
    <div class="user" style="white-space: nowrap;"> 
     Hi, <?php echo htmlspecialchars($fullname); ?>!</h1>
    </div>
   </div>
   <div class="temperature"> 
    <h2 style="text-align: center;">
     Grafik Temperature
    </h2>
    <div class="cards">
     <div class="card">
            <!-- Grafik interaktif -->
            <canvas id="TempChart" width="400" height="200"></canvas>
        
            <!-- Temperature saat ini -->
            <h3 style="font-size: 32px; font-weight: bold; color: #29b6f6; text-transform: uppercase; letter-spacing: 1px; margin-top: 20px;">
                Temperature saat ini
            </h3>
            
            <!-- Nilai Temperature -->
            <p id="CurrentTempValue" style="font-size: 40px; font-weight: bold; color: white; background-color: #29b6f6; padding: 15px 30px; border-radius: 50px; display: inline-block; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); margin-top: 10px;">
                0°C
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
      <p id="AnalystMessageTemp">
        Suhu ideal untuk ikan berkisar antara 24°C hingga 28°C. Rentang suhu ini memastikan pertumbuhan dan kesehatan ikan yang optimal.
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
    apiKey: "AIzaSyCxfE1LX7VLTxtVTCJDPfBKa2kz0INCM8U",
    authDomain: "temperature-page-4efeb.firebaseapp.com",
    projectId: "temperature-page-4efeb",
    storageBucket: "temperature-page-4efeb.firebasestorage.app",
    messagingSenderId: "208492571853",
    appId: "1:208492571853:web:29a5424eafd161daedcd84"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Initialize Firestore
const db = firebase.firestore();

// Array lokal untuk menyimpan data Temp dan timestamp
let TempData = {
    labels: [], // Untuk timestamp
    values: []  // Untuk nilai Temp
};

// Inisialisasi Chart.js
const TempChartData = {
    labels: TempData.labels, // Label timestamp
    datasets: [{
        label: 'Nilai Temp',
        data: TempData.values, // Data Temp
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderWidth: 1
    }]
};

const ctx = document.getElementById('TempChart').getContext('2d');
const TempChart = new Chart(ctx, {
    type: 'line',
    data: TempChartData,
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
function addDataToChart(timestamp, TempC) {
    // Cek apakah timestamp sudah ada di grafik
    if (!TempData.labels.includes(timestamp)) {
        TempData.labels.push(timestamp); // Tambahkan timestamp ke labels
        TempData.values.push(TempC);   // Tambahkan nilai Temp ke values

        // Batasi jumlah data hanya 20
        if (TempData.labels.length > 20) {
            TempData.labels.shift(); // Hapus elemen pertama (data tertua)
            TempData.values.shift(); // Hapus elemen pertama (data tertua)
        }

        // Perbarui chart
        TempChart.update();
    }
}

// Fungsi untuk memperbarui nilai Temp di elemen HTML dengan satuan
function updateCurrentTempValue(TempC) {
    const currentTempValueElement = document.getElementById('CurrentTempValue');
    currentTempValueElement.textContent = `${TempC.toFixed(2)} °C`; // Tambahkan satuan Derajat
}

// Fungsi untuk memperbarui pesan suhu berdasarkan nilai suhu
function updateAnalystMessageTemp(temp) {
    const analystMessageTempElement = document.getElementById('AnalystMessageTemp');

    if (temp >= 24 && temp <= 28) {
        analystMessageTempElement.textContent = "Suhu kolam Anda saat ini sangat ideal untuk sebagian besar jenis ikan. Rentang suhu ini memungkinkan ikan untuk beraktivitas dengan optimal, nafsu makan meningkat, dan pertumbuhan berlangsung baik.";
    } else if ((temp >= 22 && temp < 24) || (temp > 28 && temp <= 30)) {
        analystMessageTempElement.textContent = "Suhu kolam Anda mulai menyimpang dari kondisi ideal. Suhu yang terlalu rendah atau terlalu tinggi dapat menyebabkan stres pada ikan, mengurangi nafsu makan, dan memperlambat pertumbuhan. Sebaiknya dilakukan pemantauan lebih lanjut dan penyesuaian suhu secara bertahap.";
    } else {
        analystMessageTempElement.textContent = "Suhu kolam Anda berada di luar rentang yang aman bagi sebagian besar jenis ikan. Kondisi ini sangat berbahaya dan dapat menyebabkan kematian pada ikan. Segera lakukan tindakan untuk menstabilkan suhu kolam.";
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

            // Cek apakah timestamp dan TempC ada langsung dalam data (bukan di dalam fields)
            if (data.timestamp && data.TempC) {
                const timestamp = data.timestamp;
                const TempC = parseFloat(data.TempC); // Parsing angka

                if (timestamp && !isNaN(TempC)) {
                    const parsedTimestamp = parseTimestamp(timestamp); // Mengonversi timestamp ke Date
                    addDataToChart(parsedTimestamp.toLocaleString(), TempC); // Ubah format timestamp agar bisa dibaca
                }
            } else {
                console.error('Data tidak lengkap di dokumen:', data);  // Log data yang tidak lengkap
            }
        });

        // Perbarui nilai Temp saat ini dengan data terakhir
        if (TempData.values.length > 0) {
            const latestTemp = TempData.values[TempData.values.length - 1];
            updateCurrentTempValue(latestTemp);
            updateAnalystMessageTemp(latestTemp); // Perbarui pesan analyst
        }

        TempChart.update(); // Perbarui grafik setelah data awal dimuat
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
                
                // Cek apakah timestamp dan TempC ada langsung dalam data (bukan di dalam fields)
                if (data.timestamp && data.TempC) {
                    const timestamp = data.timestamp;
                    const TempC = parseFloat(data.TempC);

                    if (timestamp && !isNaN(TempC)) {
                        const parsedTimestamp = parseTimestamp(timestamp); // Mengonversi timestamp ke Date
                        addDataToChart(parsedTimestamp.toLocaleString(), TempC); // Ubah format timestamp agar bisa dibaca
                        updateCurrentTempValue(TempC); // Perbarui nilai Temp di UI
                        updateAnalystMessageTemp(TempC); // Perbarui pesan analyst
                    }
                } else {
                    console.error('Data Firestore tidak lengkap:', data);  // Log data yang tidak lengkap
                }
            }
        });

        TempChart.update();  // Perbarui grafik setelah menerima data baru
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
