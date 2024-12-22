<?php

namespace App\Models;

use App\Traits\ModelScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Affiliate extends Model
{
    use HasFactory, ModelScopeTrait;

    protected $fillable = [
        'discount',
        'amount',
        'user_id',
        'user_buy_id',
        'product_id',
        'affiliate_link_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function getRevenue(string $type, bool $isPast = false)
    {
        $query = self::query()
            ->join('affiliate_links', 'affiliates.affiliate_link_id', '=', 'affiliate_links.id')
            ->filter($type, $isPast, 'affiliates');

        return $query->sum(DB::raw('price * amount'));
    }

    public static function getDiscountPaid(string $type, bool $isPast = false)
    {
        $query = self::query()
            ->filter($type, $isPast);

        return $query->sum(DB::raw('discount * amount'));
    }

    public function affiliateLink()
    {
        return $this->belongsTo(AffiliateLink::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
