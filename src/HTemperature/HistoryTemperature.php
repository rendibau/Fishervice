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
  <title>
   Fishervice
  </title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="HistoryTemperature.css">
 </head>
 <body>
    <div class="main-content">
        <!-- Ganti background video dari link eksternal ke video lokal -->
        <video autoplay muted loop>
            <source src="../background/Dashboard Fishervice.mp4" type="video/mp4">
            Your browser does not support the video tag.
    </video>

  <div class="content">
   <div class="header">
    <h1>
    <a href="javascript:void(0);" onclick="saveAndRedirect('../dashboardlogin.php')" style="color: black; text-decoration: none;">
     <i class="fas fa-arrow-left"></i>
     <span class="back-text">Back</span> </a>
     <img alt="Fishervice Logo" src="https://gcdnb.pbrd.co/images/zFDCP4zQaltQ.png?o=1"/>
     Fishervice
    </h1>
    <div class="user" style="white-space: nowrap;"> 
     Hi, <?php echo htmlspecialchars($fullname); ?>!</h1>
    </div>
   </div>
   <div class="pH">
    <h2 style="text-align: center; font-size: 20px;">
     History Temperature
    </h2>
    <div class="cards">
        <div class="card">
          <!-- Tabel History -->
<div class="history">
    <table style="width: 100%; border-collapse: collapse; text-align: center; background-color: #ffffff; margin: 20px 0; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
        <thead style="background-color: #29b6f6; color: white;">
            <tr>
                <th style="padding: 10px; border: 1px solid #ddd;">Tanggal</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Waktu</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Data Temperature</th>
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
    // Data dummy untuk history
    const historyData = [
        { tanggal: '2024-11-01', waktu: '08:00', pH: 7.5 },
        { tanggal: '2024-11-02', waktu: '12:00', pH: 7.8 },
        { tanggal: '2024-11-03', waktu: '15:30', pH: 8.0 },
        { tanggal: '2024-11-04', waktu: '10:00', pH: 8.5 },
    ];

    // Referensi ke tbody tabel
    const historyBody = document.getElementById('historyBody');

    // Tambahkan data ke tabel
    historyData.forEach(data => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td style="padding: 10px; border: 1px solid #ddd;">${data.tanggal}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">${data.waktu}</td>
            <td style="padding: 10px; border: 1px solid #ddd;">${data.pH}</td>
        `;
        historyBody.appendChild(row);
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