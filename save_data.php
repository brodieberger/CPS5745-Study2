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
    $fileName = $_POST['fileName'];
    echo "Filename: " . $fileName;


    echo "<br>Image size data<br>";

    $imageWidth = abs($imageSizeData[0] - $imageSizeData[2]);
    $imageHeight = abs($imageSizeData[1] - $imageSizeData[3]);

    echo "Width: " . $imageWidth;
    echo "<br>Height: " . $imageHeight;

    echo "<br><br>";

    $stmt = $conn->prepare("INSERT INTO ShapeData (file_name, x, y, R, G, B, T) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    for ($num = 0; $num < count($pixelData); $num += 4) {
        $r = $pixelData[$num];
        $g = $pixelData[$num + 1];
        $b = $pixelData[$num + 2];
        $t = $pixelData[$num + 3];

        $x = ($num / 4) % $imageWidth + 1;
        $y = floor(($num / 4) / $imageWidth) + 1;

        $stmt->bind_param("siiiiii", $fileName, $x, $y, $r, $g, $b, $t);
        $stmt->execute();
    }
    $stmt->close();
    echo "Pixel data uploaded: ";

    
    echo count($pixelData) / 4 . " Pixels Found";
    
    for ($num = 0, $pixelCount = 1; $num < count($pixelData); $num+= 4, $pixelCount ++){
        echo "<br><b>Pixel " . $pixelCount . ":</b>";
        echo " Red: " . $pixelData[$num];
        echo " Green: " . $pixelData[$num+1];
        echo " Blue: " . $pixelData[$num+2];
        echo " Transparancy: " . $pixelData[$num+3];
    }
    
} else {
    echo "No pixel data received.";
}
?>