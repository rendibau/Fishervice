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
            <canvas id="TurbidityChart" width="400" height="200"></canvas>
        
            <!-- Turbidity saat ini -->
            <h3 style="font-size: 32px; font-weight: bold; color: #29b6f6; text-transform: uppercase; letter-spacing: 1px; margin-top: 20px;">
                Turbidity saat ini
            </h3>
            
            <!-- Nilai Turbidity -->
            <p style="font-size: 40px; font-weight: bold; color: white; background-color: #29b6f6; padding: 15px 30px; border-radius: 50px; display: inline-block; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); margin-top: 10px;">
                50 NTU
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
      <p>
        Kekeruhan air yang dianjurkan maksimum 50 NTU
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
   <script src="turbidity_page.js"></script>
 </body>
</html>