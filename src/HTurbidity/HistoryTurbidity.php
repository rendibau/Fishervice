<?php
session_start();

// Ambil data dari sessionStorage menggunakan JavaScript
if (isset($_SESSION['fullname']) && isset($_SESSION['email'])) {
    $fullname = $_SESSION['fullname'];
    $email = $_SESSION['email'];
    // Proses data lebih lanjut
} else {
    // Redirect ke halaman login jika data tidak ada
    header('Location: ../signin.html');
    exit();
}
?>
<html>
<head>
    <title>Fishervice - History Turbidity</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="HistoryTurbidity.css">
  <!-- Firebase SDK for older versions (if not using ES modules) -->
  <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-firestore.js"></script>

   
</head>
<body>
    <div class="main-content">
        <!-- Background video -->
        <video autoplay muted loop>
            <source src="../background/Dashboard Fishervice.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div class="content">
            <div class="header">
                <h1>
                    <a href="javascript:void(0);" onclick="saveAndRedirect('../dashboardlogin.php')" style="color: black; text-decoration: none;">
                        <i class="fas fa-arrow-left"></i>
                        <span class="back-text">Back</span>
                    </a>
                    <img alt="Fishervice Logo" src="https://gcdnb.pbrd.co/images/zFDCP4zQaltQ.png?o=1" />
                    Fishervice
                </h1>
                <div class="user" style="white-space: nowrap;">
                    Hi, <?php echo htmlspecialchars($fullname); ?>!
                </div>
            </div>

            <!-- Search bar untuk mencari data berdasarkan tanggal -->
            <div class="search-bar" style="text-align: center; margin: 20px 0;">
                <input type="text" id="searchDate" placeholder="Cari berdasarkan tanggal (yyyy-mm-dd)" style="padding: 10px; font-size: 16px; width: 250px;">
                <button onclick="searchByDate()" style="padding: 10px; font-size: 16px;">Cari</button>
            </div>

            <div class="pH">
                <h2 style="text-align: center; font-size: 20px;">History Turbidity</h2>
                <div class="cards">
                    <div class="card">
                        <!-- Tabel History -->
                        <div class="history">
                            <table style="width: 100%; border-collapse: collapse; text-align: center; background-color: #ffffff; margin: 20px 0; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                                <thead style="background-color: #29b6f6; color: white;">
                                    <tr>
                                        <th style="padding: 10px; border: 1px solid #ddd;">Tanggal</th>
                                        <th style="padding: 10px; border: 1px solid #ddd;">Waktu</th>
                                        <th style="padding: 10px; border: 1px solid #ddd;">Data Turbidity</th>
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
  apiKey: "AIzaSyA7LZUxNcMybJmrlndExNXHZhlO2Yy1yAo",
  authDomain: "ntu-container.firebaseapp.com",
  projectId: "ntu-container",
  storageBucket: "ntu-container.firebasestorage.app",
  messagingSenderId: "307669121895",
  appId: "1:307669121895:web:a1b0c2e8ae49104fa3cdc2"
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
                        <td style="padding: 10px; border: 1px solid #ddd;">${data.NTU.toFixed(2)}</td>
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

        function saveAndRedirect(url) {
            var fullname = '<?php echo $_SESSION['fullname']; ?>';
            var email = '<?php echo $_SESSION['email']; ?>';

            if (fullname && email) {
                sessionStorage.setItem('fullname', fullname);
                sessionStorage.setItem('email', email);
                window.location.href = url;
            } else {
                window.location.href = 'signin.html';
            }
        }
    </script>
</body>
</html>
