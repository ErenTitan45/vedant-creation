<?php
// Database connection
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
    // Save user data
    $full_name = $_POST['full_name'];
    $father_name = $_POST['father_name'];
    $husband_name = $_POST['husband_name'] ?? null;
    $dob = $_POST['dob'];
    $marital_status = $_POST['marital_status'];
    $born_in_maharashtra = $_POST['born_in_maharashtra'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $district = $_POST['district'];
    $taluka = $_POST['taluka'];
    $village = $_POST['village'];
    $municipal_corporation = $_POST['municipal_corporation'];
    $constituency = $_POST['constituency'];
    $mobile = $_POST['mobile'];
    $financial_scheme_beneficiary = $_POST['financial_scheme_beneficiary'];
    $ration_card = $_POST['ration_card'];
    $ration_card = isset($_POST['ration_card']) ? $_POST['ration_card'] : null;


    $stmt = $conn->prepare("INSERT INTO registration (full_name, father_name, husband_name, dob, marital_status, born_in_maharashtra, address, pincode, district, taluka, village, municipal_corporation, constituency, mobile, financial_scheme_beneficiary, ration_card) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssss", $full_name, $father_name, $husband_name, $dob, $marital_status, $born_in_maharashtra, $address, $pincode, $district, $taluka, $village, $municipal_corporation, $constituency, $mobile, $financial_scheme_beneficiary, $ration_card);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Save document paths
        $aadhar_front = 'uploads/' . basename($_FILES['aadhar_front']['name']);
        $aadhar_back = 'uploads/' . basename($_FILES['aadhar_back']['name']);
        $domicile_certificate = 'uploads/' . basename($_FILES['domicile_certificate']['name']);
        $bank_passbook = 'uploads/' . basename($_FILES['bank_passbook']['name']);
        $hamipatra = 'uploads/' . basename($_FILES['hamipatra']['name']);
        $applicant_photo = 'uploads/' . basename($_FILES['applicant_photo']['name']);

        move_uploaded_file($_FILES['aadhar_front']['tmp_name'], $aadhar_front);
        move_uploaded_file($_FILES['aadhar_back']['tmp_name'], $aadhar_back);
        move_uploaded_file($_FILES['domicile_certificate']['tmp_name'], $domicile_certificate);
        move_uploaded_file($_FILES['bank_passbook']['tmp_name'], $bank_passbook);
        move_uploaded_file($_FILES['hamipatra']['tmp_name'], $hamipatra);
        move_uploaded_file($_FILES['applicant_photo']['tmp_name'], $applicant_photo);

        $doc_stmt = $conn->prepare("INSERT INTO documents (user_id, aadhar_front, aadhar_back, domicile_certificate, bank_passbook, hamipatra, applicant_photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $doc_stmt->bind_param("issssss", $user_id, $aadhar_front, $aadhar_back, $domicile_certificate, $bank_passbook, $hamipatra, $applicant_photo);
        $doc_stmt->execute();

        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
