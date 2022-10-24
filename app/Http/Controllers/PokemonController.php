<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Guzzle\Service\Client;

class PokemonController extends Controller
{
    //
    public function index() {
        $users = DB::table('users')->get();

        $response = Http::get('https://pokeapi.co/api/v2/pokemon/', [
            'limit' => 12
        ]);
        /* dd($response); */
        $pokemons = $response->json();
     /*   dd(gettype($pokemons)); */
        return $pokemons;
    }
}
