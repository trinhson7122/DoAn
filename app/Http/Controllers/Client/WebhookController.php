<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\Translation\t;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $intent = $request->input('queryResult.intent.displayName');
        $params = $request->input('queryResult.parameters');
        $response = [
            $this->responseText(['Xin lỗi tôi không hiểu'])
        ];

        switch ($intent) {
            case 'Tìm kiếm sản phẩm':
                $response = $this->searchProduct($params);
                break;
            case 'Tìm kiếm đơn hàng':
                $response = $this->searchOrderCode($params);
                break;
            case 'Tư vấn size':
                $response = $this->tuvanSize($params);
                break;
        }

        Log::info(json_encode($response));
        return response()->json($response);
    }

    public function tuvanSize($params)
    {
        $weight = $params['weight'] ?? null;
        $heightCM = $params['height_cm'] ?? null;
        $heightM = $params['height_m'] ?? null;

        if (!$weight || !$heightCM) {
            return [
                'fulfillmentMessages' => [
                    [
                        ...$this->responseText([
                            'Xin lỗi bạn hãy nói chi tiết hơn được không.',
                            'Hãy cho tôi biết chiều cao và cân nặng của bạn.',
                        ]),
                    ],
                ],
            ];
        }

        if (is_array($heightCM)) {
            $heightCM = $heightCM[0];
        }

        if (is_array($heightM)) {
            $heightM = $heightM[0];
        }

        if (is_array($weight)) {
            $weight = $weight[0];
        }
        $weight = (float) $weight;
        $heightCM = (int) $heightCM;
        $heightM = $heightM ? (int) $heightM : 1;

        $height = $heightM * 100 + ($heightCM < 10 ? $heightCM * 10 : $heightCM);

        $suggestSize = $this->suggestSize($height, $weight);

        return [
            'fulfillmentMessages' => [
                [
                    'payload' => [
                        ...$this->responseRichContent(
                            'Kết quả tính toán size',
                            [
                                'Quần: ' . $suggestSize['pants'],
                                'Áo: ' . $suggestSize['shirt'],
                            ]
                        ),
                    ],
                ],
            ],
        ];
    }

    private function suggestSize(float $heightCm, float $weightKg): array
    {
        // Bảng size (tham khảo) - Cần điều chỉnh dựa trên bảng size thực tế của shop
        $sizeChart = [
            'shirt' => [
                'S' => ['height' => [150, 160], 'weight' => [40, 50]],
                'M' => ['height' => [160, 170], 'weight' => [50, 60]],
                'L' => ['height' => [170, 175], 'weight' => [60, 70]],
                'XL' => ['height' => [175, 180], 'weight' => [70, 80]],
                'XXL' => ['height' => [180, 185], 'weight' => [80, 90]],
                'XXXL' => ['height' => [185, 190], 'weight' => [90, 100]],
            ],
            'pants' => [
                'S' => ['height' => [150, 160], 'weight' => [40, 48]],
                'M' => ['height' => [160, 168], 'weight' => [48, 58]],
                'L' => ['height' => [168, 175], 'weight' => [58, 68]],
                'XL' => ['height' => [175, 180], 'weight' => [68, 78]],
                'XXL' => ['height' => [180, 185], 'weight' => [78, 85]],
                'xxxL' => ['height' => [185, 190], 'weight' => [85, 95]],
            ],
        ];

        $suggestedShirtSize = '';
        $suggestedPantsSize = '';

        // Tìm size áo
        foreach ($sizeChart['shirt'] as $size => $range) {
            if (
                $heightCm >= $range['height'][0] && $heightCm <= $range['height'][1] &&
                $weightKg >= $range['weight'][0] && $weightKg <= $range['weight'][1]
            ) {
                $suggestedShirtSize = $size;
                break;
            }
        }

        // Tìm size quần
        foreach ($sizeChart['pants'] as $size => $range) {
            if (
                $heightCm >= $range['height'][0] && $heightCm <= $range['height'][1] &&
                $weightKg >= $range['weight'][0] && $weightKg <= $range['weight'][1]
            ) {
                $suggestedPantsSize = $size;
                break;
            }
        }

        // Xử lý trường hợp không tìm thấy size phù hợp
        if (empty($suggestedShirtSize)) {
            $suggestedShirtSize = 'Không tìm thấy size áo phù hợp. Vui lòng liên hệ nhân viên tư vấn.';
        }

        if (empty($suggestedPantsSize)) {
            $suggestedPantsSize = 'Không tìm thấy size quần phù hợp. Vui lòng liên hệ nhân viên tư vấn.';
        }

        return [
            'shirt' => $suggestedShirtSize,
            'pants' => $suggestedPantsSize,
        ];
    }

    public function searchOrderCode($params)
    {
        $orderCode = $params['order_code'] ?? null;

        $order = Order::query()
            ->where('code', 'like', '%' . $orderCode . '%')
            ->select('orders.*')
            ->first();

        if (!$order) {
            return [
                'fulfillmentMessages' => [
                    [
                        ...$this->responseText(['Rất tiếc tôi không tìm đơn hàng của bạn.'])
                    ],
                ],
            ];
        }

        return [
            'fulfillmentMessages' => [
                [
                    'payload' => [
                        ...$this->responseRichContent(
                            'Thông tin đơn hàng #' . $order->code,
                            [
                                'Trạng thái: ' . $order->getStatusLabel(),
                                'Phương thức thanh toán: ' . $order->getPaymentMethodLabel(),
                                'Tổng tiền: ' . formatMoney($order->total),
                            ]
                        ),
                    ],
                ],
            ],
        ];
    }

    public function searchProduct($params)
    {
        $productName = $params['product_name'] ?? null;
        $size = $params['size'] ?? null;
        $category = $params['category_name'] ?? null;

        $products = Product::query()
            ->select('products.*')
            ->active()
            ->when($size, function ($query) use ($size) {
                $query->join('product_sizes', 'products.id', '=', 'product_sizes.product_id');
                $query->join('sizes', 'product_sizes.size_id', '=', 'sizes.id');
                if (is_array($size)) {
                    $query->where('sizes.name', 'like', '%' . $size[0] . '%');
                } else {
                    $query->where('sizes.name', 'like', '%' . $size . '%');
                }
            })
            ->when($category, function ($query) use ($category) {
                $query->join('kinds', 'products.kind_id', '=', 'kinds.id');
                $query->join('categories', 'kinds.category_id', '=', 'categories.id');
                if (is_array($category)) {
                    $query->where('categories.name', 'like', '%' . $category[0] . '%');
                } else {
                    $query->where('categories.name', 'like', '%' . $category . '%');
                }
                Log::info($query->toSql());
            })
            ->where('products.name', 'like', '%' . $productName . '%')
            ->where('products.stock', '>', 0)
            ->limit(2)
            ->get();

        if (!$products || $products->isEmpty()) {
            $response = [
                'fulfillmentMessages' => [
                    [
                        ...$this->responseText(['Rất tiếc tôi không tìm thấy sản phẩm của bạn.'])
                    ],
                ],
            ];
        } else {
            $result = [];

            foreach ($products as $product) {
                $result[] = $this->responseCard(
                    $product->name,
                    'Giá: ' . formatMoney($product->price) . '. Mô tả: ' . $product->description,
                    $product->getThumbnail(),
                    $this->getUrl('client.home.productDetail', $product->id)
                );
            }

            $response = [
                'fulfillmentMessages' => [
                    ...$result,
                ],
            ];
        }

        return $response;
    }

    private function responseText(array $text)
    {
        return [
            'text' => [
                ...$this->text($text),
            ]
        ];
    }

    private function responseRichContent(string $title, array $text)
    {
        return [
            'richContent' => [
                [
                    'type' => 'description',
                    'title' => $title,
                    ...$this->text($text),
                ]
            ]
        ];
    }

    private function responseCard(string $title, string $subtitle, string $imageUrl, string $postback)
    {
        return [
            'card' => [
                'title' => $title,
                'subtitle' => $subtitle,
                'imageUri' => $imageUrl,
                'buttons' => [
                    [...$this->button('Chi tiết', $postback)]
                ],
            ]
        ];
    }

    private function button(string $title, string $postback)
    {
        return [
            'text' => $title,
            'postback' => $postback,
        ];
    }

    private function text(array $text)
    {
        return [
            'text' => $text,
        ];
    }

    private function getUrl(string $name, $params = [])
    {
        return config('app.url') . route($name, $params, false);
    }
}
