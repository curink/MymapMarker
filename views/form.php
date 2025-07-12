<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>📍 Simpan lokasi</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <a href="/" class="btn-floating-back">← Kembali</a>
    <h1>📍 Simpan Lokasi</h1>
    <form action="add" method="POST" class="card">
        <div class="grid">
            <input type="text" name="address" placeholder="Address" required>
            <input type="number" step="any" name="lat" placeholder="Latitude" value='<?= $_GET["lat"] ?>' required>
            <input type="number" step="any" name="lng" placeholder="Longitude" value='<?= $_GET["lng"] ?>' required>
            <button type="submit">Simpan</button>
        </div>
    </form>
</body>
</html>