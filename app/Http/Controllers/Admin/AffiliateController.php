<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Product\GetListProductAction;
use App\Enums\ThongKeType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Affiliate\UpdateAffiliateProduct;
use App\Models\Affiliate;
use App\Models\Kind;
use App\Models\Product;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    public function index()
    {
        $homeController = app()->make(HomeController::class);
        $type = request('filter', ThongKeType::MONTH->value);
        $filters = $homeController->getDashboardFilters($type);
        $activeFilter = $homeController->getFilterActive($filters);


        $revenue = [
            'now' => Affiliate::getRevenue($type),
            'yesterday' => Affiliate::getRevenue($type, true),
        ];

        $discountPaid = [
            'now' => Affiliate::getDiscountPaid($type),
            'yesterday' => Affiliate::getDiscountPaid($type, true),
        ];

        $affiliates = Affiliate::query()
            ->with([
                'product',
                'product.images',
                'affiliateLink',
                'user',
            ])
            ->where('user_id', auth('web')->id())
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.affiliate.dashboard', compact(
            'revenue',
            'activeFilter',
            'type',
            'filters',
            'discountPaid',
            'affiliates',
        ));
    }

    public function product()
    {
        $filters = [
            'search' => request()->input('search', ''),
            'is_active' => request()->input('is_active', '1'),
            'kind_id' => request()->input('kind_id', ''),
            'has_affiliate' => request()->input('has_affiliate', ''),
        ];

        $products = app()->make(GetListProductAction::class)->handle(
            filters: $filters,
            hasPaginate: true,
        );

        if (request()->ajax()) {
            return response()->view('admin.affiliate.table_list', compact('products'));
        }

        $filters = [
            [
                'name' => 'kind_id',
                'label' => 'Thể loại',
                'data' => Kind::query()->pluck('name', 'id')->toArray(),
            ],
            [
                'name' => 'has_affiliate',
                'label' => 'Tiếp thị liên kết',
                'data' => [
                    '1' => 'Có',
                    '0' => 'Không',
                ],
            ],
        ];

        return view('admin.affiliate.index', compact('products', 'filters'));
    }

    public function edit(string $id)
    {
        $product = Product::find($id);
        return response()->view('admin.affiliate.modals.update-product', compact('product'));
    }

    public function update(UpdateAffiliateProduct $request, string $id)
    {
        $validated = $request->validated();

        $product = Product::find($id);
        $product->update([
            'has_affiliate' => $validated['has_affiliate'] ?? false,
            'affiliate_discount' => $validated['affiliate_discount'] ?? null,
        ]);

        return response()->json([
            'message' => 'Cập nhật sản phẩm tiếp thị liên kết thành công thành công',
            'status' => 'success',
        ]);
    }
}
