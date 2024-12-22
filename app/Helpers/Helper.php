<?php

use App\Enums\CartStatus;
use Carbon\Carbon;

function getLogo(): string
{
    return asset('images/logo.png');
}

function getUserAvatar(): string
{
    return asset('images/user.webp');
}

function getDefaultImage()
{
    return asset('images/blank-image.svg');
}

function formatMoney($money)
{
    return number_format($money, 0, ',', '.') . ' đ';
}

function getCartTotal(CartStatus $status = CartStatus::All, string $key = 'cart')
{
    $cart = getCart($status, $key);

    $total = 0;

    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return $total;
}

function getCartSavingTotal(CartStatus $status = CartStatus::All, string $key = 'cart')
{
    $cart = getCart($status, $key);

    $total = 0;

    foreach ($cart as $item) {
        if ($item['is_sale']) {
            $total += ($item['old_price'] - $item['price']) * $item['quantity'];
        }
    }

    return $total;
}

function getCartCount(CartStatus $status = CartStatus::All, string $key = 'cart')
{
    $cart = getCart($status, $key);

    return count($cart);
}

function getCart(CartStatus $status = CartStatus::All, string $key = 'cart')
{
    $cart = session()->get($key, []);
    switch ($status) {
        case CartStatus::NotDisabled:
            $cart = array_filter($cart, fn($item) => !($item['disabled'] ?? false));
            break;
        case CartStatus::OnlyDisabled:
            $cart = array_filter($cart, fn($item) => $item['disabled'] ?? false);
            break;
    }

    return $cart;
}

function stripVN($str)
{
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);

    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);
    return $str;
}

function getDiscount()
{
    return session()->get('discount', null);
}

function getCartDiscount(CartStatus $status = CartStatus::All, string $key = 'cart')
{
    $discount = getDiscount();
    $discountPrice = 0;

    if ($discount) {
        $cartTotal = getCartTotal($status, $key);

        $discountPrice = $cartTotal * ($discount['discount'] / 100);

        if ($discountPrice > $discount['max_price']) {
            $discountPrice = $discount['max_price'];
        }
    }

    return $discountPrice;
}

function getCartDiscountTotal(CartStatus $status = CartStatus::All, string $key = 'cart')
{
    $cartDiscount = getCartDiscount($status, $key);
    $cartTotal = getCartTotal($status, $key);

    return $cartTotal - $cartDiscount;
}

function getNoProductImage()
{
    return asset('images/no-products.jpg');
}

function getDiffPercent($a, $b)
{
    $percent = $b != 0 ? ((($a - $b) / $b) * 100) : 0;

    return [
        'percent' => round($percent, 2) < 0 ? -1 * round($percent, 2) : round($percent, 2),
        'icon' => $percent >= 0 ? 'ki-arrow-up' : 'ki-arrow-down',
        'color' => $percent >= 0 ? 'success' : 'danger'
    ];
}

function getStartEndThreeMonthByThreeMonth(int $threeMonth): array
{
    if ($threeMonth == 1) {
        return [
            'start' => 1,
            'end' => 3,
        ];
    }

    if ($threeMonth == 2) {
        return [
            'start' => 4,
            'end' => 6,
        ];
    }

    if ($threeMonth == 3) {
        return [
            'start' => 7,
            'end' => 9,
        ];
    }

    return [
        'start' => 10,
        'end' => 12,
    ];
}

function getThreeMonthByMonth(int $month)
{
    if ($month <= 3) {
        return 1;
    }

    if ($month <= 6) {
        return 2;
    }

    if ($month <= 9) {
        return 3;
    }

    return 4;
}

function getWeeksInMonth($year, $month)
{
    // Tạo ngày đầu tiên của tháng
    $startOfMonth = Carbon::create($year, $month, 1);
    // Tạo ngày cuối cùng của tháng
    $endOfMonth = $startOfMonth->copy()->endOfMonth();

    // Tính số tuần
    $weeks = $startOfMonth->diffInWeeks($endOfMonth) + 1; // +1 để đếm trọn vẹn tuần bắt đầu và kết thúc

    return $weeks;
}

function getBackgroundImage()
{
    return asset('images/user-background.webp');
}
