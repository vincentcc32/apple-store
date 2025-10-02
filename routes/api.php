<?php

use App\Http\Controllers\messageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sactum')->group(function () {


    // Route::post("/message/store", [messageController::class, 'store'])->name('message.store');
    // Route::delete("/message/delete/{id}", [messageController::class, 'destroy'])->name('message.destroy');
});
