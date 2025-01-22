<?php
$conn = new mysqli($host, $username, $password, $dbname);

$conn->ssl_set(NULL, $_ENV['CA'], NULL, NULL, NULL);