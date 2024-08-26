<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::get("/", [MovieController::class, "index"]);
Route::get("/getMovies", [MovieController::class, "getMovies"]);
Route::post("/createMovie", [MovieController::class, "create"]);
Route::post("/updateMovie", [MovieController::class, "update"]);
Route::post("/deleteMovie", [MovieController::class, "delete"]);
