<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    protected $table = 'PRODUIT';

    protected $primaryKey = 'produit_id';

    public $incrementing = true;

    public $timestamps = false;

    protected $keyType = 'int';

    // Crucial pour Oracle + colonnes en minuscules (nom_produit)
    public static $snakeAttributes = false;

    protected $fillable = ['nom_produit'];

    public function varietes(): HasMany
    {
        return $this->hasMany(Variete::class, 'produit_id', 'produit_id');
    }
}
