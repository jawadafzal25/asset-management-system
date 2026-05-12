<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-webhook', function () {
    // Yeh line jaan boojh kar 500 Internal Server Error throw karegi
    throw new \Exception('Ahsan is testing the WhistleIt Webhook! This is a fake crash.');
});