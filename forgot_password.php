<?php
session_start();
$servername = "localhost";
$username = "root";
$password = ""; // Your MySQL password
$dbname = "ladki_bahin_yojana";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $mobile_no = $data['mobile_no'];

    // Check if the mobile number exists in the users table
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE mobile_no = ?");
    $checkStmt->bind_param("s", $mobile_no);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Mobile number exists, generate and store OTP
        $otp = rand(100000, 999999);
        $otp_expiration = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        // Store OTP in the database
        $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiration = ? WHERE mobile_no = ?");
        $stmt->bind_param("sss", $otp, $otp_expiration, $mobile_no);

        if ($stmt->execute()) {
            // Simulate sending OTP (In real implementation, send via SMS API)
            echo json_encode(['success' => true, 'otp' => $otp]);
            $_SESSION['mobile_no'] = $mobile_no;
        } else {
            echo json_encode(['success' => false, 'message' => 'OTP तयार करण्यात अडचण आली.']);
        }

        $stmt->close();
    } else {
        // Mobile number does not exist in the database
        echo json_encode(['success' => false, 'message' => 'मोबाईल क्रमांक डेटाबेसमध्ये नाही.']);
    }

    $checkStmt->close();
}

$conn->close();
?>
