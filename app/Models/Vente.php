<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vente extends Model
{
    protected $table = 'VENTE';

    protected $primaryKey = 'vente_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false; // Pas de created_at/updated_at

    public static $snakeAttributes = false;

    protected $fillable = [
        'recolte_id',
        'date_vente',
        'quantite_vendue_kg',
        'prix_unitaire_kg',
    ];

    public function recolte(): BelongsTo
    {
        return $this->belongsTo(Recolte::class, 'recolte_id', 'recolte_id');
    }

    // Accesseurs pour affichage facile
    public function getProduitNomAttribute()
    {
        return $this->recolte?->variete?->produit?->nom_produit;
    }

    public function getVarieteNomAttribute()
    {
        return $this->recolte?->variete?->nom_variete;
    }

    public function getDateRecolteAttribute()
    {
        return $this->recolte?->date_recolte;
    }
}
