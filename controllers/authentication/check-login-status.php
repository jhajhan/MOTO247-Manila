<?php
class Login {
    function index($authSession) {
        // Ensure clean output (no errors, no whitespace)
        header('Content-Type: application/json');
        ob_clean();  // Remove any previous output
        flush();     // Clear buffer

        // Check if user is logged in
        $response = [
            "message" => $authSession->isLogged() ? "success" : "yoww",
            "status" => $authSession->isLogged() ? "success" : "error",
            "isLoggedIn" => $authSession->isLogged()
        ];

        echo json_encode($response);
        exit();  // Prevent any extra output
    }
}
?>
