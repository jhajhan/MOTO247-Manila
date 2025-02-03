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

// Define backup file path (you can use a temporary folder for this)
$backupPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $backupFile; // Use PHP's temp directory

// Command to execute mysqldump and save the backup to the specified file
$command = "mysqldump --opt -h $host -u $username -p$password $dbname > $backupPath";

// Execute the command to create the backup
exec($command, $output, $result);

// Check if the backup was successful
if ($result == 0 && file_exists($backupPath)) {
    // Clean the output buffer and prevent any additional output
    ob_clean();
    flush();

    // Set headers to force the browser to download the file
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
    header('Content-Length: ' . filesize($backupPath));

    // Read the file and send it to the browser
    readfile($backupPath);

    // Optionally, delete the backup file after download to save space
    unlink($backupPath);
    exit;  // Ensure no further output is sent
} else {
    // Error handling if backup fails
    echo json_encode(['error' => 'Error creating database backup.']);
}

// End output buffering and discard any output
ob_end_clean();
?>
