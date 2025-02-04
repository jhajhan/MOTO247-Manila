<?php

    class SessionManager {

        function start() {
            if (session_status() == PHP_SESSION_NONE) { // Check if session is not started
                session_start();
            }
        }

        function set($key, $value) { // Set session
            $_SESSION[$key] = $value;
        }

        function get($key) { // Get session
            return $_SESSION[$key];
        }

        function has($key) { // Check if session exists
            return isset($_SESSION[$key]);
        }

        function destroy() { // Destroy session
            if (session_status() == PHP_SESSION_ACTIVE) { // Check if session is started
                session_destroy();
            }
        }
    }
?>