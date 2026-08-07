<?php
require_once __DIR__ . '/includes/notifications.php';
requireLogin();
$me = currentUser();
$openWith = isset($_GET['with']) ? (int) $_GET['with'] : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat System</title>
    <script>
    (function () {
        var t = localStorage.getItem('theme') ||
            (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', t);
    })();
</script>
<link rel="stylesheet" href="theme.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        body {
            background: var(--color-app-bg);
            display: flex;
            min-height: 100vh;
        }

        aside {
            width: 250px;
            background: var(--color-sidebar-bg);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        nav {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        nav a {
            text-decoration: none;
            color: white;
            background: var(--color-sidebar-item-bg);
            padding: 12px;
            border-radius: 8px;
            font-size: 18px;
            transition: .3s;
            position: relative;
        }

        nav a:hover {
            background: white;
            color: black;
        }

        .nav-badge {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--color-danger);
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 999px;
            display: none;
        }

        .logout button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: var(--color-danger);
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
        }

        .logout button:hover {
            background: var(--color-danger-hover);
        }

        main {
            flex: 1;
            padding: 35px;
        }

        h2 {
            color: white;
            margin-bottom: 8px;
        }

        .subtitle {
            color: var(--color-muted-text);
            margin-bottom: 25px;
        }

        .chat-container {
            display: flex;
            gap: 20px;
            height: 75vh;
        }

        #left-panel,
        #right-panel {
            background: var(--color-card-bg);
            color: var(--color-card-text);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 15px var(--shadow-color);
        }

        #left-panel {
            width: 320px;
            display: flex;
            flex-direction: column;
        }

        #left-panel h3 {
            color: var(--color-accent-text);
            margin-bottom: 15px;
        }

        #left-panel input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--color-placeholder-border);
            border-radius: 10px;
            margin-bottom: 20px;
            outline: none;
            background: var(--color-card-bg);
            color: var(--color-card-text);
        }

        #left-panel h4 {
            color: var(--color-accent-text);
            margin-bottom: 15px;
        }

        .chat-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .chat-item {
            padding: 12px;
            background: var(--color-list-item-bg);
            color: var(--color-list-item-text);
            border-radius: 8px;
            cursor: pointer;
            transition: .3s;
        }

        .chat-item:hover {
            background: var(--color-app-bg);
            color: white;
        }

        #right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .chat-header {
            border-bottom: 1px solid var(--color-border);
            padding-bottom: 15px;
            color:var(--color-accent-text);
            font-weight: bold;
            font-size: 20px;
        }

        .messages {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .message {
            max-width: 70%;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .received {
            background: var(--color-list-item-bg);
            color: var(--color-list-item-text);
        }

        .sent {
            background: var(--color-app-bg);
            color: white;
            margin-left: auto;
        }

        .message-box {
            display: flex;
            gap: 10px;
            align-items: center;
            border-top: 1px solid var(--color-border);
            padding-top: 20px;
        }

        .message-box input[type="text"] {
            flex: 1;
            padding: 12px;
            border: 1px solid var(--color-placeholder-border);
            border-radius: 10px;
            outline: none;
            background: var(--color-card-bg);
            color: var(--color-card-text);
        }

        .message-box input[type="file"] {
            padding: 10px;
            border: 1px solid var(--color-placeholder-border);
            border-radius: 10px;
            background: var(--color-card-bg);
            color: var(--color-card-text);
        }

        .message-box button {
            padding: 12px 22px;
            border: none;
            border-radius: 10px;
            background: var(--color-app-bg);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
        }

        .message img {
            max-width: 220px;
            max-height: 220px;
            border-radius: 10px;
            object-fit: cover;
            display: block;
        }

        .message-box button:hover {
            background: var(--color-sidebar-item-bg);
        }

        @media(max-width:500px) {

            body {
                flex-direction: column;
            }

            aside {
                width: 100%;
            }

            .chat-container {
                flex-direction: column;
                height: auto;
            }

            #left-panel {
                width: 100%;
            }

            .message-box {
                flex-direction: column;
            }

            .message-box input,
            .message-box button {
                width: 100%;
            }
        }
    </style>

</head>

<body>

    <aside>

        <div>

            <div class="logo">
                <img src="cmpLOGO.jpeg" width="180">
            </div>

            <nav>
                <a href="Home_Feed.php">Home Feed</a>
                <a href="Create_Post.php">Post an Item</a>
                <a href="University_Map.html">University Map</a>
                <a href="Messages.php">Messages</a>
                <a href="profiledashboard.php">Profile</a>
                <a href="Settings.php">Settings</a>
                <a href="Notifications.php">Notifications <span class="nav-badge" id="navBadge"><?php echo unreadNotificationCount($me['id']); ?></span></a>

            </nav>

        </div>

        <div class="logout">
            <form action="api/logout.php" method="post" onsubmit="return confirm('Are you sure you want to logout?');">
                <button type="submit">Logout</button>
            </form>
        </div>

    </aside>

    <main>

        <h2>Chat System</h2>

        <p class="subtitle">
            Communicate and resolve found or lost items easily.
        </p>

        <div class="chat-container">

            <div id="left-panel">

                <h3>Chats</h3>

                <input type="text" id="searchConvo" placeholder="Search Conversation">

                <h4>Active Conversations</h4>

                <div class="chat-list" id="chatList">
                    <p style="opacity:.6;padding:8px;">Loading...</p>
                </div>

            </div>

            <div id="right-panel">

                <div>

                    <div class="chat-header" id="chatHeader">
                        Select a friend to start chatting
                    </div>

                    <div class="messages" id="messagesBox"></div>

                </div>

                <div class="message-box">
                    <input type="text" id="messageInput" placeholder="Write a message...">
                    <input type="file" id="fileInput" accept="image/*">
                    <button id="sendBtn">Send</button>
                </div>

            </div>

        </div>

    </main>

    <script>
        // Real friends + real messages now (api/messages_list.php, api/messages_thread.php,
        // api/messages_send.php) instead of the old hardcoded chatData object.

        const openWithId = <?php echo json_encode($openWith); ?>;

        const chatList = document.getElementById("chatList");
        const chatHeader = document.getElementById("chatHeader");
        const messagesBox = document.getElementById("messagesBox");
        const messageInput = document.getElementById("messageInput");
        const fileInput = document.getElementById("fileInput");
        const sendButton = document.getElementById("sendBtn");
        const searchInput = document.getElementById("searchConvo");

        let conversations = [];
        let currentFriendId = null;
        let pollTimer = null;

        function escapeHtml(str) {
            const div = document.createElement("div");
            div.textContent = str;
            return div.innerHTML;
        }

        async function loadConversations(selectId) {
            const res = await fetch("api/messages_list.php");
            const data = await res.json();
            if (!data.success) return;
            conversations = data.conversations;
            renderChatList();

            if (selectId && conversations.some(c => c.id === selectId)) {
                openChat(selectId);
            } else if (!currentFriendId && conversations.length > 0) {
                openChat(conversations[0].id);
            } else if (conversations.length === 0) {
                chatHeader.textContent = "Add some friends to start chatting";
            }
        }

        function renderChatList() {
            const keyword = searchInput.value.toLowerCase();
            const filtered = conversations.filter(c => c.name.toLowerCase().includes(keyword));

            if (filtered.length === 0) {
                chatList.innerHTML = '<p style="opacity:.6;padding:8px;">No conversations yet — add friends first.</p>';
                return;
            }

            chatList.innerHTML = filtered.map(c => `
                <div class="chat-item${c.id === currentFriendId ? ' active' : ''}" data-id="${c.id}">
                    ${c.name}${c.unread > 0 ? ` <span class="nav-badge">${c.unread}</span>` : ''}
                </div>
            `).join("");

            chatList.querySelectorAll(".chat-item").forEach(el => {
                el.addEventListener("click", () => openChat(parseInt(el.dataset.id, 10)));
            });
        }

        async function openChat(friendId) {
            currentFriendId = friendId;
            renderChatList();

            const res = await fetch("api/messages_thread.php?with=" + friendId);
            const data = await res.json();
            if (!data.success) {
                chatHeader.textContent = data.message || "Couldn't load this conversation.";
                return;
            }

            chatHeader.textContent = data.with.name;
            messagesBox.innerHTML = "";

            data.messages.forEach(m => {
                const div = document.createElement("div");
                div.className = "message " + (m.fromMe ? "sent" : "received");

                if (m.image) {
                    const img = document.createElement("img");
                    img.src = m.image;
                    img.style.maxWidth = "220px";
                    img.style.borderRadius = "10px";
                    img.style.display = "block";
                    div.appendChild(img);
                    if (m.body) {
                        const p = document.createElement("div");
                        p.textContent = m.body;
                        div.appendChild(p);
                    }
                } else {
                    div.textContent = m.body;
                }

                messagesBox.appendChild(div);
            });

            messagesBox.scrollTop = messagesBox.scrollHeight;

            // clear the unread badge for this conversation locally, then refresh from server
            loadConversations();
        }

        async function sendMessage() {
            if (!currentFriendId) {
                alert("Select a friend to message first.");
                return;
            }

            const text = messageInput.value.trim();
            const file = fileInput.files[0];

            if (text === "" && !file) {
                alert("Please write a message or select an image.");
                return;
            }

            const formData = new FormData();
            formData.append("receiver_id", currentFriendId);
            formData.append("body", text);
            if (file) formData.append("image", file);

            const res = await fetch("api/messages_send.php", { method: "POST", body: formData });
            const data = await res.json();

            if (!data.success) {
                alert(data.message || "Couldn't send that message.");
                return;
            }

            messageInput.value = "";
            fileInput.value = "";
            openChat(currentFriendId);
        }

        sendButton.addEventListener("click", sendMessage);

        messageInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                sendMessage();
            }
        });

        searchInput.addEventListener("keyup", renderChatList);

        loadConversations(openWithId || null);
        pollTimer = setInterval(() => {
            if (currentFriendId) openChat(currentFriendId);
            else loadConversations();
        }, 15000);
    </script>

</body>

</html>
