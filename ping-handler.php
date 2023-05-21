<?php
// Get the address from the AJAX request
$address = $_POST['address'];

// Determine the user agent
$userAgent = $_SERVER['HTTP_USER_AGENT'];

// Check if the user agent is Windows
if (strpos($userAgent, 'Windows') !== false) {
    // Windows platform
    exec("ping -n 4 " . escapeshellarg($address), $output, $returnCode);
} else {
    // Assume Linux or other platform
    exec("ping -c 4 " . escapeshellarg($address), $output, $returnCode);
}

if ($returnCode === 0) {
    // Successful ping
    $response = array('result' => implode(PHP_EOL, $output));
} else {
    // Error occurred while pinging
    $response = array('error' => 'Error occurred while pinging.');
}

// Send the JSON response back to the frontend
header('Content-Type: application/json');
echo json_encode($response);
exit;

?>