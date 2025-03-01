<?php

class SessionManager {

    function start() {
        // Ensure no session has started before setting cookie parameters
        if (session_status() == PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 86400,  // Time in seconds; here it is set for one day
                'path' => '/',        // Available for the entire domain
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',  // Secure only if HTTPS is enabled
                'httponly' => true,   // Prevent JavaScript access to the cookie
            ]);

            session_start(); // Start the session
        }
    }

    function set($key, $value) { // Set session
        $_SESSION[$key] = $value;
    }

    function get($key) { // Get session
        return $_SESSION[$key] ?? null; // Safe to return null if the key doesn't exist
    }

    function has($key) { // Check if session exists
        return isset($_SESSION[$key]);
    }

    function destroy() { // Destroy session
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();  // Unset session variables
            session_destroy(); // Destroy the session
        }
    }
}

?>
