<?php
    require_once 'session/auth_session.php';
    function authenticate() {
        $auth = new AuthSession();
        if (!$auth->isLogged()) {
            header('Location: /login');
            exit();
        }
    }
?>