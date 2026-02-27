<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variete extends Model
{
    protected $table = 'VARIETE';

    protected $primaryKey = 'VARIETE_ID';   // ← MAJUSCULES !

    public $incrementing = true;

    public $timestamps = false;

    protected $keyType = 'int';

    public static $snakeAttributes = false;

    protected $fillable = [
        'PRODUIT_ID',          // ← aussi en MAJUSCULES dans fillable
        'NOM_VARIETE',
        'PRIX_VENTE_STANDARD',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id', 'produit_id');
        // clé étrangère dans VARIETE    ↑           ↑ clé primaire dans PRODUIT
    }

    public function recoltes()  // renommé au pluriel, plus standard
    {
        return $this->hasMany(Recolte::class, 'VARIETE_ID', 'VARIETE_ID');
    }
}
