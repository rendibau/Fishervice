<?php
// Koneksi ke database
$servername = "mysql_db";
$username = "root"; // sesuaikan dengan username MySQL
$password = "root"; // sesuaikan dengan password MySQL
$dbname = "fishervice"; // sesuaikan dengan nama database

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Query untuk mengambil pengguna berdasarkan email
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Ambil data pengguna
    $row = $result->fetch_assoc();
    
    // Verifikasi password
    if (password_verify($password, $row['password'])) {
        // Login berhasil
        session_start();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['fullname'] = $row['fullname'];
        $_SESSION['email'] = $row['email']; // Simpan email di session
        header("Location: ../dashboardlogin.php"); // arahkan ke halaman dashboard setelah login
        exit();
    } else {
        // Password salah
        $error = "wrong_password";
    }
} else {
    // Email tidak ditemukan
    $error = "email_not_found";
}
// Redirect kembali ke halaman login dengan pesan error
header("Location: signin.html?error=" . $error);
$conn->close();
?>
