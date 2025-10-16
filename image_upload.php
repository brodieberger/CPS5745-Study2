<?php
if (isset($_FILES['myimage'])) {
    $file = $_FILES['myimage'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {

        $check = getimagesize($file['tmp_name']);
        if ($check == false) {
            echo "File is not an image.";
        }
        else{
            $imageData = base64_encode(file_get_contents($file['tmp_name']));
            $src = 'data:' . $check['mime'] . ';base64,' . $imageData;
            
            //https://www.php.net/manual/en/features.file-upload.post-method.php
            echo "<h1>Your Uploaded Image:</h1>";
            echo "<img src='$src' alt='Uploaded Image' style='max-width: 500px; border: 1px solid #ccc;'>";
            echo "<p>File: " . htmlspecialchars($file['name']) . "</p>";
            echo "<p>Type: " . htmlspecialchars($check['mime']) . "</p>";
            echo "<p>Size: " . htmlspecialchars($file['size']) . " bytes</p>";
        }

    } else {
        echo "Error uploading file.";
    }
} else {
    echo "No image was uploaded.";
}
?>