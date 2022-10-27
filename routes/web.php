<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* 
Route::get('/', function () {
    return view('welcome');
}); 
*/
Route::get('/api/v1/pokemon/{name}', [ PokemonController::class, 'pokemon' ])->name('pokemon');
Route::get('/api/v1/pokemons/type/{type}', [ PokemonController::class, 'pokemonByType' ])->name('pokemon_by_type');

