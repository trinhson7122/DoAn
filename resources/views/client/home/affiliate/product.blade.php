<x-client.layout.home>
    <div class="container py-5">
        <div class="row pt-md-2 pt-lg-3 pb-sm-2 pb-md-3 pb-lg-4 pb-xl-5">
            <!-- Sidebar navigation that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
            @include('client.home.common.user_sidebar')


            <div class="col-lg-9">
                <div class="ps-lg-3 ps-xl-0">
                    <!-- Page title -->
                    <h1 class="h2 mb-1 mb-sm-2">Tiếp thị liên kết</h1>
                </div>

                <div class="row row-cols-2 row-cols-md-3 g-4" id="wishlistSelection">
                    @foreach ($products as $item)
                        <div class="col">
                            <div class="product-card animate-underline hover-effect-opacity bg-body rounded">
                                <div class="position-relative">
                                    <a class="d-block rounded-top overflow-hidden p-3 p-sm-4"
                                        href="{{ route('client.home.productDetail', $item->id) }}">
                                        <span
                                            class="badge bg-success position-absolute top-0 start-0 mt-2 ms-2 mt-lg-3 ms-lg-3">+{{ formatMoney(($item->affiliate_discount / 100) * $item->price) }}</span>
                                        <div class="ratio mt-3" style="--cz-aspect-ratio: calc(240 / 258 * 100%)">
                                            <img src="{{ $item->getThumbnail() }}" alt="image">
                                        </div>
                                    </a>
                                </div>
                                <div class="w-100 min-w-0 px-1 pb-2 px-sm-3 pb-sm-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        @php
                                            $avg = $item->reviews->avg('rating');
                                        @endphp
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < round($avg, 0, 2))
                                                <i class="ci-star-filled text-warning"></i>
                                            @else
                                                <i class="ci-star text-body-tertiary opacity-75"></i>
                                            @endif
                                        @endfor
                                        <span
                                            class="text-body-tertiary fs-xs">({{ $item->reviews->count() ?? 0 }})</span>
                                    </div>
                                    <h3 class="pb-1 mb-2">
                                        <a class="d-block fs-sm fw-medium text-truncate"
                                            href="{{ route('client.home.productDetail', $item->id) }}">
                                            <span class="animate-target">{{ $item->name }}</span>
                                        </a>
                                    </h3>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="h5 lh-1 mb-0">{{ formatMoney($item->price) }}
                                        </div>
                                    </div>
                                    <div class="mt-2 d-flex align-items-center justify-content-between">
                                        <form enctype="multipart/form-data" class="w-100"
                                            action="{{ route('client.affiliate.generateLink') }}">
                                            <input type="hidden" name="product_id" value="{{ $item->id }}">
                                            <input type="hidden" name="price" value="{{ $item->price }}">
                                            <input type="hidden" name="discount"
                                                value="{{ $item->affiliate_discount }}">
                                            <button type="button" class="btn-generate-link btn btn-success w-100">Tạo
                                                liên kết</button>
                                        </form>
                                    </div>
                                    @php
                                        $affiliateLinks = $item->affiliateLinks->where('user_id', auth('web')->id());
                                    @endphp
                                    @if ($affiliateLinks->count() > 0)
                                        @php
                                            $link = $affiliateLinks->first();
                                        @endphp
                                        <div class="mt-2 d-flex align-items-center justify-content-between">
                                            <input type="text" class="form-control me-2"
                                                value="{{ route('client.home.productDetail', [
                                                    'product' => $link->product_id,
                                                    'affiliate_link' => $link->link,
                                                ]) }}"
                                                readonly>
                                            <button class="btn btn-outline-secondary copy-button"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="Copy to clipboard">
                                                COPY
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-2">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $(document).on('click', '.btn-generate-link', function(e) {
                const form = $(this).closest('form');

                ajax(form.attr('action'), 'post', {
                    product_id: form.find('input[name="product_id"]').val(),
                    price: form.find('input[name="price"]').val(),
                    discount: form.find('input[name="discount"]').val(),
                }, function(res) {
                    // toast(res.data.message);
                    location.reload();
                });
            });

            $(document).on("click", ".copy-button", function() {
                const copyButton = $(this)[0];
                const copyInput = copyButton.closest('div').querySelector('input');
                // Copy the content
                navigator.clipboard.writeText(copyInput.value).then(() => {
                    // Change tooltip text
                    const tooltip = bootstrap.Tooltip.getInstance(copyButton);
                    tooltip.setContent({
                        '.tooltip-inner': 'Copied!'
                    });

                    // Show the tooltip
                    tooltip.show();

                    // Reset tooltip after 2 seconds
                    setTimeout(() => {
                        tooltip.setContent({
                            '.tooltip-inner': 'Copy to clipboard'
                        });
                    }, 2000);
                });
            });
        </script>
    @endpush
</x-client.layout.home>
