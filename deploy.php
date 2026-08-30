<?php
/**
 * Auto-Deploy Webhook for sidesacandirejo.com
 */

error_reporting(0);
ini_set('display_errors', '0');

$secret_token = 'sidesa_candirejo_deploy_2026';

// Verifikasi token keamanan
$incoming_token = isset($_GET['token']) ? $_GET['token'] : '';

if ($incoming_token !== $secret_token) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid security token']);
    exit();
}

// Pastikan PATH berisi lokasi git
putenv('PATH=' . getenv('PATH') . ':/usr/local/bin:/usr/bin:/bin:/usr/local/cpanel/3rdparty/bin');

$output = [];
$return_var = 0;

// Cari path git
$git = 'git';
if (file_exists('/usr/local/cpanel/3rdparty/bin/git')) {
    $git = '/usr/local/cpanel/3rdparty/bin/git';
} elseif (file_exists('/usr/bin/git')) {
    $git = '/usr/bin/git';
}

$cmd = "$git fetch origin master 2>&1 && $git reset --hard origin/master 2>&1";

if (function_exists('exec')) {
    @exec($cmd, $output, $return_var);
} elseif (function_exists('shell_exec')) {
    $res = @shell_exec($cmd);
    $output = explode("\n", (string)$res);
} elseif (function_exists('passthru')) {
    ob_start();
    @passthru($cmd, $return_var);
    $output = explode("\n", (string)ob_get_clean());
} else {
    $output = ['Warning: shell execution is disabled in PHP configuration'];
}

header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => 'Deploy trigger executed',
    'return_code' => $return_var,
    'output' => $output
]);
