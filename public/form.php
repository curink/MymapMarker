<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\Models\Location;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $address = $_POST["address"] ?? "";
  $lat = (float) ($_POST["lat"] ?? 0);
  $lng = (float) ($_POST["lng"] ?? 0);

  if ($address && $lat && $lng) {
    Location::create($address, $lat, $lng);
  }
    // Redirect balik ke index setelah simpan
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>📍 Simpan lokasi</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>📍 Simpan Lokasi</h1>
    <form method="POST" class="card">
        <div class="grid">
            <input type="text" name="address" placeholder="Address" required>
            <input type="number" step="any" name="lat" placeholder="Latitude" value='<?= $_GET["lat"] ?>' required>
            <input type="number" step="any" name="lng" placeholder="Longitude" value='<?= $_GET["lng"] ?>' required>
            <button type="submit">Simpan</button>
        </div>
    </form>
    <p><a href="index.php">← Kembali</a></p>
</body>
</html>