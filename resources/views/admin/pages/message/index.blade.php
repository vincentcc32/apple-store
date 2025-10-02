@extends('admin.layouts.app')

@push('style')
  <style>
    .chat-container {
      height: 85vh;
      display: flex;
      overflow: hidden;
    }

    .sidebar {
      width: 25%;
      background-color: #f8f9fa;
      border-right: 1px solid #dee2e6;
      overflow-y: auto;
    }

    .sidebar .user {
      padding: 15px;
      cursor: pointer;
      border-bottom: 1px solid #dee2e6;
      position: relative;
    }

    .sidebar .user:hover {
      background-color: #e9ecef;
    }

    .user.disabled {
      pointer-events: none;
      opacity: 0.5;
      font-style: italic;
      color: white;
      background: blue;
    }

    .chat-box {
      width: 75%;
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .messages {
      flex-grow: 1;
      overflow-y: auto;
      margin-bottom: 15px;
    }

    .message {
      padding: 10px;
      margin: 5px 0;
      border-radius: 10px;
      max-width: 70%;
    }

    .sent {
      width: fit-content;
      background-color: #007bff;
      color: white;
      align-self: flex-end;
      margin-left: auto;
    }

    .received {
      background-color: #e9ecef;
      align-self: flex-start;
      width: fit-content;
    }

    .input-group textarea {
      resize: none;
    }

    .new-message::before {
      content: "new";
      display: block;
      position: absolute;
      top: 0;
      right: 0;
      z-index: 100;
      width: 20%;
      height: 20%;
      border-radius: 100%;
      color: red;
      font-size: 16px;
      font-weight: bold;
    }
  </style>
@endpush

@section('content')

  <div class="container">
    <div class="chat-container">
      <!-- Sidebar người dùng -->
      <div class="sidebar">

        @php
          $userIndexSelected = 0;
        @endphp

        @foreach ($groupedMessages as $chatKey => $messages)
          @php
            $first = $messages->first();
            $userNames = $first->userFrom->name;
          @endphp

          <div class="user user-{{ $first->userFrom->id }} {{ $userIndexSelected === 0 ? 'active disabled' : '' }} "
            data-chat="{{ $chatKey }}">
            {{ $userNames }}
          </div>

          @php
            $userIndexSelected++;
          @endphp

        @endforeach



      </div>

      <!-- Khung trò chuyện -->
      <div class="chat-box">
        <div class="messages" id="messages">
          <!-- Tin nhắn sẽ được thêm vào đây -->
        </div>
        <div class="input-group">
          <textarea id="messageInput" class="form-control" rows="2" placeholder="Nhập tin nhắn..."></textarea>
          <div class="input-group-append">
            <button class="btn btn-primary" type="button" id="sendBtn">Gửi</button>
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection

@push('script')

  <script>
    // Dữ liệu giả cho các cuộc trò chuyện


    let conversations = @json($groupedMessages);
    let currentUser = @json($groupedMessages->keys()->first());
    const authUserId = @json($authUserId);
    const messagesContainer = document.getElementById('messages');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    console.log(conversations);

    // Hàm hiển thị cuộc trò chuyện theo người dùng
    function renderMessages(userId) {
      messagesContainer.innerHTML = '';
      const conv = conversations[userId] || [];

      conv.forEach(msg => {
        const div = document.createElement('div');
        div.classList.add('message', msg.user_from_id === authUserId ? 'sent' : 'received');
        div.textContent = msg.message;
        messagesContainer.appendChild(div);
      });
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Gửi tin nhắn
    sendBtn.addEventListener('click', () => {
      const text = messageInput.value.trim();
      if (text !== '') {
        // Thêm vào data
        sendMessage(text);

        // Thêm vào giao diện
        const div = document.createElement('div');
        div.classList.add('message', 'sent');
        div.textContent = text;
        messagesContainer.appendChild(div);
        messageInput.value = '';
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
      }
    });

    // Chuyển đổi cuộc trò chuyện khi click người dùng
    document.querySelectorAll('.sidebar .user').forEach(userEl => {
      userEl.addEventListener('click', () => {

        if (userEl.classList.contains('disabled')) return;

        // Bỏ class active khỏi tất cả
        document.querySelectorAll('.sidebar .user').forEach(u => u.classList.remove('active', 'disabled'));

        userEl.classList.add('active', 'disabled');
        userEl.classList.remove('new-message')
        // Lấy ID người dùng
        const selectedUser = userEl.getAttribute('data-chat');
        currentUser = selectedUser;

        // Nếu chưa có dữ liệu thì tạo mới
        if (!conversations[currentUser]) {
          conversations[currentUser] = [];
        }

        // Hiển thị tin nhắn
        renderMessages(currentUser);
      });
    });

    // Gửi bằng phím Enter (nếu muốn)
    messageInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendBtn.click();
      }
    });

    function getReceiverId(chatKey) {
      const ids = chatKey.split('-').map(Number); // [3, 4]
      return ids[0] === authUserId ? ids[1] : ids[0];
    }



    async function sendMessage(message) {
      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const user_from_id = @json(Auth::user()->id ?? null);
        const user_to_id = getReceiverId(currentUser);

        const res = await fetch('http://127.0.0.1:8000/message/store', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
          },
          body: JSON.stringify({ message, user_from_id, user_to_id }),
          credentials: 'include',
        });

        if (!res.ok) {
          throw new Error('Failed to send message');
        }

        const data = await res.json();

        console.log('Message sent successfully:', data);

      } catch (error) {
        console.error('Error:', error);
      }
    }


    // Render lần đầu
    renderMessages(currentUser);
  </script>

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

              console.log(e.userFromID);

              if (currentUser.includes(e.userFromID)) {
                const div = document.createElement('div');
                div.classList.add('message', 'received');
                div.textContent = e.message;
                messagesContainer.appendChild(div);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;



              } else {
                console.log('nha tin nhan');
                document.querySelector(`.user-${e.userFromID}`).classList.add('new-message');

              }

              fetch(`http://127.0.0.1:8000/message/new`)
                .then(response => response.json())
                .then(data => {
                  conversations = data.data;
                })

            });
        } else {
          console.error("Echo chưa được khởi tạo");
        }
      }
    });
  </script>

@endpush