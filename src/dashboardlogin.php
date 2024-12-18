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
    <button id="toggleSidebar" class="toggle-btn">
        ☰
    </button>
    <div class="sidebar">
        <h2>
            Menu
        </h2>
        <ul>
            <li><a class="active" href="#result"><i class="fas fa-chart-line"></i>Result</a></li>
            <li><a href="#analyst"><i class="fas fa-chart-pie"></i>Analyst</a></li>
            <li><a href="#history"><i class="fas fa-history"></i> History</a></li>
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
            <div class="logo-container">
                <img src="https://gcdnb.pbrd.co/images/zFDCP4zQaltQ.png?o=1" alt="Logo" width="30" height="30">
                <span><strong>Fishervice</strong></span>
        </div>
            <div class="profile">
                <span>
                <strong>Hi, <?php echo htmlspecialchars($_SESSION['fullname']); ?></strong>
                </span>
            </div>
        </div>
<section id="result">
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
</section>
<section id="analyst">
         <!-- Our Product section -->
         <div id="analyst" class="our-product">
            <h2>Analyst</h2>
            <div class="product-cards">
                <div class="product-card">
                    <button id="sendDataBtn1" class="no-link-style" style="color: black; background: none; border: none; cursor: pointer;">
                        <img alt="Product 1" height="150" src="https://gcdnb.pbrd.co/images/8O9GGTtZRurR.png?o=1" width="300"/>
                        <h3 style="color: black;">Temperature</h3>
                        <p style="color: black;">Memantau suhu air kolam ikan secara berkala sangat penting untuk menjaga kesehatan ikan dan kualitas air kolam.</p>
                    </button>
                </div>
                <div class="product-card">
                    <button id="sendDataBtn2" class="no-link-style" style="color: black; background: none; border: none; cursor: pointer;">
                        <img alt="Product 2" height="150" src="https://gcdnb.pbrd.co/images/ADmtwJGKbl9h.png?o=1" width="300"/>
                        <h3 style="color: black;">pH</h3>
                        <p style="color: black;">Memantau nilai pH air kolam ikan secara berkala sangat penting untuk menjaga kesehatan ikan dan kualitas air kolam.</p>
                    </button>
                </div>
                <div class="product-card">
                    <button id="sendDataBtn3" class="no-link-style" style="color: black; background: none; border: none; cursor: pointer;">
                        <img alt="Product 3" height="150" src="https://gcdnb.pbrd.co/images/KY2XbUTnBj3f.png?o=1" width="300"/>
                        <h3 style="color: black;">Turbidity</h3>
                        <p style="color: black;">Memantau nilai kekeruhan air kolam ikan secara berkala sangat penting untuk menjaga kesehatan ikan dan kualitas air kolam.</p>
                    </button>
                </div>
            </div>
        </div>
        <section id="history">
    <!-- analyst section -->
    <div id="history" class="analyst-box">
        <h2>History</h2>
        <div class="analyst-cards">
            <!-- Link to Temperature page -->
            <div class="analyst-card">
                <a href="javascript:void(0);" onclick="saveAndRedirect('HTemperature/HistoryTemperature.php')">
                    <h3>Temperature</h3>
                </a>
            </div>
            <!-- Link to pH page -->
            <div class="analyst-card">
                <a href="javascript:void(0);" onclick="saveAndRedirect('HPh/HistoryPh.php')">
                    <h3>pH</h3>
                </a>
            </div>
            <!-- Link to Turbidity page -->
            <div class="analyst-card">
                <a href="javascript:void(0);" onclick="saveAndRedirect('HTurbidity/HistoryTurbidity.php')">
                    <h3>Turbidity</h3>
                </a>
            </div>
        </div>
    </div>
</section>
        <div id="contact" class="footer">
            <h2>Contact Information</h2>
            <p><i class="fas fa-envelope"></i> Email: Fishervice4@gmail.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Location: Telkom University, Bandung, Jawa Barat</p>
            <p><i class="fas fa-phone"></i> Phone: 089520701494 (Muhammad Hasbi Nurhadi)</p>
        </div>
    </div>
