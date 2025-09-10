<?php

use Illuminate\Support\Facades\Route;

Route::get('/', Platform\FoodService\Livewire\Dashboard::class)->name('food-service.dashboard');



