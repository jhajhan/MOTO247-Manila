<?php
    require_once 'session/auth_session.php';

    function authenticateAdmin() {
        $auth = new AuthSession();
        if (!$auth->isAdmin()) {
            header('/login');
            exit();
        }
    }
?>