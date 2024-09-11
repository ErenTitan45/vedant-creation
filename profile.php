<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>   
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    color: #333;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #FF9800;
    font-size: 28px;
    margin-bottom: 20px;
    border-bottom: 2px solid #FF9800;
    padding-bottom: 10px;
}

h3 {
    color: #555;
    font-size: 24px;
    margin-top: 30px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 5px;
}


p {
    line-height: 1.6;
    margin-bottom: 10px;
}

p strong {
    color: #333;
}

a {
    color: #2196F3 ;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

.error {
    color: #e74c3c;
    font-size: 24px;
    margin-bottom: 15px;
}

a.button {
    display: inline-block;
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 15px;
    text-align: center;
    border-radius: 5px;
    margin-top: 20px;
}

a.button:hover {
    background-color: #45a049;
}

@media (max-width: 600px) {
    .container {
        padding: 15px;
    }

    h2 {
        font-size: 24px;
    }

    h3 {
        font-size: 20px;
    }
}

</style>
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

$sql_check = "SELECT * FROM registration WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $registration_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $registration = $result_check->fetch_assoc();
    $essential_fields = [
        'full_name', 'father_name', 'dob', 'marital_status',
        'address', 'pincode', 'district', 'mobile'
    ];

    $is_filled = true;
    foreach ($essential_fields as $field) {
        if (empty($registration[$field])) {
            $is_filled = false;
            break;
        }
    }

    if ($is_filled) {
        echo "<h2>आपली प्रोफाइल माहिती</h2>";
        echo "<p><strong>पूर्ण नाव:</strong> " . htmlspecialchars($registration['full_name']) . "</p>";
        echo "<p><strong>वडिलांचे नाव:</strong> " . htmlspecialchars($registration['father_name']) . "</p>";
        echo "<p><strong>पतीचे नाव:</strong> " . htmlspecialchars($registration['husband_name']) . "</p>";
        echo "<p><strong>जन्मतारीख:</strong> " . htmlspecialchars($registration['dob']) . "</p>";
        echo "<p><strong>वैवाहिक स्थिती:</strong> " . htmlspecialchars($registration['marital_status']) . "</p>";
        echo "<p><strong>पत्ता:</strong> " . htmlspecialchars($registration['address']) . "</p>";
        echo "<p><strong>पिनकोड:</strong> " . htmlspecialchars($registration['pincode']) . "</p>";
        echo "<p><strong>जिल्हा:</strong> " . htmlspecialchars($registration['district']) . "</p>";
        echo "<p><strong>तालुका:</strong> " . htmlspecialchars($registration['taluka']) . "</p>";
        echo "<p><strong>गांव:</strong> " . htmlspecialchars($registration['village']) . "</p>";
        echo "<p><strong>महानगरपालिका / परिषद:</strong> " . htmlspecialchars($registration['municipal_corporation']) . "</p>";
        echo "<p><strong>संशोधन:</strong> " . htmlspecialchars($registration['constituency']) . "</p>";
        echo "<p><strong>मोबाइल नंबर:</strong> " . htmlspecialchars($registration['mobile']) . "</p>";
        echo "<p><strong>आधार क्रमांक:</strong> " . htmlspecialchars($registration['aadhaar_no']) . "</p>";
        echo "<p><strong>आर्थिक योजना लाभार्थी:</strong> " . htmlspecialchars($registration['financial_scheme_beneficiary']) . "</p>";

        echo "<h3>उपलब्ध दस्तऐवज:</h3>";
        echo "<p><strong>आधार कार्ड (समोर):</strong> <a href='uploads/" . htmlspecialchars($registration['aadhar_front']) . "' target='_blank'>वाचा</a></p>";
        echo "<p><strong>आधार कार्ड (पाठीमागे):</strong> <a href='uploads/" . htmlspecialchars($registration['aadhar_back']) . "' target='_blank'>वाचा</a></p>";
        echo "<p><strong>डोमिसाइल सर्टिफिकेट:</strong> <a href='uploads/" . htmlspecialchars($registration['domicile_certificate']) . "' target='_blank'>वाचा</a></p>";
        echo "<p><strong>बँक पासबुक:</strong> <a href='uploads/" . htmlspecialchars($registration['bank_passbook']) . "' target='_blank'>वाचा</a></p>";
        echo "<p><strong>हमीपत्र:</strong> <a href='uploads/" . htmlspecialchars($registration['hamipatra']) . "' target='_blank'>वाचा</a></p>";
        echo "<p><strong>अर्जदाराचे फोटो:</strong> <a href='uploads/" . htmlspecialchars($registration['applicant_photo']) . "' target='_blank'>वाचा</a></p>";

        echo "<p>आपण आधीच फॉर्म भरला आहे. <a href='adhar_validate.php.'>येथे क्लिक करून फॉर्म अपडेट करा</a>.</p>";
    } else {
        echo "<h2 class='error'>Profile incomplete</h2>";
        echo "<p>आपले प्रोफाइल अपूर्ण आहे. कृपया सर्व आवश्यक माहिती भरा.</p>";
        echo "<p><a href='adhar_validate.php'>येथे क्लिक करून रजिस्ट्रेशन फॉर्म भरा</a>.</p>";
    }
} else {
    echo "<h2 class='error'>Profile doesn't exist</h2>";
    echo "<p>आपले प्रोफाइल अस्तित्वात नाही. कृपया फॉर्म भरावा.</p>";
    echo "<p><a href='adhar_validate.php'>येथे क्लिक करून रजिस्ट्रेशन फॉर्म भरा</a>.</p>";
}

$stmt_check->close();
$conn->close();
?>

        
    </div>
</body>
</html>

