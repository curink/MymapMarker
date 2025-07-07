<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\Models\Location;

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$location = Location::findById($id);

if (!$location) {
  echo "<h2>Data tidak ditemukan.</h2>";
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $address = $_POST["address"] ?? "";
  $lat = (float) ($_POST["lat"] ?? 0);
  $lng = (float) ($_POST["lng"] ?? 0);

  if ($address && $lat && $lng) {
    Location::update($id, $address, $lat, $lng);
    header("Location: index.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>📍 Edit Lokasi</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>📍 Edit Lokasi</h1>
    <form method="POST" class="card">
        <div class="grid">
            <input type="text" name="address" value="<?= htmlspecialchars(
              $location["address"]
            ) ?>" required>
            <input type="number" step="any" name="lat" value="<?= $location[
              "lat"
            ] ?>" required>
            <input type="number" step="any" name="lng" value="<?= $location[
              "lng"
            ] ?>" required>
            <button type="submit">Ubah</button>
        </div>
    </form>
    <p><a href="index.php">← Kembali</a></p>
</body>
</html>