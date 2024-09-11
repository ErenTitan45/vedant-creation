<?php
session_start();
$conn = new mysqli("localhost", "root", "", "ladki_bahin_yojana");

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed.']));
}
$aadhaar_no = $_POST['aadhaar_no'] ?? null;

if ($aadhaar_no) {
    $sql_check = "SELECT id FROM registration WHERE aadhaar_no = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $aadhaar_no);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo json_encode(['error' => 'exists']);
        exit();
    }
    $sql_insert = "INSERT INTO registration (aadhaar_no, created_at) VALUES (?, NOW())";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("s", $aadhaar_no);

    if ($stmt_insert->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error inserting data.']);
    }
} else {
    echo json_encode(['error' => 'Aadhaar number not provided.']);
}

$conn->close();
?>
