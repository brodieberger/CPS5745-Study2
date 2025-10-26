function renderImage() {
  const canvas = document.getElementById("myCanvas");
  const ctx = canvas.getContext("2d");
  const img = document.getElementById("scream");

  let scale = getMaxDimensions(img);

  const scaledWidth = img.width * scale;
  const scaledHeight = img.height * scale;
  ctx.canvas.width = scaledWidth;
  ctx.canvas.height = scaledHeight;
  ctx.drawImage(img, 0, 0, scaledWidth, scaledHeight);
}

function getMaxDimensions(img) {
  const maxWidth = window.innerWidth * 0.8;
  const maxHeight = window.innerHeight * 0.8;
  let scale = 1;

  if (img.width > maxWidth) {
    let widthScale = maxWidth / img.width;
    scale = widthScale;
  }
  if (img.height > maxHeight) {
    let heightScale = maxHeight / img.height;
    scale = Math.min(heightScale, scale);
  }
  return scale;
}

function showCoords(event) {
  // Coords
  let x = event.offsetX;
  let y = event.offsetY;
  let text = "X coords: " + x + ", Y coords: " + y;
  document.getElementById("demo").innerHTML = text;

  // Draw
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  if (isMouseDown) {
    ctx.clearRect(0, 0, c.width, c.height);
    renderImage();
    x = event.offsetX - firstX;
    y = event.offsetY - firstY;
    ctx.rect(firstX, firstY, x, y);
    ctx.stroke();
  }

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

let isMouseDown = false;
let firstX = 0;
let firstY = 0;
function mouseDown(event) {
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  ctx.clearRect(0, 0, c.width, c.height);
  renderImage();
  firstX = event.offsetX;
  firstY = event.offsetY;
  isMouseDown = true;
}

function mouseUp(event) {
  let element = document.getElementById("chartText");
  element.innerHTML = "";

  isMouseDown = false;
  var c = document.getElementById("myCanvas");
  var ctx = c.getContext("2d");
  ctx.clearRect(0, 0, c.width, c.height);
  renderImage();
  const imageData = ctx.getImageData(
    firstX,
    firstY,
    event.offsetX - firstX,
    event.offsetY - firstY
  );
  ctx.clearRect(0, 0, c.width, c.height);
  renderImage();
  x = event.offsetX - firstX;
  y = event.offsetY - firstY;
  ctx.rect(firstX, firstY, x, y);
  ctx.stroke();

  // send stuff to HTML to go to PHP
  var pixelData = imageData.data;
  var pixelDataJSON = JSON.stringify(Array.from(pixelData));
  document.getElementById("pixelData").value = pixelDataJSON;

  var imageSizeData = [firstX, firstY, event.offsetX, event.offsetY];
  var imageDataJSON = JSON.stringify(Array.from(imageSizeData));
  document.getElementById("imageSizeData").value = imageDataJSON;

  processData(imageData);
  console.log(imageData.data);
}

function processData(imageData) {
  var redTotal = 0;
  var greenTotal = 0;
  var blueTotal = 0;
  for (let i = 0; i < imageData.data.length; i += 4) {
    redTotal += imageData.data[i];
    greenTotal += imageData.data[i + 1];
    blueTotal += imageData.data[i + 2];
  }
  createChart(redTotal, greenTotal, blueTotal);
}

let pieChartInstance = null;
function createChart(redTotal, greenTotal, blueTotal) {
  const ctx = document.getElementById("myChart");

  if (pieChartInstance) {
    pieChartInstance.destroy();
  }

  pieChartInstance = new Chart(ctx, {
    type: "pie",
    data: {
      labels: ["Red", "Green", "Blue"],
      datasets: [
        {
          label: "# of pixels",
          data: [redTotal, greenTotal, blueTotal],
          backgroundColor: ["red", "green", "blue"],
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
}
