<?php

use App\Http\Controllers\DeliverySlotsController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::post('/weather', [WeatherController::class, 'weather']);
Route::get('/delivery-slots', [DeliverySlotsController::class, 'deliverySlots']);
