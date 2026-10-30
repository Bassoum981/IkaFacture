<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    protected $guarded = [];

    //La facture peut avoir qu'un utilisateur createur(user)

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    //La facture peut etre lièe à un client enregistre
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Une facture contient plusieurs lignes de prestations
    public function lignes_facture():HasMany
    {
        return $this->hasMany(LigneFacture::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneFacture::class, 'facture_id'); // Adapte 'LigneFacture' au nom exact de ton modèle de ligne
    }
}
