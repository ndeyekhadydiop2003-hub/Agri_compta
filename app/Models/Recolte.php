<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recolte extends Model
{
    /**
     * Le nom de la table dans la base de données Oracle (souvent en majuscules).
     */
    protected $table = 'RECOLTE';


    protected $primaryKey = 'recolte_id';

    /**
     * Indique que la clé primaire est auto-incrémentée.
     */
    public $incrementing = true;

    /**
     * Ce modèle n'utilise pas les colonnes created_at et updated_at.
     */
    public $timestamps = false;


    protected $fillable = [
        'variete_id',
        'date_recolte',
        'quantite_kg'
    ];

    /**
     * Convertit automatiquement la colonne de date en objet Carbon.
     */
    protected $casts = [
        'date_recolte' => 'datetime',
    ];

    /**
     * Relation : Une récolte appartient à une variété.
     * Les clés de la relation doivent aussi être en minuscules.
     */
    public function variete()
    {
        return $this->belongsTo(
            Variete::class,
            'variete_id', // Clé étrangère sur cette table (recolte)
            'variete_id'  // Clé primaire sur l'autre table (variete)
        );
    }

    /**
     * Relation : Une récolte peut avoir plusieurs ventes.
     */
    public function ventes()
    {
        return $this->hasMany(
            Vente::class,
            'recolte_id', // Clé étrangère sur la table VENTE
            'recolte_id'  // Clé locale sur cette table (recolte)
        );
    }
}
