<?php
session_start(); // Mulai session
session_destroy(); // Hapus semua data session

// Redirect ke halaman login setelah logout
header('Location: index.html');
exit();
?>
