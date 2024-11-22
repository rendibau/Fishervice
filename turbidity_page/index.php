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
  <link rel="stylesheet" href="turbidity_page.css">
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
   <div class="turbidity">
    <h2 style="text-align: center;">
     Grafik Turbidity
    </h2>
    <div class="cards">
     <div class="card">
            <!-- Grafik interaktif -->
            <canvas id="NtuChart" width="400" height="200"></canvas>
        
            <!-- Turbidity saat ini -->
            <h3 style="font-size: 32px; font-weight: bold; color: #29b6f6; text-transform: uppercase; letter-spacing: 1px; margin-top: 20px;">
                Turbidity saat ini
            </h3>
            
            <!-- Nilai Turbidity -->
            <p id="CurrentNTUValue" style="font-size: 40px; font-weight: bold; color: white; background-color: #29b6f6; padding: 15px 30px; border-radius: 50px; display: inline-block; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); margin-top: 10px;">
                0 NTU
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
      <p id="AnalystMessage">
        Tingkat kekeruhan air kolam yang disarankan adalah 0-25 NTU. Rentang ini menjamin air tetap jernih, memungkinkan cahaya matahari menembus, dan mendukung pertumbuhan alga yang bermanfaat bagi ekosistem kolam.
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
  apiKey: "AIzaSyCbXkF8tovw4nQHFGy0bo0UAfBRbtuewhQ",
  authDomain: "ntu-page.firebaseapp.com",
  projectId: "ntu-page",
  storageBucket: "ntu-page.firebasestorage.app",
  messagingSenderId: "595402181819",
  appId: "1:595402181819:web:078a36fa978bd3c1416d9e"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Initialize Firestore
const db = firebase.firestore();

// Array lokal untuk menyimpan data NTU dan timestamp
let NtuData = {
    labels: [], // Untuk timestamp
    values: []  // Untuk nilai NTU
};

// Inisialisasi Chart.js
const NtuChartData = {
    labels: NtuData.labels, // Label timestamp
    datasets: [{
        label: 'Nilai Turbidity',
        data: NtuData.values, // Data NTU
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderWidth: 1
    }]
};

const ctx = document.getElementById('NtuChart').getContext('2d');
const NtuChart = new Chart(ctx, {
    type: 'line',
    data: NtuChartData,
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
function addDataToChart(timestamp, NTU) {
    // Cek apakah timestamp sudah ada di grafik
    if (!NtuData.labels.includes(timestamp)) {
        NtuData.labels.push(timestamp); // Tambahkan timestamp ke labels
        NtuData.values.push(NTU);   // Tambahkan nilai NTU ke values

        // Batasi jumlah data hanya 20
        if (NtuData.labels.length > 20) {
            NtuData.labels.shift(); // Hapus elemen pertama (data tertua)
            NtuData.values.shift(); // Hapus elemen pertama (data tertua)
        }

        // Perbarui chart
        NtuChart.update();
    }
}

// Fungsi untuk memperbarui nilai NTU di elemen HTML dengan satuan
function updateCurrentNTUValue(NTU) {
    const currentNTUValueElement = document.getElementById('CurrentNTUValue');
    currentNTUValueElement.textContent = `${NTU.toFixed(2)} NTU`; // Tambahkan satuan NTU
}

// Fungsi untuk memperbarui pesan Analyst berdasarkan nilai NTU
function updateAnalystMessage(NTU) {
    const analystMessageElement = document.getElementById('AnalystMessage');

    if (NTU < 25) {
        analystMessageElement.textContent = "Kekeruhan kolam Anda saat ini berada pada tingkat yang ideal. Kondisi air yang jernih mendukung pertumbuhan ikan yang sehat dan meminimalkan risiko penyakit.";
    } else if (NTU >= 25 && NTU <= 50) {
        analystMessageElement.textContent = "Kekeruhan kolam Anda mulai meningkat dan perlu dipantau lebih lanjut. Sebaiknya lakukan pemeriksaan lebih lanjut untuk mengetahui penyebab kekeruhan dan segera ambil tindakan perbaikan seperti membersihkan filter atau mengganti sebagian air.";
    } else if (NTU > 50) {
        analystMessageElement.textContent = "Kekeruhan kolam Anda sangat tinggi dan dapat membahayakan kesehatan ikan. Kondisi ini mengindikasikan adanya masalah serius seperti kelebihan pakan, pertumbuhan alga yang berlebihan, atau adanya bahan organik yang membusuk. Segera lakukan tindakan perbaikan menyeluruh seperti membersihkan kolam, mengganti sebagian besar air, dan memeriksa sistem filtrasi.";
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

            // Cek apakah timestamp dan NTU ada langsung dalam data (bukan di dalam fields)
            if (data.timestamp && data.NTU) {
                const timestamp = data.timestamp;
                const NTU = parseFloat(data.NTU); // Parsing angka

                if (timestamp && !isNaN(NTU)) {
                    const parsedTimestamp = parseTimestamp(timestamp); // Mengonversi timestamp ke Date
                    addDataToChart(parsedTimestamp.toLocaleString(), NTU); // Ubah format timestamp agar bisa dibaca
                }
            } else {
                console.error('Data tidak lengkap di dokumen:', data);  // Log data yang tidak lengkap
            }
        });

        // Perbarui nilai NTU saat ini dengan data terakhir
        if (NtuData.values.length > 0) {
            const latestNTU = NtuData.values[NtuData.values.length - 1];
            updateCurrentNTUValue(latestNTU);
            updateAnalystMessage(latestNTU); // Perbarui pesan analyst
        }

        NtuChart.update(); // Perbarui grafik setelah data awal dimuat
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
                
                // Cek apakah timestamp dan NTU ada langsung dalam data (bukan di dalam fields)
                if (data.timestamp && data.NTU) {
                    const timestamp = data.timestamp;
                    const NTU = parseFloat(data.NTU);

                    if (timestamp && !isNaN(NTU)) {
                        const parsedTimestamp = parseTimestamp(timestamp); // Mengonversi timestamp ke Date
                        addDataToChart(parsedTimestamp.toLocaleString(), NTU); // Ubah format timestamp agar bisa dibaca
                        updateCurrentNTUValue(NTU); // Perbarui nilai NTU di UI
                        updateAnalystMessage(NTU); // Perbarui pesan analyst
                    }
                } else {
                    console.error('Data Firestore tidak lengkap:', data);  // Log data yang tidak lengkap
                }
            }
        });

        NtuChart.update();  // Perbarui grafik setelah menerima data baru
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