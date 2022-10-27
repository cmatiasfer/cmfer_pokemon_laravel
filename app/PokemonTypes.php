<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Pokemon;
use App\Types;

class PokemonTypes extends Model
{
    protected $primaryKey = 'pok_id';

   //
    public function pokemon(){
        return $this->belongsTo(Pokemon::class,'pok_id');
    }

    public function types(){
        return $this->belongsTo(Types::class,'type_id');
    }
}
