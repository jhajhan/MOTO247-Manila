<?php
if (isset($_FILES['file1'])) {
    $file = $_FILES['file1'];

    if ($file['error'] === 0) {
        $file_name = $file['name'];
        $tmp_name = $file['tmp_name'];

        // Define the upload directory relative to the root
        $location = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/';  // Absolute path to 'assets/img'


        // Ensure the upload directory exists
        if (!is_dir($location)) {
            mkdir($location, 0777, true);
        }

        // Move the uploaded file to the directory
        if (move_uploaded_file($tmp_name, $location . $file_name)) {
            // Construct the file URL relative to your public directory (you might need to adjust this part)
            $file_url = '/assets/img/' . $file_name;

            // Return a JSON response with the image URL
            echo json_encode(['status' => 'success', 'imageUrl' => $file_url]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Upload Error: ' . $file['error']]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded.']);
}
?>
