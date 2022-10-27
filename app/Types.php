<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PokemonTypes;


class Types extends Model
{
    protected $primaryKey = 'type_id';

    //
    public function pokemontypes(){
        return $this->hasMany(PokemonTypes::class);
    }
}
