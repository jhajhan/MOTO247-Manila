<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];

// $host = 'localhost';
// $username = 'root';
// $password = '';
// $dbname = 'moto247_manila';



$conn = new mysqli($host, $username, $password, $dbname, 4000);
$conn->ssl_set(NULL, NULL, '/isrgrootx1.pem', NULL, NULL);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    
} else {
    echo "Connected successfully";
}


// $query = 'SELECT * FROM product';
// $result = mysqli_query($conn, $query);
// $ex = mysqli_fetch_assoc($result);
// echo $ex['name'];


// ?>