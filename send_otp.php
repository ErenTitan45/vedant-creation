<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "ladki_bahin_yojana";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$mobile_no = $data['mobile_no'];

$stmt = $conn->prepare("SELECT id FROM users WHERE mobile_no = ?");
$stmt->bind_param("s", $mobile_no);
$stmt->execute();
$stmt->store_result();

$response = [];

if ($stmt->num_rows > 0) {
    $otp = rand(100000, 999999);
    $otp_expiration = date("Y-m-d H:i:s", strtotime('+10 minutes'));

    $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiration = ? WHERE mobile_no = ?");
    $stmt->bind_param("sss", $otp, $otp_expiration, $mobile_no);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['otp'] = $otp;
    } else {
        $response['success'] = false;
        $response['message'] = 'OTP पाठवण्यात अडचण आली.';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'हा मोबाईल क्रमांक आमच्या नोंदीत नाही.';
}

$stmt->close();
$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>
