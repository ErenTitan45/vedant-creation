<?php
session_start();
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "ladki_bahin_yojana";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$entered_otp = $data['otp'];
$mobile_no = $_SESSION['mobile_no'];

$stmt = $conn->prepare("SELECT otp, otp_expiration FROM users WHERE mobile_no = ?");
$stmt->bind_param("s", $mobile_no);
$stmt->execute();
$stmt->bind_result($stored_otp, $otp_expiration);
$stmt->fetch();
$stmt->close();

$current_time = date("Y-m-d H:i:s");

if ($entered_otp === $stored_otp && $current_time <= $otp_expiration) {
    $_SESSION['otp_verified'] = true;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'OTP अवैध आहे किंवा कालबाह्य झाला आहे.']);
}

$conn->close();
?>
