<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $guarded = [];

    //Le client appartient à un user

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    //Un client peut avoir plusieurs factures
    public function factures():HasMany{
        return $this->hasMany(Facture::class);
    }
}
