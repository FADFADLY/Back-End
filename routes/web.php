<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', function () {
    return view('welcome');
});


Livewire::setScriptRoute(function($handle) {
    return Route::get('fadfadly/public/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function($handle) {
    return Route::get('fadfadly/public/livewire/update', $handle);
});
