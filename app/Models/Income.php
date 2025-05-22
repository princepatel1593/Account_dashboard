<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'title',
        'date',
        'amount',
        'note',
    ];

    // Relationship
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
