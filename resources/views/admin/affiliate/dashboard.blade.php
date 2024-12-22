<x-admin.layout.home>
    <div class="d-flex flex-column flex-column-fluid">

        <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">
            <div id="kt_app_toolbar_container" class="app-container  container-xxl d-flex flex-stack ">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Tiếp thị liên kết
                    </h1>

                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.home.dashboard') }}" class="text-muted text-hover-primary">
                                Trang chủ
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>

                        <li class="breadcrumb-item text-muted">
                            Tiếp thị liên kết
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>

                        <li class="breadcrumb-item text-muted">
                            Thống kê
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <!--begin::Filter menu-->
                    <div class="d-flex">
                        <select name="campaign-type"
                            class="filter form-select form-select-sm bg-body border-body w-175px">
                            @foreach ($filters as $item)
                                <option value="{{ $item['name'] }}" @if ($item['default']) selected @endif>
                                    {{ $item['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-xxl ">
                <!--begin::Row-->
                <div class="row gx-5 gx-xl-10 mb-xl-10">
                    @php
                        $data = [
                            [
                                'amount' => formatMoney($revenue['now']),
                                'label' => 'Doanh thu từ affiliate ' . $activeFilter['text'],
                                'data' => [
                                    'now' => $revenue['now'],
                                    'yesterday' => $revenue['yesterday'],
                                ],
                                'grid' => '6',
                            ],
                            [
                                'amount' => formatMoney($discountPaid['now']),
                                'label' => 'Hoa hồng đã trả ' . $activeFilter['text'],
                                'data' => [
                                    'now' => $discountPaid['now'],
                                    'yesterday' => $discountPaid['yesterday'],
                                ],
                                'grid' => '6',
                            ],
                        ];
                    @endphp
                    @for ($i = 0; $i < count($data); $i++)
                        <div class="col-xxl-{{ $data[$i]['grid'] }} col-md-6 col-lg-6">
                            <div class="card card-flush mb-5 mb-xl-10">
                                <div class="card-header pt-5">
                                    <div class="card-title d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            @if (isset($data[$i]['prefix']))
                                                <span
                                                    class="fs-4 fw-semibold text-gray-500 me-1 align-self-start">{{ $data[$i]['prefix'] }}</span>
                                            @endif
                                            <span
                                                class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">{{ $data[$i]['amount'] }}</span>
                                            @php
                                                $diff = getDiffPercent(
                                                    $data[$i]['data']['now'],
                                                    $data[$i]['data']['yesterday'],
                                                );
                                            @endphp
                                            <span class="badge badge-light-{{ $diff['color'] }} fs-base">
                                                <i
                                                    class="ki-duotone {{ $diff['icon'] }} fs-5 text-{{ $diff['color'] }} ms-n1"><span
                                                        class="path1"></span><span class="path2"></span></i>
                                                {{ $diff['percent'] }}%
                                            </span>
                                        </div>
                                        <span
                                            class="text-gray-500 pt-1 fw-semibold fs-6">{{ $data[$i]['label'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor

                    <!--begin::Col-->
                    <div class="col-lg-12 col-xl-12 col-xxl-12 mb-5 mb-xl-0">
                        <!--begin::Chart widget 3-->
                        <div class="card card-flush overflow-hidden h-md-100">
                            <!--begin::Header-->
                            <div class="card-header">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Đơn hàng đã bán
                                        {{ $activeFilter['text'] }}</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Chart-->
                                <div id="chartOrderAffiliate" class="pt-0 w-100 min-h-auto ps-4 pe-6"
                                    style="height: 300px; min-height: 315px;"></div>
                                <!--end::Chart-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Chart widget 3-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->

                <!--begin::Row-->
                <div class="row gy-5 g-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-12">

                        <div class="card h-md-100">
                            <!--begin::Header-->
                            <div class="card-header align-items-center border-0">
                                <!--begin::Title-->
                                <h3 class="fw-bold text-gray-900 m-0">Đơn hàng đã bán gần đây</h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-2">
                                <!--begin::Tab Content-->
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="kt_stats_widget_2_tab_1" role="tabpanel">
                                        <!--begin::Table container-->
                                        <div class="table-responsive">
                                            <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                                <thead>
                                                    <tr>
                                                        <th class="ps-0" scope="col">
                                                            <span class="fw-normal text-body">Sản phẩm</span>
                                                        </th>
                                                        <th class="d-none d-md-table-cell" scope="col">
                                                            <span class="fw-normal text-body">Ngày bán</span>
                                                        </th>
                                                        <th class="d-none d-md-table-cell" scope="col">
                                                            <span class="fw-normal text-body">Người tiếp thị</span>
                                                        </th>
                                                        <th class="text-end d-none d-sm-table-cell" scope="col">
                                                            <span class="fw-normal text-body">Số lượng</span>
                                                        </th>
                                                        <th class="text-end d-none d-sm-table-cell" scope="col">
                                                            <span class="fw-normal text-body">Giá tiền</span>
                                                        </th>
                                                        <th class="text-end d-none d-sm-table-cell" scope="col">
                                                            <span class="fw-normal text-body">Thành tiền</span>
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
                                                                        style="--cz-aspect-ratio: calc(52 / 66 * 100%); width: 80px; height: 60px">
                                                                        <img src="{{ $item->product->getThumbnail() }}"
                                                                            class="hover-effect-target"
                                                                            style="object-fit: cover" alt="Image">
                                                                    </div>
                                                                    <div class="ps-2 ms-1">
                                                                        <h6 class="product mb-1 mb-md-0">
                                                                            <a class="fs-sm fw-medium hover-effect-underline stretched-link"
                                                                                href="{{ route('admin.product.edit', $item->product->id) }}">{{ $item->product->name }}</a>
                                                                        </h6>
                                                                        <div class="fs-body-emphasis d-sm-none mb-1">
                                                                            Số lượng: {{ $item->amount }}</div>
                                                                        <div class="fs-body-emphasis d-sm-none mb-1">
                                                                            {{ formatMoney($item->affiliateLink->price) }}
                                                                        </div>
                                                                        <div class="fs-body-emphasis d-md-none">
                                                                            {{ $item->created_at->toDateString() }}
                                                                        </div>
                                                                        <div class="fs-body-emphasis d-md-none">
                                                                            Người tiếp thị: {{ $item->user->fullname }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="d-none d-md-table-cell py-3">
                                                                {{ $item->created_at->toDateString() }}</td>
                                                            <td class="d-none d-md-table-cell py-3">
                                                                {{ $item->user->fullname }}</td>
                                                            <td class="d-none d-md-table-cell py-3 text-end">
                                                                {{ $item->amount }}</td>
                                                            <td class="tendered text-end d-none d-sm-table-cell py-3">
                                                                {{ formatMoney($item->affiliateLink->price) }}</td>
                                                            <td class="earning text-end py-3 pe-0 fw-bold">
                                                                {{ formatMoney($item->affiliateLink->price * $item->amount) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <!--end::Table-->
                                        </div>
                                        <!--end::Table container-->
                                    </div>
                                    <!--end::Tap pane-->
                                </div>
                                <!--end::Tab Content-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    @push('js')
        <script src="{{ asset('plugins/echarts/echarts.min.js') }}"></script>
        <script src="{{ asset('js/dashboard.js') }}"></script>
        <script>
            $(document).on('change', '.filter', function() {
                location.search = `filter=${this.value}`;
            });

            initChartOrderAffiliate(`{{ route('admin.home.getChartOrderAffiliate', ['filter' => $type]) }}`);
        </script>
    @endpush
</x-admin.layout.home>
