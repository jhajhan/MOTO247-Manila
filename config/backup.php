<?php
// Start output buffering
ob_start();

// Include necessary files
require __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

// Load .env variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Get DB credentials from environment variables
$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];

// Set the backup file name with timestamp
$backupFile = 'backup_' . $dbname . '_' . date("Y-m-d_H-i-s") . '.sql';

// Define backup file path (temporary directory)
$backupPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $backupFile;

// Command to execute mysqldump
$command = "mysqldump --opt -h $host -u $username --password='$password' $dbname 2>&1 > $backupPath";
exec($command, $output, $result);

// Debugging: Log output to check if command runs
error_log("Backup Output: " . print_r($output, true));

// Ensure no output is sent before headers
if ($result == 0 && file_exists($backupPath)) {
    ob_clean(); // Clear buffer
    flush();

    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
    header('Content-Length: ' . filesize($backupPath));

    readfile($backupPath);
    unlink($backupPath);
    exit;
} else {
    ob_end_clean();
    echo json_encode(['error' => 'Error creating database backup.']);
}
?>
