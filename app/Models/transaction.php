<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class transaction extends Model
{
      use HasFactory;
      
      protected $fillable = [
        'user_id',
        'type',
        'user_id',
        'categorie_id',
        'montant',
        'date',
        'description',
        'piece_jointe',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }
}
