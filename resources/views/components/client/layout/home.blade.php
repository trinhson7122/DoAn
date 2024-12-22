<x-client.layout.default>
    @push('plugin-css')
        <link rel="stylesheet" href="{{ asset('plugins/swiper/swiper-bundle.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/simplebar/simplebar.min.css') }}">
    @endpush
    @push('plugin-js')
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('plugins/swiper/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset('plugins/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('plugins/sweetalert2/sweetalert2.js') }}"></script>
        <script src="{{ asset('js/customSweetalert2.js') }}"></script>
        @if (session()->has('success'))
            <script>
                toast("{{ session()->get('success') }}")
            </script>
        @endif
    @endpush
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endpush

    @include('client.modal.shopping_cart')

    <!-- Search offcanvas -->
    @include('client.layouts.search')


    <!-- Topbar -->
    @include('client.layouts.topbar')

    <!-- Navigation bar (Page header) -->
    @include('client.layouts.header')

    <!-- Page content -->
    <main class="content-wrapper">
        {{ $slot }}
    </main>

    <div id="ajax-loading" class="ajax-loading" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <!-- Page footer -->
    @include('client.layouts.footer')
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger intent="WELCOME" chat-title="{{ config('app.chatbot_title') }}"
        agent-id="{{ config('app.chatbot_agent_id') }}" language-code="vi" wait-open="false">
    </df-messenger>
    <script>
        let loadingFirst = false;

        window.addEventListener('df-messenger-loaded', function(event) {
            const dfMessenger = document.querySelector('df-messenger');

            dfMessenger.addEventListener('df-response-received', function(event) {
                const messages = event.detail.response.queryResult.fulfillmentMessages;

                if (messages && messages.length > 0) {
                    messages.forEach(message => {
                        const cardData = message['card'] ?? undefined;
                        const payload = message['payload'] ?? undefined;

                        if (cardData) {
                            const payload = [{
                                    "type": "image",
                                    "rawUrl": cardData.imageUri,
                                    "accessibilityText": cardData.title
                                },
                                {
                                    "type": "info",
                                    "title": cardData.title,
                                    "subtitle": cardData.subtitle,
                                    "actionLink": cardData.buttons[0].postback
                                },
                                // {
                                //     "type": "chips",
                                //     "options": [{
                                //         "text": "Xem",
                                //         "link": cardData.buttons[0].postback
                                //     }]
                                // }
                            ];
                            dfMessenger.renderCustomCard(payload);
                        }

                        if (payload) {
                            const richContent = payload['richContent'] ?? undefined;

                            if (richContent) {
                                richContent.map(item => {
                                    if (item.type === 'description') {
                                        dfMessenger.renderCustomCard(richContent);
                                    }
                                    else {
                                        dfMessenger.renderCustomCard(richContent);
                                    }
                                });
                            }
                        }
                    });
                }

                // if (!loadingFirst) {
                //     const defaultMessages = [{
                //         "type": "description",
                //         "title": "test",
                //         "text": ['1', '2']
                //     }];
                //     dfMessenger.renderCustomCard(defaultMessages);

                //     loadingFirst = true;
                // }
            });
        });
    </script>
</x-client.layout.default>
