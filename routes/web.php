<?php

use Illuminate\Support\Facades\Route;
use Platform\FoodService\Livewire\Dashboard as FoodServiceDashboard;
use Platform\FoodService\Livewire\Allergen\Index as AllergenIndex;
use Platform\FoodService\Livewire\Allergen\Allergen as AllergenShow;
use Platform\FoodService\Livewire\Additive\Index as AdditiveIndex;
use Platform\FoodService\Livewire\Additive\Additive as AdditiveShow;
use Platform\FoodService\Livewire\Attribute\Index as AttributeIndex;
use Platform\FoodService\Livewire\Attribute\Attribute as AttributeShow;
use Platform\FoodService\Livewire\VatCategory\Index as VatCategoryIndex;
use Platform\FoodService\Livewire\VatCategory\VatCategory as VatCategoryShow;

Route::get('/', FoodServiceDashboard::class)->name('foodservice.dashboard');

// Allergens
Route::get('/allergens', AllergenIndex::class)->name('foodservice.allergens.index');
Route::get('/allergens/{allergen}', AllergenShow::class)->name('foodservice.allergens.show');

// Additives
Route::get('/additives', AdditiveIndex::class)->name('foodservice.additives.index');
Route::get('/additives/{additive}', AdditiveShow::class)->name('foodservice.additives.show');

// Attributes
Route::get('/attributes', AttributeIndex::class)->name('foodservice.attributes.index');
Route::get('/attributes/{attribute}', AttributeShow::class)->name('foodservice.attributes.show');

// VAT Categories
Route::get('/vat-categories', VatCategoryIndex::class)->name('foodservice.vat-categories.index');
Route::get('/vat-categories/{category}', VatCategoryShow::class)->name('foodservice.vat-categories.show');



