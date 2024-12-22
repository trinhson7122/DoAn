<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AffiliateLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'price',
        'discount',
        'link',
        'is_active',
        'expiration_date',
    ];

    public function isExpired()
    {
        return $this->expiration_date < now();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
