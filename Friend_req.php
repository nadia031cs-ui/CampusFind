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
    <title>Friend Requests</title>

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

        /* Sidebar */

        aside {
            width: 250px;
            background: var(--color-sidebar-bg);
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
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
            background: var(--color-card-bg);
            color: var(--color-card-text);
        }

        /* Main */

        main {
            flex: 1;
            padding: 35px;
        }

        /* Search */

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
            color: var(--color-accent-text);
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
        }

        .search-bar button:hover {
            background: var(--color-panel-bg);
        }

        /* Card */

        .card {
            background: var(--color-card-bg);
            color: var(--color-card-text);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 6px 15px var(--shadow-color);
        }

        .card h2 {
            color: var(--color-accent-text);
            margin-bottom: 10px;
        }

        .card p {
            color: var(--color-card-text);
            margin-bottom: 25px;
        }

        /* Table */

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
            border-bottom: 1px solid var(--color-placeholder-border);
            color: var(--color-card-text);
        }

        tr:hover {
            background: var(--color-panel-bg);
        }

        /* Buttons */

        .action-btn {
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            transition: .3s;
            font-size: 14px;
        }

        .accept-btn {
            background: #28a745;
        }

        .accept-btn:hover {
            background: #218838;
        }

        .delete-btn {
            background: #dc3545;
            margin-left: 8px;
        }

        .delete-btn:hover {
            background: #bb2d3b;
        }

        /* Empty message */

        .empty-message {
            text-align: center;
            color: var(--color-card-text);
            padding: 40px;
            font-size: 17px;
        }

        /* Back */

        .back {
            margin-top: 25px;
            text-align: center;
        }

        .back a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .back a:hover {
            text-decoration: underline;
        }

        /* Responsive */

        @media(max-width:600px){

            body{
                flex-direction: column;
            }

            aside{
                width:100%;
            }

            main{
                padding:20px;
            }

            table{
                display:block;
                overflow-x:auto;
            }

            .search-bar{
                flex-direction:column;
            }

            .search-bar button{
                width:100%;
            }

            .action-btn{
                width:100%;
                margin:5px 0;
            }

            .delete-btn{
                margin-left:0;
            }
        }
    </style>

    <link rel="stylesheet" href="Friend_req.css">
</head>

<body>

    <aside>

        <div class="logo">
            <img src="cmpLOGO.jpeg" alt="" width="180">
        </div>

        <nav>
            <a href="friends.php">Friends</a>
            <a href="FindFriends.php">Find Friends</a>
            <a href="Friend_req.php">Friend Requests</a>
        </nav>

    </aside>

    <main>

        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search friend requests...">
            <button id="searchBtn">Go</button>
        </div>

        <div class="card">

            <h2>Friend Requests</h2>

            <p>Accept or remove incoming friend requests.</p>

            <table>

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Campus ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody id="requestTable">
                </tbody>

            </table>

        </div>

        <div class="back">
            <p>Back to <a href="Home_Feed.php">Home</a></p>
        </div>

    </main>

    <script>
        // Real, DB-backed friend requests now (api/friends_requests_list.php,
        // api/friends_request_accept.php, api/friends_request_decline.php)
        // instead of the old seeded-fake-users localStorage version.

        let requests = [];

        const table = document.getElementById("requestTable");
        const searchInput = document.getElementById("searchInput");
        const searchBtn = document.getElementById("searchBtn");

        function displayRequests(list) {

            table.innerHTML = "";

            if (list.length === 0) {
                table.innerHTML = `
                    <tr>
                        <td colspan="4" class="empty-message">No friend requests available.</td>
                    </tr>
                `;
                return;
            }

            list.forEach(function (person) {

                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${person.name}</td>
                    <td>${person.department || 'N/A'}</td>
                    <td>${person.studentId || 'N/A'}</td>
                    <td>
                        <button class="action-btn accept-btn">Accept</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </td>
                `;

                row.querySelector(".accept-btn").addEventListener("click", async function () {
                    const res = await fetch("api/friends_request_accept.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "request_id=" + person.requestId
                    });
                    const result = await res.json();
                    if (result.success) {
                        alert(person.name + " is now your friend!");
                        loadRequests();
                    } else {
                        alert(result.message || "Couldn't accept this request.");
                    }
                });

                row.querySelector(".delete-btn").addEventListener("click", async function () {
                    if (!confirm("Delete friend request from " + person.name + "?")) return;
                    await fetch("api/friends_request_decline.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "request_id=" + person.requestId
                    });
                    loadRequests();
                });

                table.appendChild(row);

            });

        }

        function searchRequests() {
            const keyword = searchInput.value.toLowerCase();
            const filtered = requests.filter(function (person) {
                return (
                    person.name.toLowerCase().includes(keyword) ||
                    (person.department || '').toLowerCase().includes(keyword) ||
                    (person.studentId || '').toLowerCase().includes(keyword)
                );
            });
            displayRequests(filtered);
        }

        async function loadRequests() {
            const res = await fetch("api/friends_requests_list.php");
            const data = await res.json();
            if (!data.success) return;
            requests = data.requests;
            searchRequests();
        }

        searchInput.addEventListener("keyup", searchRequests);
        searchBtn.addEventListener("click", searchRequests);
        window.addEventListener("focus", loadRequests);

        loadRequests();
    </script>

</body>

</html>
