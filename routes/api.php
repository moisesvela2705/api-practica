<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

// ====================================================================
// RUTAS PÚBLICAS (no requieren token)
// ====================================================================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/health', function () {
    return response()->json([
        'status'  => 'OK',
        'message' => 'API funcionando correctamente',
        'version' => '2.0 - Con autenticación',
    ], 200);
});

// ====================================================================
// RUTAS PROTEGIDAS (requieren token válido)
// ====================================================================

Route::middleware('auth:sanctum')->group(function () {

    // --- Autenticación ---
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // --- CRUD Productos ---
    Route::apiResource('productos', ProductoController::class);

});