<?php
require_once 'session_manager.php';

class AuthSession {
    function isLogged() {
        $session = new SessionManager();
        return $session->has('user_id');
    }

    function isAdmin() {
        $session = new SessionManager();
        return $session->has('user_id') && $session->get('user_type') == 'admin';
    }

    function logout() {
        $session = new SessionManager();
        $session->destroy();
    }
}
?>