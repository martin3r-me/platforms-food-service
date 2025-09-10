<?php

use Illuminate\Support\Facades\Route;
use Platform\FoodService\Livewire\Dashboard as FoodServiceDashboard;
use Platform\FoodService\Livewire\Allergen\Index as AllergenIndex;
use Platform\FoodService\Livewire\Allergen\Allergen as AllergenShow;
use Platform\FoodService\Livewire\Additive\Index as AdditiveIndex;
use Platform\FoodService\Livewire\Additive\Additive as AdditiveShow;
use Platform\FoodService\Livewire\Attribute\Index as AttributeIndex;
use Platform\FoodService\Livewire\Attribute\Attribute as AttributeShow;

Route::get('/', FoodServiceDashboard::class)->name('food-service.dashboard');

// Allergens
Route::get('/allergens', AllergenIndex::class)->name('food-service.allergens.index');
Route::get('/allergens/{allergen}', AllergenShow::class)->name('food-service.allergens.show');

// Additives
Route::get('/additives', AdditiveIndex::class)->name('food-service.additives.index');
Route::get('/additives/{additive}', AdditiveShow::class)->name('food-service.additives.show');

// Attributes
Route::get('/attributes', AttributeIndex::class)->name('food-service.attributes.index');
Route::get('/attributes/{attribute}', AttributeShow::class)->name('food-service.attributes.show');



