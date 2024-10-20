<?php
    // Start the session if not already started
function start_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Set user as logged in
function set_logged($username, $level) {
    start_session();
    $_SESSION['ss_user_token'] = array(
        'username' => $username,
        'level' => $level
    );
}

// Set user as logged out
function set_logout() {
    start_session();
    unset($_SESSION['ss_user_token']);
}

// Check if the user is logged in
function is_logged() {
    start_session();
    return isset($_SESSION['ss_user_token']) ? $_SESSION['ss_user_token'] : null;
}

// Check if the user is an admin
function is_admin() {
    $user = is_logged();
    return !empty($user['level']) && $user['level'] === '1';
}
