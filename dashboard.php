<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>मुख्यमंत्री माझी लाडकी बहिण योजना - डॅशबोर्ड</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes slideIn {
            0% { transform: translateY(-20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            height: 250vh;
            animation: fadeIn 1s ease;
        }

        .header {
            background: linear-gradient(to right, #170ad4, #021061);
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #dce7e7;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: slideIn 1.2s ease;
        }

        .logos {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px;
            animation: slideIn 1.4s ease;
        }

        .left-logos, .right-logo {
            display: flex;
            gap: 30px;
        }

        .left-logos img, .right-logo img {
            height: 85px;
            width: auto;
            transition: transform 0.3s ease;
        }

        .left-logos img:hover, .right-logo img:hover {
            transform: scale(1.1);
        }

        nav {
            background-color: #ce430c;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: slideIn 1.6s ease;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        nav ul li a {
            color: #dfd7d7;
            text-align: center;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
            padding: 10px;
        }

        nav ul li a:hover {
            background-color: #b03b0b;
            border-radius: 5px;
            transform: scale(1.1);
        }

        .content {
            text-align: center;
            padding: 20px;
            animation: fadeIn 2s ease;
        }

        .content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .content img:hover {
            transform: scale(1.05);
        }

        h1 {
            color: #333;
            margin-top: 20px;
            animation: slideIn 1.8s ease;
        }

        .footer {
            background-color: #021061;
            color: white;
            padding: 0.02px;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1);
            font-size: 0.8rem;
            animation: fadeIn 2.4s ease;
        }
    </style>
</head>
<body>
    <header>
        <div class="header">
            <div class="helpline">
                हेल्पलाइन क्रमांक: 9322765939
            </div>
        </div>
        <div class="logos">
            <div class="left-logos">
                <img src="web images/new-logo.png" alt="लोगो 1">
                <img src="logo-maha.png" alt="लोगो 2">
            </div>
            <div class="right-logo">
                <img src="govEmblem.png" alt="लोगो 3">
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="home.html"><i class="fas fa-home"></i> मुख्यपृष्ठ</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> प्रोफाइल</a></li>
                <li><a href="adhar_validate.php"><i class="fas fa-file"></i> योजनेचा अर्ज</a></li>
                <li><a href="about.html"><i class="fas fa-info"></i> योजनेची माहिती</a></li>
                <li><a href="login.html"><i class="fas fa-sign-out-alt"></i> लॉगआउट</a></li>
            </ul>
        </nav>
    </header>
    <main class="content">
        <img src="new-hero.jpeg" alt="मुख्यमंत्री माझी लाडकी बहिण योजना">
        <h1>माझी लाडकी बहिण योजना मध्ये स्वागत आहे</h1>
    </main>
    <div class="footer">
        <p>© 2024 महिला व बाल विकास विभाग. सर्व हक्क राखीव.</p>
    </div>
</body>
</html>