</section>
    
    <div class="main-content">
        <!-- Konten halaman utama -->
    </div>

    <script>
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    const mainContent = document.querySelector('.main-content');

    toggleBtn.addEventListener('click', () => {
        // Toggle kelas 'active' pada sidebar
        sidebar.classList.toggle('active');

        // Pindahkan tombol ke sisi lain saat sidebar terbuka
        if (sidebar.classList.contains('active')) {
            toggleBtn.style.left = '270px'; // Sesuaikan dengan lebar sidebar
        } else {
            toggleBtn.style.left = '20px';
        }
        // Geser main content
        if (sidebar.classList.contains('active')) {
            mainContent.style.marginLeft = '250px'; // Sesuaikan dengan lebar sidebar
        } else {
            mainContent.style.marginLeft = '0';
        }
    });
    
    // Ambil email dari PHP session
    const userEmail = '<?php echo $_SESSION['email']; ?>';

    // Firebase configuration (isi dengan konfigurasi Firebase Anda)
    const firebaseConfig = {
        apiKey: "AIzaSyCHQZyc8WbHVmuRiE0Z-V2icYScgRhjKck",
        authDomain: "fishervice-result.firebaseapp.com",
        projectId: "fishervice-result",
        storageBucket: "fishervice-result.appspot.com",
        messagingSenderId: "841310454525",
        appId: "1:841310454525:web:95980a6dfadda9120cf494"
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
    // JavaScript untuk menambahkan kelas 'active' ke item sidebar yang sesuai saat scroll
    document.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('section');
        const menuItems = document.querySelectorAll('.sidebar ul li a');

        let currentSection = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop - 50;
            if (pageYOffset >= sectionTop) {
                currentSection = section.getAttribute('id');
            }
        });

        menuItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('href') === '#' + currentSection) {
                item.classList.add('active');
            }
        });
    });
// Event listener untuk tombol pertama
document.getElementById('sendDataBtn1').addEventListener('click', function() {
    fetch('http://13.236.116.101:8006/receive_user_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'fullname': '<?php echo $_SESSION['fullname']; ?>',
            'email': '<?php echo $_SESSION['email']; ?>'
        })
    })
    .then(response => {
        console.log("Response Status:", response.status);
        console.log("Response Headers:", response.headers);
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text(); // Mengambil respons sebagai teks
    })
    .then(text => {
        console.log("Raw response:", text); // Tambahkan log ini
        try {
            const data = JSON.parse(text); // Coba parse respons sebagai JSON
            console.log("Received data:", data);
            if (data.status === 'success') {
                window.location.href = 'http://13.236.116.101:8006/index.php'; // Arahkan ke halaman utama
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
            alert('Ada kesalahan saat memproses respons dari server.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ada kesalahan saat mengirim data. Silakan coba lagi.');
    });

    console.log("Sending data:", {
        fullname: '<?php echo $_SESSION['fullname']; ?>',
        email: '<?php echo $_SESSION['email']; ?>'
    });
});

// Event listener untuk tombol kedua
document.getElementById('sendDataBtn2').addEventListener('click', function() {
    fetch('http://13.236.116.101:8004/receive_user_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'fullname': '<?php echo $_SESSION['fullname']; ?>',
            'email': '<?php echo $_SESSION['email']; ?>'
        })
    })
    .then(response => {
        console.log("Response Status:", response.status);
        console.log("Response Headers:", response.headers);
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text(); // Mengambil respons sebagai teks
    })
    .then(text => {
        console.log("Raw response:", text);
        try {
            const data = JSON.parse(text);
            console.log("Received data:", data);
            if (data.status === 'success') {
                window.location.href = 'http://13.236.116.101:8004/index.php'; // Arahkan ke halaman utama
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
            alert('Ada kesalahan saat memproses respons dari server.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ada kesalahan saat mengirim data. Silakan coba lagi.');
    });

    console.log("Sending data:", {
        fullname: '<?php echo $_SESSION['fullname']; ?>',
        email: '<?php echo $_SESSION['email']; ?>'
    });
});

// Event listener untuk tombol ketiga
document.getElementById('sendDataBtn3').addEventListener('click', function() {
    fetch('http://13.236.116.101:8005/receive_user_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'fullname': '<?php echo $_SESSION['fullname']; ?>',
            'email': '<?php echo $_SESSION['email']; ?>'
        })
    })
    .then(response => {
        console.log("Response Status:", response.status);
        console.log("Response Headers:", response.headers);
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.text(); // Mengambil respons sebagai teks
    })
    .then(text => {
        console.log("Raw response:", text);
        try {
            const data = JSON.parse(text);
            console.log("Received data:", data);
            if (data.status === 'success') {
                window.location.href = 'http://13.236.116.101:8005/index.php'; // Arahkan ke halaman utama
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
            alert('Ada kesalahan saat memproses respons dari server.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ada kesalahan saat mengirim data. Silakan coba lagi.');
    });

    console.log("Sending data:", {
        fullname: '<?php echo $_SESSION['fullname']; ?>',
        email: '<?php echo $_SESSION['email']; ?>'
    });
});

function saveAndRedirect(url) {
    // Ambil data dari PHP session
    var fullname = '<?php echo $_SESSION['fullname']; ?>';
    var email = '<?php echo $_SESSION['email']; ?>';

    // Cek apakah data ada
    if (fullname && email) {
        // Menyimpan data di sessionStorage
        sessionStorage.setItem('fullname', fullname);
        sessionStorage.setItem('email', email);

        // Redirect ke halaman tujuan
        window.location.href = url;
    } else {
        // Jika data tidak ada, arahkan ke halaman login
        window.location.href = 'signin.html';
    }
}

</script>
</body>
</html>