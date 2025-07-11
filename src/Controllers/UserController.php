<?php
namespace App\Controllers;

class UserController {
    public function show($id) {
        echo "<h1>Profil User ID: $id</h1>";
    }

    public function edit($id) {
        echo "<h1>Edit User ID: $id</h1>";
    }
}