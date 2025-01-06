<?php
// Koneksi ke server MySQL
$servername = "mysql_db";
$username = "root"; // sesuaikan dengan username MySQL
$password = "root"; // sesuaikan dengan password MySQL
$dbname = "fishervice"; // sesuaikan dengan nama database

// Koneksi ke MySQL tanpa memilih database
$conn = new mysqli($servername, $username, $password);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Buat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error saat membuat database: " . $conn->error);
}

// Pilih database yang telah dibuat
$conn->select_db($dbname);

// Buat tabel `users` jika belum ada
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT,
    fullname VARCHAR(100) NOT NULL,
    phone VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB";
if ($conn->query($sql) !== TRUE) {
    die("Error saat membuat tabel: " . $conn->error);
}

// Tambahkan kolom `reset_token` dan `reset_token_expiry` jika belum ada
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'reset_token'");
if ($result->num_rows === 0) {
    $sql = "ALTER TABLE users 
            ADD COLUMN reset_token VARCHAR(255) NULL, 
            ADD COLUMN reset_token_expiry DATETIME NULL";
    if ($conn->query($sql) !== TRUE) {
        die("Error saat menambahkan kolom: " . $conn->error);
    }
}

// Ambil data dari form
$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$conpassword = mysqli_real_escape_string($conn, $_POST['conpassword']);

// Cek apakah email sudah digunakan
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Jika email sudah ada, redirect dengan pesan error
    header('Location: signup.html?error=email_exists');
    exit();
} else {

// Validasi password
if ($password === $conpassword) {
    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Query untuk memasukkan data
    $sql = "INSERT INTO users (fullname, phone, email, password)
            VALUES ('$fullname', '$phone', '$email', '$hashed_password')";
    
    if ($conn->query($sql) === TRUE) {
        // Pendaftaran berhasil
        header('Location: ../signin.html'); // Ganti dengan nama file HTML Anda
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Jika password tidak cocok, redirect dengan pesan error
    header('Location: signup.html?error=password_mismatch');
    exit();
}
}

$conn->close();
?>