<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perte extends Model
{
    protected $table = 'perte';

    protected $primaryKey = 'perte_id';  // clé primaire

    public $incrementing = true;

    protected $fillable = [
        'variete_id',
        'date_perte',
        'quantite_kg',
        'motif_perte'
    ];

    public $timestamps = false;

    public function variete()
    {
        return $this->belongsTo(Variete::class, 'variete_id','variete_id');
    }
}
