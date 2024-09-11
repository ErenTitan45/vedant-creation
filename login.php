<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "ladki_bahin_yojana";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile_no = $_POST['mobile_no'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE mobile_no = ?");
    $stmt->bind_param("s", $mobile_no);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            echo "Login successful. Redirecting to dashboard...";
            header("refresh:2;url=dashboard.php"); // Redirect after 2 seconds
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that mobile number.";
    }

    $stmt->close();
}

$conn->close();
?>
