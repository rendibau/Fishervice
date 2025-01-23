<?php
session_start();
$servername = "mysql_db";
$username = "root";
$password = "root";
$dbname = "fishervice";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $new_password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    if ($new_password === $cpassword) {
        $query = "SELECT * FROM users WHERE reset_token = '$token' AND reset_token_expiry > NOW()";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_password = "UPDATE users SET password = '$hashed_password', reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = '$token'";
            if (mysqli_query($conn, $update_password)) {
                $_SESSION['info'] = "Your password has been successfully updated.";
                echo "
                <html>
                <head>
                    <title>Password Updated</title>
                    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css' rel='stylesheet'>
                    <style>
                        body {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            background-color: #e0f7fa;
                            font-family: 'Arial', sans-serif;
                            margin: 0;
                        }
                        .message-container {
                            text-align: center;
                            background: #ffffff;
                            padding: 30px;
                            border-radius: 10px;
                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                        }
                        .message-container h2 {
                            color: #1e88e5;
                            margin-bottom: 15px;
                        }
                        .message-container p {
                            color: #555;
                            font-size: 16px;
                        }
                        .message-container .icon {
                            font-size: 50px;
                            color: #29b6f6;
                            margin-bottom: 15px;
                        }
                    </style>
                    <script>
                        var countdown = 5;
                        function updateCountdown() {
                            if (countdown > 0) {
                                document.getElementById('countdown').textContent = countdown + ' detik';
                                countdown--;
                            } else {
                                window.location.href = 'signin.html';
                            }
                        }
                        setInterval(updateCountdown, 1000);
                    </script>
                </head>
                <body>
                    <div class='message-container'>
                        <i class='fas fa-check-circle icon'></i>
                        <h2>Password berhasil diperbarui!</h2>
                        <p>Anda akan diarahkan ke halaman login dalam <span id='countdown'>5 detik</span>...</p>
                    </div>
                </body>
                </html>
                ";
                exit;
            } else {
                echo "Error updating password: " . $conn->error;
            }
        } else {
            echo "Invalid or expired token!";
        }
    } else {
        echo "Passwords do not match!";
    }
}

$conn->close();
?>
