<?php
header('Content-Type: application/json');

// Get province code from query parameter
$provinceCode = isset($_GET['province_code']) ? $_GET['province_code'] : '';

if (empty($provinceCode)) {
    http_response_code(400);
    echo json_encode([
        'error' => true,
        'message' => 'Province code is required'
    ]);
    exit;
}

// Fetch regencies from wilayah.id API
$url = 'https://wilayah.id/api/regencies/' . $provinceCode . '.json';

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local development
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

// Execute request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for errors
if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Failed to fetch regencies: ' . curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Return the response
if ($httpCode === 200) {
    echo $response;
} else {
    http_response_code($httpCode);
    echo json_encode([
        'error' => true,
        'message' => 'API returned status code: ' . $httpCode
    ]);
}
?>