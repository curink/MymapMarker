<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>📍 Edit Lokasi</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
  <a href="/" class="btn-floating-back">← Kembali</a>
    <h1>📍 Edit Lokasi</h1>
    <form action="/update/<?=$locations['id']?>" method="POST" class="card">
        <div class="grid">
            <input type="hidden" name="_method" value="PUT">
            <input type="text" name="address" value="<?= htmlspecialchars($locations['address']) ?>" required>
            <input type="number" step="any" name="lat" value="<?= $locations['lat'] ?>" required>
            <input type="number" step="any" name="lng" value="<?= $locations['lng'] ?>" required>
            <button type="submit">Ubah</button>
        </div>
    </form>
</body>
</html>
<script>
  console.log($location)
</script>