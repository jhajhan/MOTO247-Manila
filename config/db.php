<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];

global $conn;




$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    
} else {
    // echo "Connected successfully";
}


// $query = 'SELECT * FROM product';
// $result = mysqli_query($conn, $query);
// $ex = mysqli_fetch_assoc($result);
// echo $ex['name'];


// ?>