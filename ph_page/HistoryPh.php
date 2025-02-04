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
    <title>Fishervice - History pH</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="HistoryPh.css">
  <!-- Firebase SDK for older versions (if not using ES modules) -->
  <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-firestore.js"></script>

   
</head>
<body>
    <div class="main-content">
        <!-- Background video -->
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
                    <img alt="Fishervice Logo" src="https://iili.io/2Z190Cl.png" />
                    Fishervice
                </h1>
                <div class="user">
                    Hi, <?php echo htmlspecialchars($fullname); ?>!
                </div>
            </div>

            <!-- Search bar untuk mencari data berdasarkan tanggal -->
            <div class="search-bar">
        <input type="text" id="searchDate" placeholder="Cari berdasarkan tanggal (yyyy-mm-dd)">
        <button onclick="searchByDate()">Cari</button>
    </div>

    <div class="pH">
        <h2>History pH</h2>
        <div class="cards">
            <div class="card">
                <div class="history">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Data pH</th>
                            </tr>
                        </thead>
                        <tbody id="historyBody">
                            <!-- Data akan ditambahkan secara dinamis menggunakan JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <div class="footer">
            <p>
                <i class="fas fa-envelope"></i>
                Email: Fishervice4@gmail.com
            </p>
            <p>
                <i class="fas fa-map-marker-alt"></i>
                Location: Telkom University, Bandung, Jawa Barat
            </p>
            <p>
                <i class="fas fa-phone"></i>
                Phone: 089520701494 (Muhammad Hasbi Nurhadi)
            </p>
        </div>
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

        // Inisialisasi Firebase
        const app = firebase.initializeApp(firebaseConfig);
        const db = firebase.firestore(app);

        // Referensi ke tbody tabel
        const historyBody = document.getElementById('historyBody');

        // Fungsi untuk mengambil data dari Firestore dengan limit 100 data
        async function loadHistoryData(dateFilter = null) {
            try {
                let query = db.collection(userEmail).orderBy("timestamp", "desc").limit(144);

                if (dateFilter) {
                    query = query.where("timestamp", ">=", dateFilter + "-00-00-00").where("timestamp", "<", dateFilter + "-23-59-59");
                }

                const snapshot = await query.get();

                // Kosongkan tbody sebelum menambah data baru
                historyBody.innerHTML = "";

                snapshot.forEach(doc => {
                    const data = doc.data();
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td style="padding: 10px; border: 1px solid #ddd;">${formatDate(data.timestamp)}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${formatTime(data.timestamp)}</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">${data.pHValue.toFixed(2)}</td>
                    `;
                    historyBody.appendChild(row);
                });
            } catch (error) {
                console.error("Error loading Firestore data:", error);
            }
        }

        // Fungsi format tanggal
        function formatDate(timestamp) {
            const [year, month, day] = timestamp.split('-').slice(0, 3);
            return `${year}-${month}-${day}`;
        }

        // Fungsi format waktu
        function formatTime(timestamp) {
            const [hour, minute, second] = timestamp.split('-').slice(3);
            return `${hour}:${minute}:${second}`;
        }

        // Fungsi untuk mencari berdasarkan tanggal
        function searchByDate() {
            const searchDate = document.getElementById('searchDate').value;
            if (searchDate) {
                loadHistoryData(searchDate); // Panggil fungsi dengan filter tanggal
            }
        }

        // Panggil fungsi untuk memuat data saat halaman dimuat
        loadHistoryData();

        document.getElementById('backButton').addEventListener('click', function() {
        console.log('Tombol Back diklik'); // Log ini untuk debugging
        window.location.href = 'http://localhost:8002/dashboardlogin.php'; // URL yang sesuai
    });
    </script>
</body>
</html>
