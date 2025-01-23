<?php
session_start();

// Koneksi ke database
$servername = "mysql_db";
$username = "root";
$password = "root";
$dbname = "fishervice";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Periksa apakah token ada di URL
if (!isset($_GET['token'])) {
    die("Invalid or missing token.");
}

$token = $_GET['token'];

// Validasi token di database
$query = "SELECT * FROM users WHERE reset_token = '$token' AND reset_token_expiry > NOW()";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    // Token tidak valid
    die("Invalid or expired token.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fishervice - Reset Password</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e0f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('background/Dashboard%20Fishervice.mp4') no-repeat center center fixed;
            background-size: cover;
        }
        .reset-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .reset-container h2 {
            color: #1e88e5;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .reset-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .reset-container label {
            font-size: 14px;
            color: #555;
            text-align: left;
        }
        .reset-container input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .reset-container button {
            background-color: #1e88e5;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .reset-container button:hover {
            background-color: #1565c0;
        }
        .reset-container .back-link {
            display: block;
            margin-top: 10px;
            color: #555;
            font-size: 14px;
            text-decoration: none;
        }
        .reset-container .back-link:hover {
            color: #1e88e5;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Your Password</h2>
        <form method="POST" action="reset_password.php">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="cpassword">Confirm Password:</label>
            <input type="password" id="cpassword" name="cpassword" required>
            <button type="submit">Reset Password</button>
        </form>
        <a class="back-link" href="signin.html"><i class="fas fa-arrow-left"></i> Back to Sign In</a>
    </div>
</body>
</html>
