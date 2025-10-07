<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GaleriApiController;

Route::get('/galeri-items', [GaleriApiController::class, 'index']);