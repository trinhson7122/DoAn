<?php

namespace App\Http\Controllers\Client;

use App\Enums\ThongKeType;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AffiliateController extends Controller
{
    public function index()
    {
        $homeController = app()->make(HomeController::class);
        $type = request('filter', ThongKeType::MONTH->value);
        $filters = $homeController->getDashboardFilters($type);
        $activeFilter = $homeController->getFilterActive($filters);

        $totalDiscount = Affiliate::query()
            ->selectRaw('SUM(discount * amount) as totalDiscount')
            ->where('user_id', auth('web')->id())
            ->filter($type)
            ->first()?->totalDiscount ?? 0;

        $affiliates = Affiliate::query()
            ->with([
                'product',
                'product.images',
            ])
            ->where('user_id', auth('web')->id())
            ->orderByDesc('created_at')
            ->paginate();
        $user = auth('web')->user();

        return view('client.home.affiliate.dashboard', compact(
            'affiliates',
            'totalDiscount',
            'user',
            'filters',
            'type',
            'activeFilter',
        ));
    }

    public function products()
    {
        $products = Product::query()
            ->where('has_affiliate', 1)
            ->with(['images', 'affiliateLinks', 'reviews'])
            ->active()
            ->paginate();

        return view('client.home.affiliate.product', compact('products'));
    }

    public function generateLink()
    {
        $link = AffiliateLink::create([
            'user_id' => auth('web')->id(),
            'product_id' => request('product_id'),
            'price' => request('price'),
            'discount' => request('discount'),
            'expiration_date' => now()->addDays(7),
            'link' => Hash::make(request('product_id') . request('price') . request('discount') . now()->addDays(7)),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo liên kết thành công',
            'data' => $link,
        ]);
    }

    public function getChartAffiliate()
    {
        $homeController = app()->make(HomeController::class);
        $type = request('filter', ThongKeType::MONTH->value);
        $chartX = $homeController->mapTypeToChartX($type);
        $dataAffiliate = $homeController->mapDataWithType(Affiliate::query()->selectRaw('*,(discount * amount) as total')->filter($type)->where('user_id', auth('web')->id())->get(), $type, 'total');
        $dataAffiliate = array_map(fn($item) => round($item / 1000), $dataAffiliate);

        return response()->json([
            'x' => $chartX,
            'y' => $dataAffiliate,
        ]);
    }
}
