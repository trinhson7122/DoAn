<x-client.layout.default>
    <!-- Page content -->
    <main class="content-wrapper w-100 px-3 ps-lg-5 pe-lg-4 mx-auto" style="max-width: 1920px">
        <div class="d-lg-flex">

            <div class="d-flex flex-column min-vh-100 w-100 py-4 mx-auto me-lg-51 border-1 border-red-50"
                style="max-width: 416px">

                <!-- Logo -->
                <header class="navbar align-items-center px-0 pb-4 mt-n2 mt-sm-0 mb-2 mb-md-3 mb-lg-4">
                    <a href="index.html" class="navbar-brand pt-0">
                        <span class="d-flex flex-shrink-0 text-primary me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36">
                                <path
                                    d="M36 18.01c0 8.097-5.355 14.949-12.705 17.2a18.12 18.12 0 0 1-5.315.79C9.622 36 2.608 30.313.573 22.611.257 21.407.059 20.162 0 18.879v-1.758c.02-.395.059-.79.099-1.185.099-.908.277-1.817.514-2.686C2.687 5.628 9.682 0 18 0c5.572 0 10.551 2.528 13.871 6.517 1.502 1.797 2.648 3.91 3.359 6.201.494 1.659.771 3.436.771 5.292z"
                                    fill="currentColor"></path>
                                <g fill="#fff">
                                    <path
                                        d="M17.466 21.624c-.514 0-.988-.316-1.146-.829-.198-.632.138-1.303.771-1.501l7.666-2.469-1.205-8.254-13.317 4.621a1.19 1.19 0 0 1-1.521-.75 1.19 1.19 0 0 1 .751-1.521l13.89-4.818c.553-.197 1.166-.138 1.64.158a1.82 1.82 0 0 1 .85 1.284l1.344 9.183c.138.987-.494 1.994-1.482 2.33l-7.864 2.528-.375.04zm7.31.138c-.178-.632-.85-1.007-1.482-.81l-5.177 1.58c-2.331.79-3.28.02-3.418-.099l-6.56-8.412a4.25 4.25 0 0 0-4.406-1.758l-3.122.987c-.237.889-.415 1.777-.514 2.686l4.228-1.363a1.84 1.84 0 0 1 1.857.81l6.659 8.551c.751.948 2.015 1.323 3.359 1.323.909 0 1.857-.178 2.687-.474l5.078-1.54c.632-.178 1.008-.829.81-1.481z">
                                    </path>
                                    <use href="#czlogo"></use>
                                    <use href="#czlogo" x="8.516" y="-2.172"></use>
                                </g>
                                <defs>
                                    <path id="czlogo"
                                        d="M18.689 28.654a1.94 1.94 0 0 1-1.936 1.935 1.94 1.94 0 0 1-1.936-1.935 1.94 1.94 0 0 1 1.936-1.935 1.94 1.94 0 0 1 1.936 1.935z">
                                    </path>
                                </defs>
                            </svg>
                        </span>
                        {{ config('app.name') }}
                    </a>
                    <div class="nav">
                        <a class="nav-link fs-base animate-underline p-0" href="{{ route('client.auth.login') }}">
                            <i class="ci-chevron-left fs-lg ms-n1 me-1"></i>
                            <span class="animate-target">Trở về đăng nhập</span>
                        </a>
                    </div>
                </header>

                <h1 class="h2 mt-auto">Quên mật khẩu?</h1>
                <p class="pb-2 pb-md-3">Nhập địa chỉ email bạn đã dùng để đăng ký tài khoản xuống dưới và tôi sẽ gửi cho
                    bạn mật khẩu mới.</p>

                <!-- Form -->
                <form class="pb-4 mb-3 mb-lg-4" method="post" action="{{ route('client.auth.handleForgotPassword') }}">
                    @csrf
                    <div class="position-relative mb-4">
                        <i class="ci-mail position-absolute top-50 start-0 translate-middle-y fs-lg ms-3"></i>
                        <input type="text" name="email"
                            class="form-control form-control-lg form-icon-start @error('email') is-invalid @enderror"
                            placeholder="Email address">
                        @error('email')
                            <div style="display: block" class="invalid-tooltip bg-transparent py-0">{{ $message }}
                            </div>
                        @enderror
                    </div>
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <button type="submit" class="btn btn-lg btn-primary w-100">Quên mật khẩu</button>
                </form>

                <!-- Footer -->
                @include('client.layouts.auth_footer')
            </div>
        </div>
    </main>
</x-client.layout.default>
