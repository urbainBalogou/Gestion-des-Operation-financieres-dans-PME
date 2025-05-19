<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
     protected $fillable = ['libelle'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'categorie_id');
    }
    //
}
