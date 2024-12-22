<x-client.layout.home>
    <div class="container py-5">
        <div class="row pt-md-2 pt-lg-3 pb-sm-2 pb-md-3 pb-lg-4 pb-xl-5">
            <!-- Sidebar navigation that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
            @include('client.home.common.user_sidebar')


            <div class="col-lg-9">
                <div class="ps-lg-3 ps-xl-0">
                    <!-- Page title -->
                    <div class="d-flex align-items-center justify-content-between gap-3 pb-3 mb-2 mb-md-3">
                        <h1 class="h2 mb-0">Thống kê</h1>
                        <div class="position-relative" style="width: 190px">
                            <i class="ci-calendar position-absolute top-50 start-0 translate-middle-y z-1 ms-3"></i>
                            <select class="filter form-select form-icon-start rounded-pill" name=""
                                id="">
                                @foreach ($filters as $item)
                                    <option value="{{ $item['name'] }}"
                                        @if ($item['default']) selected @endif>
                                        {{ $item['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-4 mb-2 mb-md-3" id="wishlistSelection">
                    <div class="col">
                        <div class="h-100 bg-success-subtle rounded-4 text-center p-4">
                            <h2 class="fs-sm pb-2 mb-1">Hoa hồng</h2>
                            <div class="h2 pb-1 mb-2 d-flex justify-content-center align-items-center">
                                <i class="ci-gift"></i>
                                {{ formatMoney($totalDiscount) }}
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="h-100 bg-info-subtle rounded-4 text-center p-4">
                            <h2 class="fs-sm pb-2 mb-1">Tài chính</h2>
                            <div class="h2 pb-1 mb-2 d-flex justify-content-center align-items-center">
                                <i class="ci-dollar-sign"></i>
                                {{ formatMoney($user->coin) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-3 mb-2 mb-sm-3">
                    <div class="border rounded-4 py-4 px-3 px-sm-4">
                        <h2 class="h5 text-center text-sm-start mb-sm-4">Hoa hồng {{ $activeFilter['text'] }}
                        </h2>
                        <div id="chartAffiliate" class="pt-0 w-100 min-h-auto ps-4 pe-6"
                            style="height: 387px; width: 773px;"></div>
                    </div>
                </div>

                <div class="border rounded-4 py-4 px-3 px-sm-4">
                    <div
                        data-filter-list="{&quot;searchClass&quot;: &quot;product-search&quot;, &quot;listClass&quot;: &quot;product-list&quot;, &quot;sortClass&quot;: &quot;product-sort&quot;, &quot;valueNames&quot;: [&quot;product&quot;, &quot;date&quot;, &quot;tendered&quot;, &quot;earning&quot;]}">
                        <div
                            class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 mb-2 mb-sm-3 mb-md-4">
                            <h2 class="h5 text-center text-sm-start mb-0">Sản phẩm đã bán gần đây</h2>
                        </div>
                        <table class="table align-middle fs-sm mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-0" scope="col">
                                        <span class="fw-normal text-body">Sản phẩm</span>
                                    </th>
                                    <th class="d-none d-md-table-cell" scope="col">
                                        <span class="fw-normal text-body">Ngày bán</span>
                                    </th>
                                    <th class="text-end d-none d-sm-table-cell" scope="col">
                                        <span class="fw-normal text-body">Số lượng</span>
                                    </th>
                                    <th class="text-end d-none d-sm-table-cell" scope="col">
                                        <span class="fw-normal text-body">Hoa hồng</span>
                                    </th>
                                    <th class="text-end pe-0 text-nowrap" scope="col">
                                        <span class="fw-normal text-body">Tổng nhận</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="product-list">
                                @foreach ($affiliates as $item)
                                    <tr>
                                        <td class="py-3 ps-0">
                                            <div
                                                class="d-flex align-items-start align-items-md-center hover-effect-scale position-relative">
                                                <div class="ratio bg-body-secondary rounded-2 overflow-hidden flex-shrink-0"
                                                    style="--cz-aspect-ratio: calc(52 / 66 * 100%); width: 66px">
                                                    <img src="{{ $item->product->getThumbnail() }}"
                                                        class="hover-effect-target" style="object-fit: cover"
                                                        alt="Image">
                                                </div>
                                                <div class="ps-2 ms-1">
                                                    <h6 class="product mb-1 mb-md-0">
                                                        <a class="fs-sm fw-medium hover-effect-underline stretched-link"
                                                            href="{{ route('client.home.productDetail', $item->product->id) }}">{{ $item->product->name }}</a>
                                                    </h6>
                                                    <div class="fs-body-emphasis d-sm-none mb-1">
                                                        Số lượng: {{ $item->amount }}</div>
                                                    <div class="fs-body-emphasis d-sm-none mb-1 text-success">
                                                        {{ formatMoney($item->discount) }}</div>
                                                    <div class="fs-body-emphasis d-md-none">
                                                        {{ $item->created_at->toDateString() }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell py-3">
                                            {{ $item->created_at->toDateString() }}</td>
                                        <td class="d-none d-md-table-cell py-3 text-end">
                                            {{ $item->amount }}</td>
                                        <td class="tendered text-end d-none d-sm-table-cell py-3">
                                            {{ formatMoney($item->discount) }}</td>
                                        <td class="earning text-end py-3 pe-0 text-success fw-bold">
                                            {{ formatMoney($item->discount * $item->amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        {{ $affiliates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script src="{{ asset('plugins/echarts/echarts.min.js') }}"></script>
        <script src="{{ asset('js/affiliate.js') }}"></script>
        <script>
            $(document).on('change', '.filter', function() {
                location.search = `filter=${this.value}`;
            });

            initChartAffiliate(`{{ route('client.affiliate.getChartAffiliate', ['filter' => $type]) }}`);
        </script>
    @endpush
</x-client.layout.home>
