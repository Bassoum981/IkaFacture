<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LigneFacture extends Model
{
    protected $table = 'lignes_facture';
    protected $guarded = [];

    public function facture():BelongsTo{
        return $this->belongsTo(Facture::class);
    }

}
