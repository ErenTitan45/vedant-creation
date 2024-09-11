<?php
session_start();

$conn = new mysqli("localhost", "root", "", "ladki_bahin_yojana");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$mobile = $_POST['mobile'] ?? null; 
if ($mobile) {
    $sql_check = "SELECT id FROM registration WHERE mobile = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $mobile);
    
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<p>Profile already submitted. Please <a href='profile.php'>update it here</a>.</p>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mazhi Ladki Bahin Yojana Registration</title>
    
</head>
<style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            min-width :200vh;;
        }

        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 1200px;
            width: 100%;
            animation: fadeIn 1s ease-in-out;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1), rgba(0,0,0,0.1));
            animation: rotate 6s linear infinite;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        label {
            font-size: 0.9rem;
            color: #555;
        }

        select, input[type="text"], input[type="date"], input[type="file"], input[type="radio"], button {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            text-align: centre;
        }

        select:focus, input[type="text"]:focus, input[type="date"]:focus, input[type="file"]:focus {
            border-color: #00aaff;
            box-shadow: 0 0 10px rgba(0, 170, 255, 0.2);
        }

        button {
            background-color: #00aaff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #007acc;
            transform: translateY(-2px);
        }

        button[type="submit"] {
            margin-top: 1rem;
            animation: slideIn 0.8s ease;
        }

        button[type="submit"]:hover {
            background-color: #005fa3;
        }

        button[name="save_as_draft"] {
            background-color: #f0f4f8;
            color: #555;
            border: 1px solid #ddd;
        }

        button[name="save_as_draft"]:hover {
            background-color: #ddd;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 1.5rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            button {
                font-size: 0.9rem;
            }
        }

