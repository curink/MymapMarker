<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Models\Location;

$locations = Location::all();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>📍 Futuristic Location Manager</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.84.2/dist/L.Control.Locate.min.css" />
  <link href="/assets/Leaflet.AnimatedSearchBox.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-extra-markers/1.2.1/css/leaflet.extra-markers.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link href="/assets/style.css" rel="stylesheet">
</head>
<body>
  <h1>📍 Futuristic Location Manager</h1>
  <div id="map" style="width: 97%; height: 75vh"></div>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.84.2/dist/L.Control.Locate.min.js" charset="utf-8"></script>
    <script src="/assets/Leaflet.AnimatedSearchBox.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fuse.js@5.0.10-beta/dist/fuse.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-extra-markers/1.2.1/js/leaflet.extra-markers.min.js"></script>
    <script>
      var mapOptions = {
        center: [-6.2889, 106.7181],
        zoom: 10
      };
  
      var map = new L.map("map", mapOptions);
      var layer = new L.TileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {attribution: 'By curink © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'});
  
      map.addLayer(layer);
      map.locate({setView: 'once', maxZoom: 18});
      var lc = L.control.locate({
        position: "bottomright",
        flyTo: true,
        icon: 'fa-solid fa-location-crosshairs',
      }).addTo(map);
      lc.start();
  
      var markers = '<?= json_encode($locations) ?>';
      var markers1 = JSON.parse(markers);
      var addr = markers1.map(data => data.address);
      var searchbox = L.control.searchbox({
        position: 'topright',
        expand: 'left'
      }).addTo(map);
  
      var fuse = new Fuse(addr, {
        shouldSort: true,
        threshold: 0.6,
        location: 0,
        distance: 100,
        minMatchCharLength: 1
      });
  
      searchbox.onInput("keyup", function (e) {
        if (e.keyCode == 13) {
          search();
        } else {
          var value = searchbox.getValue();
          if (value != "") {
            var results = fuse.search(value);
            searchbox.setItems(results.map(res => res.item).slice(0, 5));
          } else {
            searchbox.clearItems();
          }
        }
      });
  
      searchbox.onButton("click", search);
      
      var marker = {};
  
      for (var i=0; i<markers1.length; i++) {
        const id = markers1[i]['id'];
        const address = markers1[i]['address'];
        const lat = markers1[i]['lat'];
        const lng = markers1[i]['lng'];
        onpop(id,address,lat,lng);
      };
      
      map.on('click', function(e) {
        var clickedLatLng = e.latlng; // This is a L.LatLng object
        var latitude = clickedLatLng.lat;
        var longitude = clickedLatLng.lng;
    
        searchbox.hide()
    
        if (marker != undefined) {
          map.removeLayer(marker);
        };
        marker = L.marker(clickedLatLng).addTo(map);
        marker.bindPopup(latitude.toFixed(6) +','+ longitude.toFixed(6) + '<br><a href="geo:0,0?q=' + latitude + ',' + longitude + '" target="_blank">Open Map</a> &nbsp &nbsp<a href="form.php?lat=' + latitude + '&lng=' + longitude  + '">Add</a>').openPopup();
      });
      
      function search() {
        var value = searchbox.getValue();
        if (value != "") {
          var data1 = markers1.find(data => data.address === value);
          var results = fuse.search(value)
          onpop(data1.id,value,data1.lat,data1.lng).openPopup();
          map.flyTo([data1.lat,data1.lng],18);
        }

        setTimeout(function () {
          searchbox.hide();
          searchbox.clear();
        }, 600);
      }
      
      function onpop(d1,d2,d3,d4){
        return L.marker([d3,d4]).addTo(map).bindPopup(d2 + '<br><a href="geo:0,0?q=' + d3 + ',' + d4 + '">Nav</a> <a href="/edit.php?id=' + d1 + '">Edit</a> <a href="/delete.php?id=' + d1 + '">Hapus</a>').on('click', function(e){map.removeLayer(marker)});
      }
    </script>
</body>
</html>