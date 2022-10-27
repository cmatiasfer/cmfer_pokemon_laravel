<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
use Guzzle\Service\Client;
use App\Pokemon;

/**
 * @OA\Info(title="Pokemon API", version="1.0")
 */
class PokemonController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/pokemon/{name}",
     *     description="Datos de Pokemon",
     *     @OA\Response(
     *         response=200,
     *         description="Datos de pokemon en JSON.",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="pok_id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="pok_name",
     *                         type="string",
     *                     ),
     *                     @OA\Property(
     *                         property="pok_height",
     *                         type="int",
     *                     ),
     *                     @OA\Property(
     *                         property="pok_weight",
     *                         type="int",
     *                     ),
     *                     @OA\Property(
     *                         property="pok_base_experience",
     *                         type="int",
     *                     ),
     *                     @OA\Property(
     *                         property="type",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se ha encontrado pokemon.",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="msg",
     *                         type="string",
     *                     ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         description="El parametro 'name' es necesario para filtrar",
     *         required=true
     *     )
     * )
     */
    public function pokemon(Request $request, $name) {
        $data = [];
        $pokemon = Pokemon::where('pok_name','=',$name)
        ->orderBy('pok_id', 'asc')
        ->first();
        $code = 200;
        if(isset($pokemon)){
            $data["pok_id"] = $pokemon->pok_id;
            $data["pok_name"] = $pokemon->pok_name;
            $data["pok_height"] = $pokemon->pok_height;
            $data["pok_weight"] = $pokemon->pok_weight;
            $data["pok_base_experience"] = $pokemon->pok_base_experience;
            foreach($pokemon->pokemontypes as $pokeType){
                $data["type"][] = $pokeType->types->type_name;
            }
        }else{
            $code = 404;
            $data["msg"] = "No se ha encontrado pokemon con nombre: ".$name ;
        }
    
        return Response::json($data, $code);
    }
    
    /**
     * @OA\Get(
     *     path="/api/v1/pokemons/type/{type}",
     *      description="Pokemons por Tipo",
     *      @OA\Response(
     *         response=200,
     *         description="Datos de Pokemons en JSON.",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="pok_id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="pok_name",
     *                         type="string",
     *                     ),
     *                     @OA\Property(
     *                         property="pok_height",
     *                         type="int",
     *                     ),
     *                     @OA\Property(
     *                         property="pok_weight",
     *                         type="int",
     *                     ),
     *                     @OA\Property(
     *                         property="pok_base_experience",
     *                         type="int",
     *                     ),
     *                     @OA\Property(
     *                         property="type",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items
     *                     ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         description="El parametro 'type' es necesario para filtrar",
     *         required=true
     *     )
     * )
     */
    //
    public function pokemonByType(Request $request, $type) {
        $data = [];
        $pokemons = Pokemon::select()
        ->leftJoin('pokemon_types', 'pokemon.pok_id', '=', 'pokemon_types.pok_id')
        ->leftJoin('types', 'pokemon_types.type_id', '=', 'types.type_id')
        ->where('type_name','=', $type)
        ->orderBy('pokemon.pok_id', 'asc')
        ->get();
        $code = 200;
        if(count($pokemons) > 0 ){
            foreach($pokemons as $key => $pokemon){
                $data[$key]["pok_id"] = $pokemon->pok_id;
                $data[$key]["pok_name"] = $pokemon->pok_name;
                $data[$key]["pok_height"] = $pokemon->pok_height;
                $data[$key]["pok_weight"] = $pokemon->pok_weight;
                $data[$key]["pok_base_experience"] = $pokemon->pok_base_experience;
                $data[$key]["type"] = [];
                foreach($pokemon->pokemontypes as $pokeType){
                    $data[$key]["type"][]= $pokeType->types->type_name;
                }
            }
        }else{
            $code = 404;
            $data["msg"] = "No se ha encontrado el tipo de pokemon con nombre: ".$name ;
        }
    
        return Response::json($data, $code);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/team/{teamId}/pokemon/{pokemonId}",
     *     description="Agrega un nuevo pokemon al equipo",
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="pok_id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="team_id",
     *                         type="integer",
     *                     ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="ID invalid"
     *     ),
     * )
     */
    //
    public function add(){

    }
    
    /**
     * @OA\Put(
     *     path="/api/v1/team/{teamId}/pokemon/{pokemonId}",
     *     description="Edicion de team",
     *     @OA\Response(
     *         response=200,
     *         description="Agrega un pokemon a un equipo determinado",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="pok_id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="team_id",
     *                         type="integer",
     *                     ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="ID invalid"
     *     ),
     * )
     */
    public function edit(){

    }
    
    /**
     * @OA\Delete(
     *     path="/api/v1/team/{teamId}/pokemon/{pokemonId}",
     *     description="Elimina un pokemon de un equipo determinado",
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="pok_id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="team_id",
     *                         type="integer",
     *                     ),
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="ID invalid"
     *     ),
     * )
     */
    //
    public function delete(){

    }
}
