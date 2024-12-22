<div class="table-responsive">
    <table class="table align-middle table-row-dashed fs-6 gy-5">
        <thead class="bg-light">
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th style="width: 30%" class="pe-2 text-nowrap">Sản phẩm</th>
                <th style="width: 10%" class="pe-2 text-nowrap text-center">Thể loại</th>
                <th style="width: 10%" class="pe-2 text-nowrap text-center">Giá tiền</th>
                <th style="width: 10%" class="pe-2 text-nowrap text-center">Hoa hồng (%)</th>
                <th style="width: 10%" class="pe-2 text-nowrap text-center">Đánh giá</th>
                <th style="width: 5%" class="pe-2 text-nowrap text-center">Cho phép tiếp thị liên kết</th>
                <th style="width: 10%" class="pe-2 text-nowrap"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $item)
                <tr>
                    <td>
                        <a href="{{ route('admin.product.edit', $item->id) }}"
                            class="d-flex align-items-center flex-wrap">
                            <img width="80" src="{{ $item->getThumbnail() }}" class="img-fluid me-3">
                            <div>{{ $item->name }}</div>
                        </a>
                    </td>
                    <td class="text-center">
                        <div>{{ $item->kind->category->name }}</div>
                        <div>{{ $item->kind->name }}</div>
                    </td>
                    <td class="text-nowrap text-center">
                        {{ formatMoney($item->price) }}
                    </td>
                    <td class="text-nowrap text-center">
                        {{ $item->affiliate_discount }}
                    </td>
                    <td class="text-nowrap text-center">
                        <div class="rating justify-content-end">
                            @php
                                $avg = $item->reviews->avg('rating');
                            @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="rating-label @if ($i <= $avg) checked @endif">
                                    <i class="ki-duotone ki-star fs-6"></i>
                                </div>
                            @endfor
                        </div>
                    </td>
                    <td class="text-nowraps text-center">
                        @if ($item->has_affiliate)
                            <span class="badge badge-success">Có</span>
                        @else
                            <span class="badge badge-danger">Không</span>
                        @endif
                    </td>
                    <td class="text-end text-nowrap">
                        <a data-id="{{ $item->id }}" class="btn-edit-product-affiliate btn btn-default btn-sm"
                            href="javascript:void(0)">
                            <i class="bi bi-pencil-square text-warning"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="row">
    {{ $products->links() }}
</div>
@push('js')
    <script>
        $(() => {
            $(document).on('click', '.btn-delete-product', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                showConfirm("Bạn có chắc chắn muốn xóa sản phẩm?", function() {
                    ajax(url, 'delete', {}, function(res) {
                        dispatchReloadEvent();
                        toast(res.data.message);
                    });
                });
            });

            $(document).on('click', '.btn-edit-product-affiliate', function(e) {
                let url = `{{ route('admin.affiliate.edit', ':id') }}`;
                url = url.replace(':id', this.dataset.id);
                loadView(url);
            });
        });
    </script>
@endpush
