<?php
namespace App\Controllers;

use App\Core\View;
use App\Models\Location;

class HomeController {
    public function index() {
        session_start();
        $locations = Location::all();
        View::render('home', ['locations' => $locations]);
        if (isset($_SESSION['alert'])) {
            echo "<script>alert('{$_SESSION['alert']}');</script>";
            unset($_SESSION['alert']); // hanya tampil sekali
        }
    }

    public function form() {
        View::render('form');
    }
    
    public function add() {
            session_start();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
          $address = $_POST["address"] ?? "";
          $lat = (float) ($_POST["lat"] ?? 0);
          $lng = (float) ($_POST["lng"] ?? 0);
        
          if ($address && $lat && $lng) {
            Location::create($address, $lat, $lng);
          }
            // Redirect balik ke index setelah simpan
            header("Location: /");
            $_SESSION['alert'] = 'Data berhasil disimpan!';
            exit();
        }
    }

    public function edit($id) {
        $location = Location::findById($id);
        
        if (!$location) {
          echo "<h2>Data tidak ditemukan.</h2>";
          exit();
        }
        View::render('edit', ['locations' => $location]);
    }
    
    public function update($id) {
            session_start();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
          $address = $_POST["address"] ?? "";
          $lat = (float) ($_POST["lat"] ?? 0);
          $lng = (float) ($_POST["lng"] ?? 0);
        
          if ($address && $lat && $lng) {
            Location::update($id, $address, $lat, $lng);
            header("Location: /");
            $_SESSION['alert'] = 'Data berhasil diubah!';
            exit();
          }
        }
    }

    public function destroy($id) {
        session_start();
        $location = Location::findById($id);
        //if ($_SERVER["REQUEST_METHOD"] === "POST") {
        Location::delete($id);
        header("Location: /");
        $_SESSION['alert'] = 'Data berhasil dihapus!';
        exit();
        //}
    }
}