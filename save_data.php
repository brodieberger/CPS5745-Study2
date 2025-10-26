<?php
include 'dbcredentials.php';

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['pixelData'])) {
    $pixelData = json_decode($_POST['pixelData'], true);
    $imageSizeData = json_decode($_POST['imageSizeData'], true);
    $fileName = 'sample.png'; // Temp

    echo "Image size data<br>";

    echo "Width: " . abs($imageSizeData[0] - $imageSizeData[2]);
    echo "<br>Height: " . abs($imageSizeData[1] - $imageSizeData[3]);
    
    echo "<br><br>";

    echo count($pixelData);
    for ($num = 0; $num < count($pixelData); $num+= 4){
        echo "<br> Red: " . $pixelData[$num];
        echo " Green: " . $pixelData[$num+1];
        echo " Blue: " . $pixelData[$num+2];
        echo " Transparancy: " . $pixelData[$num+3];
    }
} else {
    echo "No pixel data received.";
}
?>