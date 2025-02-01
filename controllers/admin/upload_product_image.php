<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] == 0) {
        $uploadedFile = $_FILES['productImage'];
        $uploadDirectory = 'assets/img/';  // Folder where the image will be saved

        // Get the file name and generate a unique name
        $fileName = basename($uploadedFile['name']);
        $uniqueFileName = uniqid() . '_' . $fileName;

        $targetPath = $uploadDirectory . $uniqueFileName;

        // Check if the file is an image (optional)
        $fileType = mime_content_type($uploadedFile['tmp_name']);
        if (strpos($fileType, 'image') !== false) {
            if (move_uploaded_file($uploadedFile['tmp_name'], $targetPath)) {
                // File uploaded successfully
                echo json_encode(['status' => 'success', 'imageUrl' => '/assets/img/' . $uniqueFileName]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'File is not an image']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded or error in upload']);
    }
}
?>