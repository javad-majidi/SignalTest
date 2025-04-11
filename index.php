<?php
/**
 * Backend Developer Test Challenge - Signal
 *
 * This API creates a file with each request and stores the request content as JSON.
 * File naming follows a rotating counter pattern from 100.txt down to 1.txt, then back to 100.txt.
 */

// Set content type to JSON
header('Content-Type: application/json');

// Function to get the next filename in sequence
function getNextFilename() {
    $baseDir = './data/'; // Directory to store files

    // Create directory if it doesn't exist
    if (!is_dir($baseDir)) {
        mkdir($baseDir, 0755, true);
    }

    // Get all existing txt files
    $files = glob($baseDir . '*.txt');

    // If no files exist or 1.txt was the last one created, return 100.txt
    if (empty($files) || in_array($baseDir . '1.txt', $files)) {
        return $baseDir . '100.txt';
    }

    // Find the highest numbered file
    $lowestNumber = (int)pathinfo(basename($files[0]), PATHINFO_FILENAME);
    foreach ($files as $file) {
        $filename = basename($file);
        $number = (int)pathinfo($filename, PATHINFO_FILENAME);
        if ($number < $lowestNumber) {
            $lowestNumber = $number;
        }
    }

    // Return one number less as the new filename
    return $baseDir . ($lowestNumber - 1) . '.txt';
}

// Get request content
$requestData = file_get_contents('php://input');
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestHeaders = getallheaders();

// Prepare data to store
$dataToStore = [
    'method' => $requestMethod,
    'headers' => $requestHeaders,
    'content' => json_decode($requestData, true) ?: $requestData,
    'timestamp' => date('Y-m-d H:i:s')
];

// Get the next filename
$filename = getNextFilename();

// Store the JSON data in the file
$result = file_put_contents($filename, json_encode($dataToStore, JSON_PRETTY_PRINT));

if ($result !== false) {
    // Success response
    $response = [
        'status' => 'success',
        'message' => 'Request data saved successfully',
        'filename' => basename($filename)
    ];
    http_response_code(200);
} else {
    // Error response
    $response = [
        'status' => 'error',
        'message' => 'Failed to save request data'
    ];
    http_response_code(500);
}

// Return response
echo json_encode($response);
?>