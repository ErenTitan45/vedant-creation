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

if (!isset($_SESSION['mobile_no'])) {
    echo "मोबाइल नंबर सत्रात उपलब्ध नाही. कृपया लॉगिन करा.";
    header("Location: login.html"); 
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $mobile_no = $_SESSION['mobile_no'];

    if ($new_password === $confirm_password) {
        
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE users SET password = ?, otp = NULL, otp_expiration = NULL WHERE mobile_no = ?");
        $stmt->bind_param("ss", $hashed_password, $mobile_no);

        if ($stmt->execute()) {
            $message = "<p class='success-message'>पासवर्ड यशस्वीरित्या सेट केला गेला आहे.</p>";
            header("Refresh: 2; URL=login.html"); 
            exit();
        } else {
            $message = "<p class='error-message'>पासवर्ड अपडेट करण्यात अडचण आली.</p>";
        }

        $stmt->close();
    } else {
        $message = "<p class='error-message'>पासवर्ड जुळत नाहीत.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="mr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>नवीन पासवर्ड सेट करा</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* General Styles */
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(to right, #ece9e6, #ffffff); /* Subtle gradient background */
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .reset-password-container {
      background: white;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); /* Light shadow for depth */
      border-radius: 10px;
      padding: 40px;
      width: 400px;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .reset-password-container:hover {
      transform: translateY(-10px); /* Slight lift on hover */
    }

    .reset-password-container h2 {
      margin-bottom: 20px;
      font-size: 2rem;
      color: #333; /* Dark grey for the title */
      font-weight: 700;
    }

    .reset-password-container input {
      width: 100%;
      padding: 15px;
      margin: 10px 0;
      border: 1px solid #ccc; /* Subtle border */
      border-radius: 5px;
      font-size: 1rem;
      transition: box-shadow 0.3s ease;
    }

    .reset-password-container input:focus {
      outline: none;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Light glow effect */
    }

    .reset-password-container button {
      background-color: #021061; /* Deep blue color for the button */
      color: white;
      border: none;
      padding: 15px;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
      font-size: 1.2rem;
      font-weight: 600;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .reset-password-container button:hover {
      background-color: #020c4b; /* Slightly darker blue on hover */
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Deepened shadow effect */
    }

    .error-message, .success-message {
      font-size: 1rem;
      margin-bottom: 15px;
    }

    .error-message {
      color: red;
    }

    .success-message {
      color: green;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
      .reset-password-container {
        width: 90%;
        padding: 30px;
      }

      .reset-password-container h2 {
        font-size: 1.8rem;
      }

      .reset-password-container input, 
      .reset-password-container button {
        font-size: 1rem;
        padding: 12px;
      }
    }

    @media (max-width: 480px) {
      .reset-password-container {
        width: 100%;
        padding: 20px;
      }

      .reset-password-container h2 {
        font-size: 1.6rem;
      }

      .reset-password-container input, 
      .reset-password-container button {
        font-size: 0.9rem;
        padding: 10px;
      }
    }
  </style>
</head>
<body>
  <div class="reset-password-container">
    <h2>नवीन पासवर्ड सेट करा</h2>
    <?php if (!empty($message)) echo $message; ?>
    <form method="POST" action="">
      <input type="password" name="new_password" id="new_password" placeholder="नवीन पासवर्ड" required>
      <input type="password" name="confirm_password" id="confirm_password" placeholder="पासवर्डची पुष्टी करा" required>
      <button type="submit">पासवर्ड सेट करा</button>
    </form>
  </div>
</body>
</html>
