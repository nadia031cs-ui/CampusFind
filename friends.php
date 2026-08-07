<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$me = currentUser();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>

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
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .theme-toggle {
            margin: 0 auto 20px auto;
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
        }

        nav a:hover {
            background: white;
            color: black;
        }

        main {
            flex: 1;
            padding: 35px;
        }

        .search-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
        }

        .search-bar input {
            flex: 1;
            padding: 13px;
            border: none;
            border-radius: 10px;
            outline: none;
            font-size: 16px;
            background: var(--color-card-bg);
            color: var(--color-card-text);
        }

        .search-bar button {
            padding: 13px 25px;
            border: none;
            border-radius: 10px;
            background: var(--color-card-bg);
            color: var(--color-app-bg);
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
        }

        .search-bar button:hover {
            background: var(--color-muted-text);
        }

        .card {
            background: var(--color-card-bg);
            color: var(--color-card-text);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 6px 15px var(--shadow-color);
        }

        h2 {
            color:var(--color-accent-text);
            margin-bottom: 10px;
        }

        .card p {
            color: var(--color-text-muted);
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: var(--color-app-bg);
            color: white;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--color-border);
        }

        tr:hover {
            background: var(--color-placeholder-bg);
        }

        .action-btn {
            background: var(--color-app-bg);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: .3s;
        }

        .action-btn:hover {
            background: var(--color-sidebar-item-bg);
        }

        .back {
            margin-top: 25px;
            text-align: center;
        }

        .back a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .back a:hover {
            text-decoration: underline;
        }

        @media(max-width:600px) {

            body {
                flex-direction: column;
            }

            aside {
                width: 100%;
            }

            main {
                padding: 20px;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            .search-bar {
                flex-direction: column;
            }

            .search-bar button {
                width: 100%;
            }
        }
    </style>

</head>

<body>

    <aside>

        <div class="logo">
            <img src="cmpLOGO.jpeg" alt="" width="180">
        </div>

        <button id="theme-toggle" class="theme-toggle" aria-label="Toggle dark mode">🌙</button>

        <nav>
            <a href="friends.php">Friends</a>
            <a href="FindFriends.php">Find Friends</a>
            <a href="Friend_req.php">Friend Requests</a>
        </nav>

    </aside>

    <main>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search anything...">
            <button id="searchBtn">Go</button>
        </div>

        <div class="card">

            <h2>Friends</h2>

            <p>Connect with friends and stay updated together.</p>

            <table>

                <thead>
                    <tr>
                        <th>Friends</th>
                        <th>Department</th>
                        <th>Campus ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody id="friendsTable">
                    <tr>
                        <td colspan="4" style="text-align:center;color:var(--color-text-muted);padding:40px;">
                            Loading...
                        </td>
                    </tr>
                </tbody>

            </table>

        </div>

        <div class="back">
            <p>Back to <a href="Home_Feed.php">Home</a></p>
        </div>

    </main>

    <script src="theme.js"></script>

    <script>
        // Real friends now (api/friends_list.php + api/friends_remove.php)
        // instead of the old hardcoded/localStorage seed list.

        let friends = [];

        const tbody = document.getElementById("friendsTable");
        const searchInput = document.getElementById("searchInput");
        const searchButton = document.getElementById("searchBtn");

        function displayFriends(list) {

            tbody.innerHTML = "";

            if (list.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" style="text-align:center;color:var(--color-text-muted);padding:40px;">
                            No friends to display.
                        </td>
                    </tr>
                `;
                return;
            }

            list.forEach((friend) => {

                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${friend.name}</td>
                    <td>${friend.department || 'N/A'}</td>
                    <td>${friend.studentId || 'N/A'}</td>
                    <td>
                        <button class="action-btn message-btn">Message</button>
                        <button class="action-btn remove-btn" style="background:#dc3545;margin-left:8px;">Unfriend</button>
                    </td>
                `;

                row.querySelector(".message-btn").addEventListener("click", () => {
                    window.location.href = "Messages.php?with=" + friend.id;
                });

                row.querySelector(".remove-btn").addEventListener("click", async () => {
                    if (!confirm("Remove " + friend.name + " from your friends?")) return;
                    await fetch("api/friends_remove.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "friend_id=" + friend.id
                    });
                    loadFriends();
                });

                tbody.appendChild(row);

            });

        }

        function searchFriends() {
            const keyword = searchInput.value.toLowerCase();
            const filtered = friends.filter(friend =>
                friend.name.toLowerCase().includes(keyword) ||
                (friend.department || '').toLowerCase().includes(keyword) ||
                (friend.studentId || '').toLowerCase().includes(keyword)
            );
            displayFriends(filtered);
        }

        async function loadFriends() {
            const res = await fetch("api/friends_list.php");
            const data = await res.json();
            if (!data.success) return;
            friends = data.friends;
            searchFriends();
        }

        searchInput.addEventListener("keyup", searchFriends);
        searchButton.addEventListener("click", searchFriends);
        window.addEventListener("focus", loadFriends);

        loadFriends();
    </script>

</body>

</html>
