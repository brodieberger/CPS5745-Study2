<?php
if (isset($_FILES['myimage'])) {
    $file = $_FILES['myimage'];

    if ($file['error'] === UPLOAD_ERR_OK) {

        $check = getimagesize($file['tmp_name']);
        if ($check == false) {
            echo "File is not an image.";
        } else {
            $imageData = base64_encode(file_get_contents($file['tmp_name']));
            $src = 'data:' . $check['mime'] . ';base64,' . $imageData;
            echo '<img id="scream" src="' . $src . '" alt="The Scream" style="display: none" />';
            echo "<h1>Your Uploaded Image:</h1>";
            echo '<canvas id="myCanvas" onmousemove="showCoords(event)" style="border:1px solid black"></canvas>';
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
<html>

<body>
    <p id="demo">Coordinates:</p>
    <p id="rgb">RGB:</p>

    <div>
        <label><input type="checkbox" id="redChannel" checked onchange="updateCanvas()"> Red</label>
        <label><input type="checkbox" id="greenChannel" checked onchange="updateCanvas()"> Green</label>
        <label><input type="checkbox" id="blueChannel" checked onchange="updateCanvas()"> Blue</label>
    </div>

    <script>
        function showCoords(event) {
            // Coords
            let x = event.offsetX;
            let y = event.offsetY;
            let text = "X coords: " + x + ", Y coords: " + y;
            document.getElementById("demo").innerHTML = text;

            // RGB
            var canvas = document.getElementById("myCanvas");
            var ctx = canvas.getContext("2d");

            var pixelData = ctx.getImageData(x, y, 1, 1).data;

            var r = pixelData[0];
            var g = pixelData[1];
            var b = pixelData[2];

            var rgbText = "RGB: (" + r + ", " + g + ", " + b + ")";
            document.getElementById("rgb").innerHTML = rgbText;
        }

        function myCanvas() {
            var c = document.getElementById("myCanvas");
            var ctx = c.getContext("2d");
            var img = document.getElementById("scream");

            ctx.canvas.width = img.width;
            ctx.canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
        }

        function updateCanvas() {
            var canvas = document.getElementById("myCanvas");
            var ctx = canvas.getContext("2d");
            var img = document.getElementById("scream");

            ctx.drawImage(img, 0, 0);

            var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            var data = imageData.data;

            var redChannel = document.getElementById("redChannel").checked;
            var greenChannel = document.getElementById("greenChannel").checked;
            var blueChannel = document.getElementById("blueChannel").checked;

            for (var i = 0; i < data.length; i += 4) {
                data[i] = redChannel ? data[i] : 0;     // Red
                data[i + 1] = greenChannel ? data[i + 1] : 0; // Green
                data[i + 2] = blueChannel ? data[i + 2] : 0;  // Blue
            }

            ctx.putImageData(imageData, 0, 0);
        }

        window.onload = myCanvas;
    </script>
</body>

</html>