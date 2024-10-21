<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['fullname'])) {
    header('Location: signin.html'); // Jika belum login, redirect ke halaman login
    exit();
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <!-- Firebase SDK for older versions (if not using ES modules) -->
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-firestore.js"></script>

</head>
<body>
    <div class="sidebar">
        <h2>
            <img alt="Logo" height="30" src="https://gcdnb.pbrd.co/images/zFDCP4zQaltQ.png?o=1" width="30"/>
            Fishervice
        </h2>
        <ul>
            <li>
                <a class="active" href="#result">
                    <i class="fas fa-chart-line"></i>
                    Result
                </a>
            </li>
            <li>
                <a href="#analyst">
                    <i class="fas fa-chart-pie"></i>
                    Analyst
                </a>
            </li>
            <li>
                <a href="#history">
                    <i class="fas fa-history"></i>
                    History
                </a>
            </li>
            <li>
                <a href="#contact">
                    <i class="fas fa-address-book"></i>
                    Contact Information
                </a>
            </li>
        </ul>
        <div class="logout">
            <a href="logout.php">
             <i class="fas fa-sign-out-alt"></i>
             Log Out
            </a>
        </div>
    </div>
    <div class="main-content">
        <!-- Ganti background video dari link eksternal ke video lokal -->
        <video autoplay muted loop>
            <source src="background/Dashboard Fishervice.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="header">
            <h1></h1>
            <div class="profile">
                <span>
                <strong>Hi, <?php echo htmlspecialchars($_SESSION['fullname']); ?></strong>
                </span>
            </div>
        </div>
        
        <!-- analyst section -->
        <div id="result" class="analyst-box">
            <h2>Result</h2>
            <div class="analyst-cards">
                <div class="analyst-card">
                    <i class="fas fa-thermometer-half"></i>
                    <h3>Temperature</h3>
                    <p>0°C</p>
                </div>
                <div class="analyst-card">
                    <i class="fas fa-vial"></i>
                    <h3>pH</h3>
                    <p>0</p>
                </div>
                <div class="analyst-card">
                    <i class="fas fa-water"></i>
                    <h3>Turbidity</h3>
                    <p>0 NTU</p>
                </div>
            </div>
        </div>

         <!-- Our Product section -->
         <div id="analyst" class="our-product">
            <h2>Analyst</h2>
            <div class="product-cards">
                <div class="product-card">
                    <a href="http://temperature_page:80/index.html" class="no-link-style" style="color: black; text-decoration: none;">
                    <img alt="Product 1" height="150" src="https://gcdnb.pbrd.co/images/8O9GGTtZRurR.png?o=1" width="300"/>
                    <h3 style="color: black;">Temperature</h3>
                    <p style="color: black;">Our monitoring tools provide highly accurate data to ensure optimal conditions for fish growth.</p>
                    </a>
                </div>
                <div class="product-card">
                    <a href="./ph_page/index.html" class="no-link-style" style="color: black; text-decoration: none;">
                    <img alt="Product 2" height="150" src="https://gcdnb.pbrd.co/images/ADmtwJGKbl9h.png?o=1" width="300"/>
                    <h3 style="color: black;">pH</h3>
                    <p style="color: black;">Monitor your fish pond conditions in real-time to make immediate adjustments as needed.</p>
                    </a>
                </div>
                <div class="product-card">
                    <a href="./turbidity_page/index.html" class="no-link-style" style="color: black; text-decoration: none;">
                    <img alt="Product 3" height="150" src="https://gcdnb.pbrd.co/images/KY2XbUTnBj3f.png?o=1" width="300"/>
                    <h3 style="color: black;">Turbidity</h3>
                    <p style="color: black;">Our tools are designed to be user-friendly, making it easy for anyone to monitor their fish pond.</p>
                    </a>
                </div>
            </div>
        </div>

        <div id="contact" class="footer">
            <h2>Contact Information</h2>
            <p><i class="fas fa-envelope"></i> Email: Fishervice4@gmail.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Location: Telkom University, Bandung, Jawa Barat</p>
            <p><i class="fas fa-phone"></i> Phone: 089520701494 (Muhammad Hasbi Nurhadi)</p>
        </div>
    </div>
    <div class="sidebar">
        <h2>Fishervice</h2>
        <ul>
            <li><a class="active" href="#result"><i class="fas fa-chart-line"></i> Result</a></li>
            <li><a href="#analyst"><i class="fas fa-chart-pie"></i> Analyst</a></li>
            <li><a href="#history"><i class="fas fa-history"></i> History</a></li>
            <li><a href="#contact"><i class="fas fa-address-book"></i> Contact</a></li>
        </ul>
    </div>

    <div class="main-content">
        <!-- Konten halaman utama -->
    </div>

    <script>
    // Ambil email dari PHP session
    const userEmail = '<?php echo $_SESSION['email']; ?>';

    // Firebase configuration (isi dengan konfigurasi Firebase Anda)
    const firebaseConfig = {
        apiKey: "AIzaSyAbGSUAOW6UTOGlZa1RB5I_WGTVAH5yyHw",
        authDomain: "coba-35774.firebaseapp.com",
        projectId: "coba-35774",
        storageBucket: "coba-35774.appspot.com",
        messagingSenderId: "163958562627",
        appId: "1:163958562627:web:fcc939a487bc09de4a59f6"
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    // Initialize Firestore
    const db = firebase.firestore();

    // Mendapatkan data secara real-time dari Firestore berdasarkan email pengguna
    db.collection(userEmail).doc("latestData")
      .onSnapshot((doc) => {
        if (doc.exists) {
            const data = doc.data();

            // Update nilai di HTML
            document.querySelector(".analyst-card:nth-child(1) p").textContent = `${data.tempC}°C`;
            document.querySelector(".analyst-card:nth-child(2) p").textContent = `${data.pHValue}`;
            document.querySelector(".analyst-card:nth-child(3) p").textContent = `${data.ntu} NTU`;
        } else {
            console.log("No such document!");
        }
      });

    // JavaScript untuk menambahkan kelas 'active' ke item sidebar yang diklik
    const menuItems = document.querySelectorAll('.sidebar ul li a');

    menuItems.forEach(item => {
    item.addEventListener('click', function() {
        // Hapus kelas 'active' dari semua item
        menuItems.forEach(link => link.classList.remove('active'));
        
        // Tambahkan kelas 'active' ke item yang diklik
        this.classList.add('active');
    });
});
</script>
</body>
</html>