</style>
<body>
    <div class="container">
        <h2>Mazhi Ladki Bahin Yojana Registration</h2>
        <form action="register_submit.php" method="POST" enctype="multipart/form-data">
            <h2>Registration Form</h2>
            <label for="full_name">Full Name (As per Aadhar):</label>
            <input type="text" id="full_name" name="full_name" required><br>

            <label for="father_name">Father's Name:</label>
            <input type="text" id="father_name" name="father_name" required><br>

            <label for="husband_name">Husband's Name (if applicable):</label>
            <input type="text" id="husband_name" name="husband_name"><br>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required><br>

            <label for="marital_status">Marital Status:</label>
            <select id="marital_status" name="marital_status" required>
                <option value="single">Single</option>
                <option value="married">Married</option>
            </select><br>

            <label for="born_in_maharashtra">Are You Born in Maharashtra?</label>
            <input type="radio" id="yes" name="born_in_maharashtra" value="yes"> Yes
            <input type="radio" id="no" name="born_in_maharashtra" value="no"> No<br>

            <h2>Applicant's Current Address & Other Information</h2>
            <label for="address">Applicant's Full Address:</label>
            <input type="text" id="address" name="address" required><br>

            <label for="pincode">Pincode:</label>
            <input type="text" id="pincode" name="pincode" required><br>

            <label for="district">District:</label>
            <select id="district" name="district" required>
                <option value="">Select District</option>
            </select><br>

            <label for="taluka">Taluka:</label>
            <select id="taluka" name="taluka" required>
                <option value="">Select Taluka</option>
            </select><br>

            <label for="village">Village:</label>
            <select id="village" name="village" required>
                <option value="">Select Village</option>
            </select><br>

            <label for="municipal_corporation">Municipal Corporation / Council:</label>
            <select id="municipal_corporation" name="municipal_corporation" required>
                <option value="">Select Municipal Corporation</option>
                <option value="corporation1">Corporation 1</option>
                <option value="corporation2">Corporation 2</option>
                <option value="corporation3">Corporation 3</option>
            </select><br>

            <label for="constituency">Constituency:</label>
            <select id="constituency" name="constituency" required>
                <option value="">Select Constituency</option>
                <option value="constituency1">Constituency 1</option>
                <option value="constituency2">Constituency 2</option>
                <option value="constituency3">Constituency 3</option>
            </select><br>

            <label for="mobile">Mobile Number:</label>
            <input type="text" id="mobile" name="mobile" required><br>

            <label for="financial_scheme_beneficiary">Are You A Beneficiary of Any Other Financial Schemes Implemented By State/Central Government?</label>
            <input type="radio" id="yes_scheme" name="financial_scheme_beneficiary" value="yes"> Yes
            <input type="radio" id="no_scheme" name="financial_scheme_beneficiary" value="no"> No<br>

            <h2>Upload Documents</h2>
            <label for="aadhar_front">Aadhar Card Front:</label>
            <input type="file" id="aadhar_front" name="aadhar_front" required><br>

            <label for="aadhar_back">Aadhar Card Back:</label>
            <input type="file" id="aadhar_back" name="aadhar_back" required><br>

            <label for="domicile_certificate">Domicile Certificate or Equivalent:</label>
            <input type="file" id="domicile_certificate" name="domicile_certificate" required><br>

            <label for="ration_card">Do You Have An Orange or Yellow Ration Card?</label>
            <input type="radio" id="yes_ration_card" name="ration_card" value="yes"> Yes
            <input type="radio" id="no_ration_card" name="ration_card" value="no"> No<br>
            <input type="text" name="ration_card" placeholder="Enter Ration Card Number">

            <label for="bank_passbook">Bank Passbook:</label>
            <input type="file" id="bank_passbook" name="bank_passbook" required><br>

            <label for="hamipatra">Applicant's Hamipatra (Signature And Upload):</label>
            <input type="file" id="hamipatra" name="hamipatra" required><br>

            <label for="applicant_photo">Applicant's Photo:</label>
            <input type="file" id="applicant_photo" name="applicant_photo" required><br>


            <button type="submit" name="submit">Submit</button>
            <button type="submit" name="save_as_draft">Save As Draft</button>
        </form>
    </div>

    <script>
        const data = {
            "Pune": {
                "talukas": ["Pune City", "Haveli", "Baramati", "Bhor", "Daund", "Indapur", "Junnar", "Khed", "Maval", "Mulshi", "Purandar", "Shirur", "Velhe"]
            },
            "Satara": {
                "talukas": ["Satara", "Karad", "Koregaon", "Patan", "Phaltan", "Khatav", "Man", "Wai", "Mahabaleshwar", "Jaoli"]
            },
            "Sangli": {
                "talukas": ["Miraj", "Tasgaon", "Khanapur", "Atpadi", "Jat", "Kavathe Mahankal", "Palus", "Shirala", "Walwa"]
            },
            "Solapur": {
                "talukas": ["Solapur North", "Solapur South", "Akkalkot", "Barshi", "Karmala", "Madha", "Malshiras", "Mangalvedha", "Pandharpur", "Sangole"]
            },
            "Kolhapur": {
                "talukas": ["Karveer", "Panhala", "Shahuwadi", "Hatkanangale", "Shirol", "Kagal", "Gadhinglaj", "Bhudargad", "Ajara", "Chandgad", "Radhanagari"]
            },
            "Ahmednagar": {
                "talukas": ["Ahmednagar", "Akole", "Jamkhed", "Karjat", "Kopargaon", "Nevasa", "Parner", "Pathardi", "Rahata", "Rahuri", "Sangamner", "Shrirampur", "Shrigonda", "Shevgaon"]
            },
            "Nashik": {
                "talukas": ["Nashik", "Igatpuri", "Dindori", "Kalwan", "Nandgaon", "Chandwad", "Sinnar", "Niphad", "Yeola", "Surgana", "Peth", "Trimbakeshwar", "Deola", "Baglan"]
            },
            "Dhule": {
                "talukas": ["Dhule", "Sakri", "Shirpur", "Shindkheda"]
            },
            "Jalgaon": {
                "talukas": ["Jalgaon", "Bhusawal", "Chalisgaon", "Amalner", "Erandol", "Pachora", "Jamner", "Parola", "Raver", "Yawal", "Muktainagar", "Bodwad"]
            },
            "Nandurbar": {
                "talukas": ["Nandurbar", "Shahada", "Taloda", "Akkalkuwa", "Akrani", "Navapur"]
            },
            "Mumbai City": {
                "talukas": ["Mumbai City"]
            },
            "Mumbai Suburban": {
                "talukas": ["Andheri", "Borivali", "Kurla"]
            },
            "Thane": {
                "talukas": ["Thane", "Kalyan", "Ulhasnagar", "Bhiwandi", "Shahapur", "Murbad", "Ambernath", "Dahanu", "Jawhar", "Mokhada", "Palghar", "Talasari", "Vasai", "Wada"]
            },
            "Raigad": {
                "talukas": ["Alibag", "Karjat", "Khalapur", "Mahad", "Mangaon", "Mhasla", "Murud", "Panvel", "Pen", "Poladpur", "Roha", "Shrivardhan", "Sudhagad", "Tala", "Uran"]
            },
            "Ratnagiri": {
                "talukas": ["Ratnagiri", "Chiplun", "Dapoli", "Guhagar", "Khed", "Lanja", "Mandangad", "Rajapur", "Sangameshwar"]
            },
            "Sindhudurg": {
                "talukas": ["Kankavli", "Deogad", "Malvan", "Sawantwadi", "Vengurla", "Dodamarg", "Kudal"]
            },
            "Aurangabad": {
                "talukas": ["Aurangabad", "Kannad", "Khuldabad", "Paithan", "Sillod", "Soygaon", "Vaijapur", "Gangapur", "Phulambri"]
            },
            "Beed": {
                "talukas": ["Beed", "Ashti", "Georai", "Kaij", "Majalgaon", "Parli", "Patoda", "Shirur", "Wadwani"]
            },
            "Jalna": {
                "talukas": ["Jalna", "Ambad", "Bhokardan", "Jafrabad", "Mantha", "Partur", "Ghansawangi"]
            },
            "Osmanabad": {
                "talukas": ["Osmanabad", "Tuljapur", "Omerga", "Bhoom", "Paranda", "Kalamb", "Lohara", "Washi"]
            },
            "Nanded": {
                "talukas": ["Nanded", "Ardhapur", "Bhokar", "Biloli", "Deglur", "Dharmabad", "Hadgaon", "Himayatnagar", "Kandhar", "Kinwat", "Loha", "Mahur", "Mukhed", "Mudkhed", "Naigaon", "Umri"]
            },
            "Latur": {
                "talukas": ["Latur", "Ahmedpur", "Ausa", "Chakur", "Deoni", "Jalkot", "Nilanga", "Renapur", "Shirur Anantpal", "Udgir"]
            },
            "Amravati": {
                "talukas": ["Amravati", "Achalpur", "Anjangaon", "Bhatkuli", "Chandur Bazar", "Chandur Railway", "Chikhaldara", "Daryapur", "Dharni", "Dhamangaon", "Morshi", "Nandgaon Khandeshwar", "Teosa", "Warud"]
            },
            "Akola": {
                "talukas": ["Akola", "Akot", "Balapur", "Barshitakli", "Murtijapur", "Patur", "Telhara"]
            },
            "Yavatmal": {
                "talukas": ["Yavatmal", "Arni", "Babhulgaon", "Darwha", "Digras", "Ghatanji", "Kalamb", "Mahagaon", "Maregaon", "Ner", "Pusad", "Ralegaon", "Umarkhed", "Wani", "Zari Jamni"]
            },
            "Buldhana": {
                "talukas": ["Buldhana", "Chikhli", "Deulgaon Raja", "Jalgaon Jamod", "Khamgaon", "Lonar", "Malkapur", "Mehkar", "Motala", "Nandura", "Sangrampur", "Shegaon", "Sindkhed Raja"]
            },
            "Washim": {
                "talukas": ["Washim", "Karanja", "Mangrulpir", "Manora", "Risod"]
            },
            "Wardha": {
                "talukas": ["Wardha", "Arvi", "Ashti", "Deoli", "Hinganghat", "Karanja", "Samudrapur", "Seloo"]
            },
            "Nagpur": {
                "talukas": ["Nagpur", "Hingna", "Kamptee", "Katol", "Kuhi", "Mauda", "Narkhed", "Parseoni", "Ramtek", "Savner", "Umred"]
            },
            "Bhandara": {
                "talukas": ["Bhandara", "Lakhandur", "Lakhani", "Mohadi", "Pauni", "Sakoli", "Tumsar"]
            },
            "Chandrapur": {
                "talukas": ["Chandrapur", "Bhadravati", "Brahmapuri", "Chimur", "Gondpipri", "Mul", "Nagbhid", "Pombhurna", "Rajura", "Saoli", "Sindewahi", "Warora"]
            },
            "Gadchiroli": {
                "talukas": ["Gadchiroli", "Aheri", "Armori", "Bhamragad", "Chamorshi", "Dhanora", "Etapalli", "Korchi", "Kurkheda", "Mulchera", "Sironcha"]
            },
            "Gondia": {
                "talukas": ["Gondia", "Amgaon", "Arjuni Morgaon", "Deori", "Goregaon", "Sadak Arjuni", "Salekasa", "Tirora"]
            },
            "Wardha": {
                "talukas": ["Wardha", "Arvi", "Ashti", "Deoli", "Hinganghat", "Karanja", "Samudrapur", "Seloo"]
            }
        };

        const districtSelect = document.getElementById('district');
        for (const district in data) {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district.charAt(0).toUpperCase() + district.slice(1);
            districtSelect.appendChild(option);
        }

        districtSelect.addEventListener('change', function() {
            const talukaSelect = document.getElementById('taluka');
            const villageSelect = document.getElementById('village');
            const selectedDistrict = this.value;

            talukaSelect.innerHTML = '<option value="">Select Taluka</option>';
            villageSelect.innerHTML = '<option value="">Select Village</option>';

            if (selectedDistrict) {
                const talukas = data[selectedDistrict].talukas;
                talukas.forEach(taluka => {
                    const option = document.createElement('option');
                    option.value = taluka;
                    option.textContent = taluka.charAt(0).toUpperCase() + taluka.slice(1);
                    talukaSelect.appendChild(option);
                });
            }
        });

        document.getElementById('taluka').addEventListener('change', function() {
            const villageSelect = document.getElementById('village');
            const selectedDistrict = districtSelect.value;
            const selectedTaluka = this.value;

            villageSelect.innerHTML = '<option value="">Select Village</option>';

            if (selectedTaluka) {
                const villages = ["Village1", "Village2", "Village3"]; 
                villages.forEach(village => {
                    const option = document.createElement('option');
                    option.value = village;
                    option.textContent = village.charAt(0).toUpperCase() + village.slice(1);
                    villageSelect.appendChild(option);
                });
            }
        });
    </script>
</body>
</html>
