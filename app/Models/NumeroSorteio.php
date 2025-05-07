<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumeroSorteio extends Model
{
    use HasFactory;

    protected $fillable = [
        'cupom_id',
        'numero',
    ];

    public function cupom()
    {
        return $this->belongsTo(Cupom::class);
    }
}
