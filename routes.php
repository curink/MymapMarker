<?php

use App\Controllers\HomeController;

$routes = [
    'GET' => [
      '' => [HomeController::class, 'index'],
      'form' => [HomeController::class, 'form'],
      'edit/{id}' => [HomeController::class, 'edit'],
      'destroy/{id}' => [HomeController::class, 'destroy']
    ],
    'POST' => [
      'add' => [HomeController::class, 'add']
    ],
    'PUT'  => [
         'update/{id}' => [HomeController::class, 'update']
     ],
      'DELETE' => [
          'destroy/{id}' => [HomeController::class, 'destroy']
      ]
];