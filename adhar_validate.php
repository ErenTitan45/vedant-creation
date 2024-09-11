<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aadhaar Validation with OTP</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9f9f9;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        input[type="text"],
        input[type="number"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background-color: #d35400;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-bottom: 15px;
        }

        button:hover {
            background-color: #e67e22;
        }

        .captcha-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .captcha {
            font-size: 1.5rem;
            font-weight: bold;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            width: 50%;
            text-align: center;
        }

        .refresh-btn {
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #b0b0b0;
            border-radius: 5px;
            cursor: pointer;
        }

        .otp-container input {
            width: 40px;
            padding: 10px;
            margin: 5px;
            font-size: 16px;
            text-align: center;
        }

        .otp-display {
            font-size: 24px;
            font-weight: bold;
            color: #d35400;
            margin-bottom: 10px;
        }

        .message {
            font-size: 1rem;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Aadhaar Validation</h2>

    <!-- Aadhaar input -->
    <label for="aadhaar_no">Aadhaar No.*</label>
    <input type="text" id="aadhaar_no" name="aadhaar_no" placeholder="Please provide Aadhaar card no." maxlength="12" required>

    <!-- Captcha input -->
    <label for="captcha">Captcha*</label>
    <div class="captcha-container">
        <div class="captcha" id="captcha"></div>
        <button type="button" class="refresh-btn" onclick="generateCaptcha()">Refresh</button>
    </div>
    <input type="text" id="captcha_input" name="captcha_input" placeholder="Enter Captcha" required>

    <!-- Validate Aadhaar button -->
    <button id="otpSendBtn" onclick="validateCaptcha()">OTP पाठवा</button>

    <!-- Message display -->
    <div id="message" class="message"></div>

    <div class="otp-container" id="otpContainer" style="display:none;">
        <p>OTP प्रविष्ट करा:</p>
        <div class="otp-display" id="otpDisplay"></div>
        <input type="text" id="otp1" maxlength="1">
        <input type="text" id="otp2" maxlength="1">
        <input type="text" id="otp3" maxlength="1">
        <input type="text" id="otp4" maxlength="1">
        <input type="text" id="otp5" maxlength="1">
        <input type="text" id="otp6" maxlength="1">

        <!-- Verify OTP button -->
        <button type="button" id="otpVerifyBtn" onclick="verifyOTP()">OTP सत्यापित करा</button>
    </div>
</div>

<script>
    let generatedCaptcha = '';
    let generatedOTP = '';

    // Function to generate a random captcha
    function generateCaptcha() {
        const chars = '0123456789';
        let captcha = '';
        for (let i = 0; i < 6; i++) {
            captcha += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('captcha').innerText = captcha;
        generatedCaptcha = captcha;
    }

    // Function to validate captcha and Aadhaar, and then generate OTP
    function validateCaptcha() {
        const userCaptcha = document.getElementById('captcha_input').value;
        const aadhaarNo = document.getElementById('aadhaar_no').value;
        const messageDiv = document.getElementById('message');

        if (!aadhaarNo || aadhaarNo.length !== 12) {
            messageDiv.innerText = 'Aadhaar number must be 12 digits long.';
            messageDiv.className = 'message error';
            hideMessage();
            return;
        }

        if (userCaptcha === generatedCaptcha) {
            messageDiv.innerText = 'Aadhaar validated successfully! Generating OTP...';
            messageDiv.className = 'message success';

            // Generate and display OTP
            generateOTP();

            // Clear previous OTP inputs and show OTP input fields
            clearOTPInputs();
            document.getElementById('otpContainer').style.display = 'block';

            // Display OTP verify button
            document.getElementById('otpVerifyBtn').style.display = 'block';
        } else {
            messageDiv.innerText = 'Invalid Captcha';
            messageDiv.className = 'message error';
            generateCaptcha();  // Refresh captcha on failure
        }

        hideMessage();
    }

    // Function to clear the OTP input fields
    function clearOTPInputs() {
        document.querySelectorAll('.otp-container input').forEach(input => {
            input.value = '';  // Clear all OTP input fields
        });
    }

    // Function to generate a random 6-digit OTP
    function generateOTP() {
        generatedOTP = Math.floor(100000 + Math.random() * 900000).toString();
        document.getElementById('otpDisplay').innerText = generatedOTP;
        console.log("Generated OTP: " + generatedOTP); // Simulate sending OTP (for demo purposes)
    }

    // Function to verify OTP
    function verifyOTP() {
        const otpInputs = [
            document.getElementById('otp1').value,
            document.getElementById('otp2').value,
            document.getElementById('otp3').value,
            document.getElementById('otp4').value,
            document.getElementById('otp5').value,
            document.getElementById('otp6').value
        ];

        const enteredOTP = otpInputs.join('');
        const messageDiv = document.getElementById('message');

        if (enteredOTP === generatedOTP) {
            messageDiv.innerText = 'OTP सत्यापित झाला!';
            messageDiv.className = 'message success';

            // Redirect to another page after OTP verification
            setTimeout(function() {
                window.location.href = "registration.php";  // Change this to your desired URL
            }, 1000);  // Delay of 1 second before redirecting
        } else {
            messageDiv.innerText = 'अवैध OTP, कृपया पुन्हा प्रयत्न करा.';
            messageDiv.className = 'message error';

            // Clear the OTP fields on incorrect OTP
            clearOTPInputs();

            // Generate a new OTP on error
            generateOTP();
        }

        hideMessage();
    }

    // Function to hide message after 1 second
    function hideMessage() {
        setTimeout(function() {
            document.getElementById('message').innerText = '';
        }, 1000);
    }

    // Automatically move to the next input field for OTP
    document.querySelectorAll('.otp-container input').forEach((input, index, inputs) => {
        input.addEventListener('input', () => {
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });
    });

    // Generate initial captcha on page load
    generateCaptcha();
</script>

</body>
</html>
