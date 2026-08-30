<?php
/**
 * Auto-Deploy Webhook for sidesacandirejo.com
 */

$secret_token = 'sidesa_candirejo_deploy_2026';

// Verifikasi token keamanan
$incoming_token = isset($_GET['token']) ? $_GET['token'] : (isset($_SERVER['HTTP_X_HUB_SIGNATURE']) ? $_SERVER['HTTP_X_HUB_SIGNATURE'] : '');

if ($incoming_token !== $secret_token) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid security token']);
    exit();
}

// Jalankan git pull di direktori project
$output = [];
$return_var = 0;
exec('git pull origin master 2>&1', $output, $return_var);

header('Content-Type: application/json');
if ($return_var === 0) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Successfully pulled latest changes',
        'output' => $output
    ]);
} else {
    echo json_encode([
        'status' => 'partial_success',
        'message' => 'Executed with return code ' . $return_var,
        'output' => $output
    ]);
}
