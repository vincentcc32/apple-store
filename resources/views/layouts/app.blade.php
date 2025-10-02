<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('style')
</head>

<body class="font-sans antialiased">

    <main>
        @include('layouts/header')

        @yield('content')
    </main>

    @if (Auth::check())
        {{-- chat box --}}
        <button id="openChat"
            class="fixed  cursor-pointer z-10 bottom-20 right-20 bg-blue-600 text-white px-7 py-5 rounded-full shadow-lg before:absolute ">
            Liên hệ
        </button>

        <!-- Modal chat -->
        <div id="chatModal"
            class="hidden fixed bottom-10 right-10 bg-black bg-opacity-40 rounded-t-2xl flex items-end justify-end z-[100]">
            <div class="bg-white w-80 h-96 rounded-t-2xl shadow-lg flex flex-col">
                <!-- Header -->
                <div class="flex justify-between items-center bg-blue-600 text-white px-4 py-2 rounded-t-2xl">
                    <h2 class="text-lg font-semibold">Chat Support</h2>
                    <button id="closeChat" class="text-white text-xl">&times;</button>
                </div>

                <!-- Nội dung chat -->
                <div id="chatMessages" class="flex-1 p-3 overflow-y-auto space-y-2">
                    <!-- Tin nhắn mẫu -->
                    <div class="bg-gray-200 p-2 rounded-lg self-start w-fit">Xin chào! Tôi có thể giúp gì?</div>
                    @foreach ($messagesByUserID as $message)

                        @if ($message->user_from_id !== Auth::user()->id)
                            <div class="bg-gray-200 p-2 rounded-lg self-start w-fit">{{ $message->message }}</div>
                        @else
                            <div class="bg-blue-500 text-white p-2 rounded-lg self-end w-fit ml-auto">{{ $message->message }}</div>
                        @endif

                    @endforeach

                </div>

                <!-- Ô nhập -->
                <div class="p-2 border-t flex gap-2">
                    <input id="chatInput" type="text" placeholder="Nhập tin nhắn..."
                        class="flex-1 border rounded-lg px-3 py-1 focus:outline-none" />
                    <button id="sendMsg" class="bg-blue-600 text-white px-3 py-1 rounded-lg">Gửi</button>
                </div>
            </div>
        </div>
    @endif


    <!-- Modal Search -->
    <div id="searchModal" class="fixed inset-0 hidden z-[99999] opacity-0 translate-y-4 transition-all duration-300">
        <!-- Overlay nền mờ -->
        <div id="overlaySearch" class="absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Nội dung modal -->
        <div id="searchForm" class="relative w-full h-full">
            <!-- Nút đóng -->
            <button id="closeSearch" class="absolute top-6 right-6 text-white bg-black/50 hover:bg-black/70 
                   p-3 rounded-full shadow-lg transition-all duration-300 
                   hover:rotate-90 hover:scale-110">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>

            <!-- Ô tìm kiếm -->
            <form action="{{ route('search') }}" class="w-full h-full flex items-center justify-center">
                <input type="text" placeholder="Tìm kiếm..." class="w-2/3 md:w-1/3 p-4 rounded-lg border border-gray-300 bg-white 
                   focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-lg" name="q">
                <button type="submit" class="bg-blue-500 text-white py-3 px-5 ml-4">Tìm</button>
            </form>
        </div>
    </div>

    @include('layouts/footer')

    @if (session(key: 'success'))
        <div id="toast"
            class="fixed top-5 right-5 w-72 p-4 bg-green-500 text-white rounded-lg shadow-lg opacity-0 transition-opacity duration-300 pointer-events-none z-[1000]">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div id="toast"
            class="fixed top-5 right-5 w-72 p-4 bg-red-500 text-white rounded-lg shadow-lg opacity-0 transition-opacity duration-300 pointer-events-none z-[1000]">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    <div id="toast2-wapper">

    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @vite(['resources/js/main.js'])

    {{-- toast --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userId = @json(Auth::user()->id ?? null);
            // console.log(userId);
            if (userId) {

                window.Echo.private(`order-status.${userId}`)
                    .listen('.OrderStatusEvent', (e) => {
                        // console.log('Received event:', e);


                        if (e.status === 'success') {
                            document.getElementById('toast2-wapper').innerHTML = `
                    
                     <div id="toast2"
                     class="fixed top-5 right-5 w-72 p-4 bg-green-500 text-white rounded-lg shadow-lg opacity-0 transition-opacity duration-300 pointer-events-none z-[1000]">
                     <p>${e.message}</p>
                     </div>
                     `;
                            document.getElementById('toast2').classList.add('opacity-100');
                            setTimeout(() => {
                                document.getElementById('toast2').classList.remove('opacity-100');
                                document.getElementById('toast2').innerHTML = '';
                            }, 5000);
                        }
                    });
            }
        });
    </script>

    {{-- chat box --}}
    <script>


        const openChat = document.getElementById("openChat");
        const closeChat = document.getElementById("closeChat");
        const chatModal = document.getElementById("chatModal");
        const sendMsg = document.getElementById("sendMsg");
        const chatInput = document.getElementById("chatInput");
        const chatMessages = document.getElementById("chatMessages");

        if (openChat) {

            // Mở modal
            openChat.addEventListener("click", () => {
                chatModal.classList.remove("hidden");
                // Auto scroll xuống cuối
                chatMessages.scrollTop = chatMessages.scrollHeight;
                openChat.classList.remove("chat-button");
            });

            // Đóng modal
            closeChat.addEventListener("click", () => {
                chatModal.classList.add("hidden");
                openChat.classList.remove("chat-button");
            });

            // Gửi tin nhắn
            sendMsg.addEventListener("click", () => {
                const msg = chatInput.value.trim();
                if (msg) {
                    // Tạo div tin nhắn
                    const newMsg = document.createElement("div");
                    newMsg.textContent = msg;
                    newMsg.className = "bg-blue-500 text-white p-2 rounded-lg self-end w-fit ml-auto";
                    chatMessages.appendChild(newMsg);

                    // Auto scroll xuống cuối
                    chatMessages.scrollTop = chatMessages.scrollHeight;

                    chatInput.value = "";
                    sendMessage(msg);


                }
            });

            // Enter để gửi
            chatInput.addEventListener("keypress", (e) => {
                if (e.key === "Enter") {
                    sendMsg.click();
                }
            });



            async function sendMessage(message) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const user_from_id = @json(Auth::user()->id ?? null);
                    const res = await fetch('http://127.0.0.1:8000/message/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ message, user_from_id }),
                        credentials: 'include',
                    });

                    if (!res.ok) {
                        throw new Error('Failed to send message');
                    }

                    const data = await res.json();

                    if (data.status !== 'success') {
                        alert(data.message);
                    }

                } catch (error) {
                    console.error('Error:', error);
                }
            }



        }
    </script>
    {{-- chat socket --}}
    <script>


        document.addEventListener("DOMContentLoaded", function () {
            let userId = @json(Auth::user()->id ?? null);

            // console.log("Tracking ID:", trackingId);

            if (userId) {
                console.log("user to id:", userId);
                if (window.Echo) {
                    window.Echo.leave(`message.${userId}`);
                    window.Echo.private(`message.${userId}`)
                        .listen('.messageEvent', (e) => {
                            console.log("Nhận event:", e);

                            if (!openChat.classList.contains('hidden')) {
                                openChat.classList.add('chat-button');
                            }


                            const div = document.createElement('div');
                            div.textContent = e.message;
                            div.className = "bg-gray-200 p-2 rounded-lg self-start w-fit";
                            chatMessages.appendChild(div);

                            // Cuộn xuống cuối
                            chatMessages.scrollTo({
                                top: chatMessages.scrollHeight,
                                behavior: 'smooth'
                            });



                        });
                } else {
                    console.error("Echo chưa được khởi tạo");
                }
            }
        });
    </script>


    @stack('script')



</body>

</html>