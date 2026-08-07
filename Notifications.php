<?php
require_once __DIR__ . '/includes/notifications.php';
requireLogin();
$me = currentUser();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>

    <!-- Apply saved/preferred theme BEFORE first paint, to avoid a flash of the wrong theme -->
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
            background: var(--color-icon-btn-bg);
            color: var(--color-app-bg);
        }

        nav a.active {
            background: var(--color-icon-btn-bg);
            color: var(--color-app-bg);
            font-weight: bold;
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

        .panel {
            background: var(--color-card-bg);
            border-radius: 12px;
            box-shadow: 0 6px 15px var(--shadow-color);
            overflow: hidden;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 25px;
            border-bottom: 1px solid var(--color-border);
            flex-wrap: wrap;
            gap: 12px;
        }

        .panel-header h3 {
            color: var(--color-accent-text);
        }

        .filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-btn {
            border: 1px solid var(--color-accent-text);
            background: var(--color-card-bg);
            color:var(--color-accent-text);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            transition: .3s;
        }

        .filter-btn.active,
        .filter-btn:hover {
            background: var(--color-app-bg);
            color: white;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .header-actions button {
            border: none;
            background: var(--color-list-item-bg);
            color: var(--color-app-bg);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            font-weight: bold;
            transition: .3s;
        }

        .header-actions button:hover {
            background: var(--color-app-bg);
            color: white;
        }

        .notif-list {
            display: flex;
            flex-direction: column;
            max-height: 70vh;
            overflow-y: auto;
        }

        .notif-item {
            display: flex;
            gap: 15px;
            padding: 18px 25px;
            border-bottom: 1px solid var(--color-border);
            cursor: pointer;
            transition: .2s;
            position: relative;
        }

        .notif-item:hover {
            background: var(--color-hover-tint);
        }

        .notif-item.unread {
            background: var(--color-unread-bg);
        }

        .notif-item.unread:hover {
            background: var(--color-unread-hover-bg);
        }

        .notif-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .icon-message { background: rgb(60, 45, 131); }
        .icon-item { background: rgb(40, 160, 100); }
        .icon-friend { background: rgb(230, 150, 30); }
        .icon-admin { background: rgb(220, 53, 69); }
        .icon-post { background: rgb(70, 130, 200); }
        .icon-like { background: rgb(225, 60, 130); }
        .icon-settings { background: rgb(110, 110, 120); }

        .notif-body {
            flex: 1;
        }

        .notif-text {
            color: var(--color-card-text);
            margin-bottom: 4px;
        }

        .notif-time {
            color: var(--color-text-muted);
            font-size: 13px;
        }

        .unread-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--color-app-bg);
            align-self: center;
        }

        .delete-btn {
            background: none;
            border: none;
            color: var(--color-text-muted);
            font-size: 18px;
            cursor: pointer;
            align-self: center;
            padding: 4px 8px;
            border-radius: 6px;
            transition: .2s;
        }

        .delete-btn:hover {
            color: var(--color-danger);
            background: var(--color-danger-tint);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--color-text-muted);
        }

        @media(max-width:500px) {

            body {
                flex-direction: column;
            }

            aside {
                width: 100%;
            }

            .panel-header {
                flex-direction: column;
                align-items: flex-start;
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
                <a href="Notifications.php" class="active">Notifications <span class="nav-badge" id="navBadge"><?php echo unreadNotificationCount($me['id']); ?></span>
                </a>
            </nav>

        </div>

        <div class="logout">
            <form action="api/logout.php" method="post" onsubmit="return confirm('Are you sure you want to logout?');">
                <button type="submit">Logout</button>
            </form>
        </div>

    </aside>

    <main>

        <h2>Notifications</h2>

        <p class="subtitle">
            Stay updated on messages, feed activity, likes, and account changes.
        </p>

        <div class="panel">

            <div class="panel-header">

                <div class="filters">
                    <button class="filter-btn active" data-filter="all">All</button>
                    <button class="filter-btn" data-filter="unread">Unread</button>
                    <button class="filter-btn" data-filter="message">Messages</button>
                    <button class="filter-btn" data-filter="friend">Friend Requests</button>
                    <button class="filter-btn" data-filter="post">Feed Posts</button>
                    <button class="filter-btn" data-filter="like">Likes</button>
                </div>

                <div class="header-actions">
                    <button id="markAllBtn">Mark all as read</button>
                    <button id="clearAllBtn">Clear all</button>
                </div>

            </div>

            <div class="notif-list" id="notifList"></div>

        </div>

    </main>

    <script>
        // Notifications are now real, DB-backed (api/notifications_list.php / notifications_read.php / notifications_delete.php)
        // instead of the old shared-localStorage simulation.

        const notifList = document.getElementById("notifList");
        const navBadge = document.getElementById("navBadge");
        const filterBtns = document.querySelectorAll(".filter-btn");
        const markAllBtn = document.getElementById("markAllBtn");
        const clearAllBtn = document.getElementById("clearAllBtn");

        let notifications = [];
        let currentFilter = "all";

        const icons = {
            message: "💬",
            item: "📦",
            friend: "👥",
            admin: "🛡️",
            post: "📝",
            like: "❤️",
            settings: "⚙️"
        };

        function formatTimeAgo(isoTime) {
            const timestamp = new Date(isoTime.replace(" ", "T")).getTime();
            const seconds = Math.floor((Date.now() - timestamp) / 1000);
            if (seconds < 60) return "Just now";
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return minutes + (minutes === 1 ? " minute ago" : " minutes ago");
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return hours + (hours === 1 ? " hour ago" : " hours ago");
            const days = Math.floor(hours / 24);
            if (days < 7) return days + (days === 1 ? " day ago" : " days ago");
            const weeks = Math.floor(days / 7);
            if (weeks < 4) return weeks + (weeks === 1 ? " week ago" : " weeks ago");
            const months = Math.floor(days / 30);
            if (months < 12) return months + (months === 1 ? " month ago" : " months ago");
            const years = Math.floor(days / 365);
            return years + (years === 1 ? " year ago" : " years ago");
        }

        function updateBadge(unreadCount) {
            navBadge.textContent = unreadCount;
            navBadge.style.display = unreadCount === 0 ? "none" : "inline-block";
        }

        async function loadNotifications() {
            const res = await fetch("api/notifications_list.php");
            const data = await res.json();
            if (!data.success) return;
            notifications = data.notifications;
            updateBadge(data.unreadCount);
            renderNotifications();
        }

        function renderNotifications() {

            notifList.innerHTML = "";

            let filtered = notifications;
            if (currentFilter === "unread") {
                filtered = notifications.filter(n => !n.read);
            } else if (currentFilter !== "all") {
                filtered = notifications.filter(n => n.type === currentFilter);
            }

            if (filtered.length === 0) {
                notifList.innerHTML = `<div class="empty-state">No notifications here.</div>`;
                return;
            }

            filtered.forEach(n => {

                const item = document.createElement("div");
                item.className = "notif-item" + (n.read ? "" : " unread");

                item.innerHTML = `
                    <div class="notif-icon icon-${n.type}">${icons[n.type] || "🔔"}</div>
                    <div class="notif-body">
                        <div class="notif-text">${n.text}</div>
                        <div class="notif-time">${formatTimeAgo(n.time)}</div>
                    </div>
                    ${!n.read ? '<div class="unread-dot"></div>' : ""}
                    <button class="delete-btn" title="Delete">&times;</button>
                `;

                item.addEventListener("click", async (e) => {
                    if (e.target.classList.contains("delete-btn")) return;
                    if (!n.read) {
                        await fetch("api/notifications_read.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: "id=" + n.id
                        });
                    }
                    if (n.link) window.location.href = n.link;
                    else loadNotifications();
                });

                item.querySelector(".delete-btn").addEventListener("click", async (e) => {
                    e.stopPropagation();
                    await fetch("api/notifications_delete.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + n.id
                    });
                    loadNotifications();
                });

                notifList.appendChild(item);

            });

        }

        filterBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                filterBtns.forEach(b => b.classList.remove("active"));
                btn.classList.add("active");
                currentFilter = btn.dataset.filter;
                renderNotifications();
            });
        });

        markAllBtn.addEventListener("click", async () => {
            await fetch("api/notifications_read.php", { method: "POST" });
            loadNotifications();
        });

        clearAllBtn.addEventListener("click", async () => {
            if (confirm("Clear all notifications?")) {
                await fetch("api/notifications_delete.php", { method: "POST" });
                loadNotifications();
            }
        });

        loadNotifications();
        setInterval(loadNotifications, 30000);
    </script>

</body>

</html>
