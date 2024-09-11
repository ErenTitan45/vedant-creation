<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ladki_bahin_yojana";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $mobile_no = $_POST['mobile_no'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $district = $_POST['district'];
    $taluka = $_POST['taluka'];
    $village = $_POST['village'];
    $municipal_corporation = $_POST['municipal_corporation'];
    $authorized_person = $_POST['authorized_person'];

    $checkStmt = $conn->prepare("SELECT id FROM users WHERE mobile_no = ?");
    $checkStmt->bind_param("s", $mobile_no);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "This mobile number is already registered. Please use a different mobile number or log in.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, mobile_no, password, district, taluka, village, municipal_corporation, authorized_person) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $full_name, $mobile_no, $password, $district, $taluka, $village, $municipal_corporation, $authorized_person);

        if ($stmt->execute()) {
            header("Location: login.html");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $checkStmt->close();
}

$conn->close();
?>
