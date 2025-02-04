<?php
if (isset($_FILES['file1'])) {
    $file = $_FILES['file1'];

    if ($file['error'] === 0) {
        $file_name = $file['name'];
        $tmp_name = $file['tmp_name'];
        $location = '../../assets/img/' ;

        if (!is_dir($location)) {
            mkdir($location, 0777, true);
        }

        if (move_uploaded_file($tmp_name, $location . $file_name)) {
            echo "File uploaded successfully!";
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "Upload Error: " . $file['error'];
    }
} else {
    echo "No file uploaded.";
}

?>
