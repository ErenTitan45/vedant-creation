<?php
// Start the session to store CAPTCHA
session_start();

// Generate a random 6-digit number for CAPTCHA
$captcha_code = rand(100000, 999999);

// Store the CAPTCHA in the session to verify later
$_SESSION['captcha'] = $captcha_code;

// Create an image with the CAPTCHA code
header('Content-Type: image/png');
$image = imagecreatetruecolor(120, 40);

// Define colors
$bg_color = imagecolorallocate($image, 255, 255, 255); // White background
$text_color = imagecolorallocate($image, 0, 0, 0); // Black text

// Fill the image background with white
imagefilledrectangle($image, 0, 0, 399, 99, $bg_color);

// Add the CAPTCHA text to the image
imagettftext($image, 20, 0, 10, 30, $text_color, './Amiri-Bold.ttf', $captcha_code);


// Output the image as PNG
imagepng($image);

// Destroy the image from memory
imagedestroy($image);
?>
