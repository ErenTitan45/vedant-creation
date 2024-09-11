<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "ladki_bahin_yojana");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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
    $ration_card = isset($_POST['ration_card']) ? $_POST['ration_card'] : null;

    $upload_dir = "uploads/";
    $aadhar_front = $upload_dir . basename($_FILES['aadhar_front']['name']);
    $aadhar_back = $upload_dir . basename($_FILES['aadhar_back']['name']);
    $domicile_certificate = $upload_dir . basename($_FILES['domicile_certificate']['name']);
    $bank_passbook = $upload_dir . basename($_FILES['bank_passbook']['name']);
    $hamipatra = $upload_dir . basename($_FILES['hamipatra']['name']);
    $applicant_photo = $upload_dir . basename($_FILES['applicant_photo']['name']);

    move_uploaded_file($_FILES['aadhar_front']['tmp_name'], $aadhar_front);
    move_uploaded_file($_FILES['aadhar_back']['tmp_name'], $aadhar_back);
    move_uploaded_file($_FILES['domicile_certificate']['tmp_name'], $domicile_certificate);
    move_uploaded_file($_FILES['bank_passbook']['tmp_name'], $bank_passbook);
    move_uploaded_file($_FILES['hamipatra']['tmp_name'], $hamipatra);
    move_uploaded_file($_FILES['applicant_photo']['tmp_name'], $applicant_photo);

    $sql_check = "SELECT id FROM registration WHERE mobile = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $mobile);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $user_id = $result_check->fetch_assoc()['id'];

        $stmt_update = $conn->prepare("UPDATE registration SET 
            full_name = ?, father_name = ?, husband_name = ?, dob = ?, marital_status = ?, born_in_maharashtra = ?,
            address = ?, pincode = ?, district = ?, taluka = ?, village = ?, municipal_corporation = ?, constituency = ?, 
            financial_scheme_beneficiary = ?, ration_card = ? 
            WHERE id = ?");
        $stmt_update->bind_param("sssssssssssssssi", $full_name, $father_name, $husband_name, $dob, $marital_status, $born_in_maharashtra, $address, $pincode, $district, $taluka, $village, $municipal_corporation, $constituency, $financial_scheme_beneficiary, $ration_card, $user_id);
        $stmt_update->execute();

        $doc_stmt = $conn->prepare("UPDATE documents SET 
            aadhar_front = ?, aadhar_back = ?, domicile_certificate = ?, bank_passbook = ?, hamipatra = ?, applicant_photo = ? 
            WHERE user_id = ?");
        $doc_stmt->bind_param("ssssssi", $aadhar_front, $aadhar_back, $domicile_certificate, $bank_passbook, $hamipatra, $applicant_photo, $user_id);
        $doc_stmt->execute();

        echo "Profile updated successfully!";
        header("Location: dashboard.php");
        exit(); 
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO registration (full_name, father_name, husband_name, dob, marital_status, born_in_maharashtra, address, pincode, district, taluka, village, municipal_corporation, constituency, mobile, financial_scheme_beneficiary, ration_card) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("ssssssssssssssss", $full_name, $father_name, $husband_name, $dob, $marital_status, $born_in_maharashtra, $address, $pincode, $district, $taluka, $village, $municipal_corporation, $constituency, $mobile, $financial_scheme_beneficiary, $ration_card);

        if ($stmt_insert->execute()) {
            $user_id = $stmt_insert->insert_id;

            $doc_stmt = $conn->prepare("INSERT INTO documents (user_id, aadhar_front, aadhar_back, domicile_certificate, bank_passbook, hamipatra, applicant_photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $doc_stmt->bind_param("issssss", $user_id, $aadhar_front, $aadhar_back, $domicile_certificate, $bank_passbook, $hamipatra, $applicant_photo);
            $doc_stmt->execute();

            echo "Registration successful!";
            header("Location: dashboard.php");
            exit(); 
        } else {
            echo "Error: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    }

    $stmt_check->close();
    $conn->close();
}
?>