<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

    .container {
    width: 80%;
    margin: 2rem auto;
    padding: 2rem;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

    h1 {
    color: #333;
    text-align: center;
    margin-bottom: 1rem;
}

    form {
    display: flex;
    flex-direction: column;
}

    label {
    font-weight: bold;
    margin-bottom: 0.5rem;
}

    input[type="text"],
    input[type="date"],
    input[type="file"] {
    padding: 0.8rem;
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

    input[type="text"]:focus,
    input[type="date"]:focus,
    input[type="file"]:focus {
    border-color: #007bff;
    outline: none;
}

    input[type="submit"] {
    padding: 0.8rem;
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    color: #fff;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

    input[type="submit"]:hover {
    background-color: #0056b3;
}

    .image-preview {
    width: 150px; /* Adjust as needed */
    height: auto;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

    @media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 1rem;
    }

    input[type="text"],
    input[type="date"],
    input[type="file"] {
        font-size: 0.9rem;
    }

    input[type="submit"] {
        font-size: 0.9rem;
    }


        .image-preview {
            width: 150px; /* Adjust the width as needed */
            height: auto;
            margin-bottom: 10px;
        }
    }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();

        if (!isset($_SESSION['user_id'])) {
            echo "<p>कृपया लॉगिन करा.</p>";
            exit();
        }
        $conn = new mysqli("localhost", "root", "", "ladki_bahin_yojana");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $registration_id = $_SESSION['user_id'];  
        $sql = "SELECT * FROM registration WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("i", $registration_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            die("Query failed: " . htmlspecialchars($stmt->error));
        }

        if ($result->num_rows > 0) {
            $registration = $result->fetch_assoc();
        } else {
            echo "<p>प्रोफाइल उपलब्ध नाही.</p>";
            exit();
        }

        $stmt->close();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

            
            $upload_dir = "uploads/";
            $files = [
                'aadhar_front' => $_FILES['aadhar_front']['name'],
                'aadhar_back' => $_FILES['aadhar_back']['name'],
                'domicile_certificate' => $_FILES['domicile_certificate']['name'],
                'bank_passbook' => $_FILES['bank_passbook']['name'],
                'hamipatra' => $_FILES['hamipatra']['name'],
                'applicant_photo' => $_FILES['applicant_photo']['name']
            ];

            foreach ($files as $key => $file) {
                if (!empty($_FILES[$key]['tmp_name'])) {
                    move_uploaded_file($_FILES[$key]['tmp_name'], $upload_dir . $file);
                } else {
                    $files[$key] = $registration[$key];
                }
            }

            $sql_update = "UPDATE registration SET
                full_name = ?, father_name = ?, husband_name = ?, dob = ?, marital_status = ?, born_in_maharashtra = ?,
                address = ?, pincode = ?, district = ?, taluka = ?, village = ?, municipal_corporation = ?, constituency = ?, 
                mobile = ?, financial_scheme_beneficiary = ?, aadhar_front = ?, aadhar_back = ?, domicile_certificate = ?,
                bank_passbook = ?, hamipatra = ?, applicant_photo = ?
                WHERE id = ?";

            $stmt_update = $conn->prepare($sql_update);

            if ($stmt_update === false) {
                die("Prepare failed: " . htmlspecialchars($conn->error));
            }

            $stmt_update->bind_param(
                "sssssssssssssssssssssi",
                $full_name, $father_name, $husband_name, $dob, $marital_status, $born_in_maharashtra,
                $address, $pincode, $district, $taluka, $village, $municipal_corporation, $constituency, $mobile,
                $financial_scheme_beneficiary, $files['aadhar_front'], $files['aadhar_back'], $files['domicile_certificate'],
                $files['bank_passbook'], $files['hamipatra'], $files['applicant_photo'], $registration_id
            );

            if ($stmt_update->execute()) {
                header("Location: profile.php");
                exit();
            } else {
                echo "Error: " . $sql_update . "<br>" . $conn->error;
            }

            $stmt_update->close();
            $conn->close();
        }
        ?>

        <form action="update_form.php" method="post" enctype="multipart/form-data">
            <label for="full_name">पूर्ण नाव:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($registration['full_name']); ?>" required><br><br>

            <label for="father_name">वडिलांचे नाव:</label>
            <input type="text" id="father_name" name="father_name" value="<?php echo htmlspecialchars($registration['father_name']); ?>" required><br><br>

            <label for="husband_name">पतीचे नाव:</label>
            <input type="text" id="husband_name" name="husband_name" value="<?php echo htmlspecialchars($registration['husband_name']); ?>"><br><br>

            <label for="dob">जन्मतारीख:</label>
            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($registration['dob']); ?>" required><br><br>

            <label for="marital_status">वैवाहिक स्थिती:</label>
            <input type="text" id="marital_status" name="marital_status" value="<?php echo htmlspecialchars($registration['marital_status']); ?>" required><br><br>

            <label for="born_in_maharashtra">महाराष्ट्रात जन्म:</label>
            <input type="text" id="born_in_maharashtra" name="born_in_maharashtra" value="<?php echo htmlspecialchars($registration['born_in_maharashtra']); ?>" required><br><br>

            <label for="address">पत्ता:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($registration['address']); ?>" required><br><br>

            <label for="pincode">पिनकोड:</label>
            <input type="text" id="pincode" name="pincode" value="<?php echo htmlspecialchars($registration['pincode']); ?>" required><br><br>

            <label for="district">जिल्हा:</label>
            <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($registration['district']); ?>" required><br><br>

            <label for="taluka">तालुका:</label>
            <input type="text" id="taluka" name="taluka" value="<?php echo htmlspecialchars($registration['taluka']); ?>" required><br><br>

            <label for="village">गांव:</label>
            <input type="text" id="village" name="village" value="<?php echo htmlspecialchars($registration['village']); ?>" required><br><br>

            <label for="municipal_corporation">महानगरपालिका / परिषद:</label>
            <input type="text" id="municipal_corporation" name="municipal_corporation" value="<?php echo htmlspecialchars($registration['municipal_corporation']); ?>" required><br><br>

            <label for="constituency">संशोधन:</label>
            <input type="text" id="constituency" name="constituency" value="<?php echo htmlspecialchars($registration['constituency']); ?>" required><br><br>

            <label for="mobile">मोबाइल नंबर:</label>
            <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($registration['mobile']); ?>" required><br><br>

            <label for="financial_scheme_beneficiary">आर्थिक योजना लाभार्थी:</label>
            <input type="text" id="financial_scheme_beneficiary" name="financial_scheme_beneficiary" value="<?php echo htmlspecialchars($registration['financial_scheme_beneficiary']); ?>" required><br><br>

            <!--upload karnyasathi -->
            <label for="aadhar_front">आधार कार्ड (समोर):</label>
            <input type="file" id="aadhar_front" name="aadhar_front"><br>
            <?php if ($registration['aadhar_front']) echo "<img src='uploads/" . htmlspecialchars($registration['aadhar_front']) . "' class='image-preview' alt='आधार कार्ड (समोर)'><br>"; ?>

            <label for="aadhar_back">आधार कार्ड (पाठीमागे):</label>
            <input type="file" id="aadhar_back" name="aadhar_back"><br>
            <?php if ($registration['aadhar_back']) echo "<img src='uploads/" . htmlspecialchars($registration['aadhar_back']) . "' class='image-preview' alt='आधार कार्ड (पाठीमागे)'><br>"; ?>

            <label for="domicile_certificate">डोमिसाइल सर्टिफिकेट:</label>
            <input type="file" id="domicile_certificate" name="domicile_certificate"><br>
            <?php if ($registration['domicile_certificate']) echo "<img src='uploads/" . htmlspecialchars($registration['domicile_certificate']) . "' class='image-preview' alt='डोमिसाइल सर्टिफिकेट'><br>"; ?>

            <label for="bank_passbook">बँक पासबुक:</label>
            <input type="file" id="bank_passbook" name="bank_passbook"><br>
            <?php if ($registration['bank_passbook']) echo "<img src='uploads/" . htmlspecialchars($registration['bank_passbook']) . "' class='image-preview' alt='बँक पासबुक'><br>"; ?>

            <label for="hamipatra">हमीपत्र:</label>
            <input type="file" id="hamipatra" name="hamipatra"><br>
            <?php if ($registration['hamipatra']) echo "<img src='uploads/" . htmlspecialchars($registration['hamipatra']) . "' class='image-preview' alt='हमीपत्र'><br>"; ?>

            <label for="applicant_photo">अर्जदाराचे फोटो:</label>
            <input type="file" id="applicant_photo" name="applicant_photo"><br>
            <?php if ($registration['applicant_photo']) echo "<img src='uploads/" . htmlspecialchars($registration['applicant_photo']) . "' class='image-preview' alt='अर्जदाराचे फोटो'><br>"; ?>

            <input type="submit" value="अपडेट करा">
        </form>
    </div>
</body>
</html>
