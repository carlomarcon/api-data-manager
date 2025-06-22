<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DynamicModelController;
use Illuminate\Support\Facades\Route;

// Rotte pubbliche per l'autenticazione
Route::post("/login", [AuthController::class, "login"]);

// Rotte protette da Sanctum
Route::middleware("auth:sanctum")->group(function () {
  Route::post("/logout", [AuthController::class, "logout"]);

  // Rotte del motore dinamico
  Route::post("/insert", [DynamicModelController::class, "insert"]);
  Route::post("/update", [DynamicModelController::class, "update"]);
  Route::post("/update-multiple", [
    DynamicModelController::class,
    "updateMultiple",
  ]);
});
