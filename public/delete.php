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
  Location::delete($id);
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>📍 Hapus Lokasi</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

  <h1>📍 Hapus Lokasi</h1>

  <p>Yakin ingin menghapus lokasi berikut?</p>
  <ul>
    <li><strong>Alamat:</strong> <?= htmlspecialchars(
      $location["address"]
    ) ?></li>
    <li><strong>Lat:</strong> <?= $location[
      "lat"
    ] ?> | <strong>Lng:</strong> <?= $location["lng"] ?></li>
  </ul>

  <form method="POST">
    <button type="submit">Hapus</button>
    <a href="index.php">Batal</a>
  </form>

</body>
</html>