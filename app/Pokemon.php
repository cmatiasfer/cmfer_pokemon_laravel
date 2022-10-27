<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PokemonTypes;


class Pokemon extends Model
{
    protected $primaryKey = 'pok_id';
    
    //
    public function pokemontypes(){
        return $this->hasMany(PokemonTypes::class,'pok_id');
    }
}
