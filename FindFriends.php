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
    <title>Find Friends</title>

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
            transform: translateX(5px);
        }

        /* Main */

        main {
            flex: 1;
            padding: 35px;
        }

        /* Top Search */

        .top-search {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
        }

        .top-search input {
            flex: 1;
            padding: 13px;
            border: none;
            border-radius: 10px;
            outline: none;
            font-size: 16px;
            background: var(--color-card-bg);
            color: var(--color-card-text);
        }

        .top-search button {
            padding: 13px 25px;
            border: none;
            border-radius: 10px;
            background: var(--color-sidebar-item-bg);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
        }

        .top-search button:hover {
            background: var(--color-sidebar-item-hover-bg);
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

        /* Search Form */

        .search-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .field {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            color: var(--color-accent-text);
            margin-bottom: 6px;
        }

        input,
        select {
            padding: 12px;
            border: 1px solid var(--color-placeholder-border);
            border-radius: 10px;
            outline: none;
            font-size: 15px;
            transition: .3s;
            background: var(--color-card-bg);
            color: var(--color-card-text);
        }

        input:focus,
        select:focus {
            border-color: var(--color-app-bg);
            box-shadow: 0 0 5px var(--color-primary);
        }

        /* Results */

        .results {
            grid-column: 1 / 3;
            margin-top: 20px;
            padding: 20px;
            background: var(--color-panel-bg);
            border-radius: 10px;
            min-height: 250px;
        }

        .results h3 {
            color: var(--color-accent-text);
            margin-bottom: 15px;
        }

        /* User Card */

        .user-card {
            background: var(--color-card-bg);
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px var(--shadow-color);
        }

        .user-card h4 {
            color: var(--color-accent-text);
            margin-bottom: 10px;
        }

        .user-card p {
            margin: 5px 0;
            color: var(--color-card-text);
        }

        .user-card button {
            margin-top: 12px;
            background: var(--color-app-bg);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: .3s;
        }

        .user-card button:hover {
            background: var(--color-sidebar-item-bg);
        }

        .user-card button:disabled {
            background: gray;
            cursor: not-allowed;
        }

        /* Back */

        .back {
            margin-top: 25px;
        }

        .back a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .back a:hover {
            text-decoration: underline;
        }

        /* Responsive */

        @media (max-width:900px) {

            body {
                flex-direction: column;
            }

            aside {
                width: 100%;
            }

            main {
                padding: 20px;
            }

            .search-form {
                grid-template-columns: 1fr;
            }

            .results {
                grid-column: auto;
            }

            .top-search {
                flex-direction: column;
            }

            .top-search button {
                width: 100%;
            }
        }
    </style>

    <link rel="stylesheet" href="FindFriends.css">
</head>

<body>

    <aside>

        <div class="logo">
            <img src="cmpLOGO.jpeg" alt="Logo" width="180">
        </div>

        <nav>
            <a href="friends.php">Friends</a>
            <a href="FindFriends.php">Find Friends</a>
            <a href="Friend_req.php">Friend Requests</a>
        </nav>

    </aside>

    <main>

        <div class="top-search">
            <input type="text" id="topSearch" placeholder="Search anything...">
            <button id="topSearchBtn">Go</button>
        </div>

        <div class="card">

            <h2>Find Friends</h2>

            <p>Search for students and teachers at the university and connect with them.</p>

            <div class="search-form">

                <div class="field">
                    <label>Email Address</label>
                    <input type="email" id="emailInput" placeholder="Search Email">
                </div>

                <div class="field">
                    <label>Department</label>
                    <select id="departmentInput">
                        <option value="">All Departments</option>
                        <option value="CSE">CSE</option>
                        <option value="BBA">BBA</option>
                        <option value="ENG">ENG</option>
                        <option value="LAW">LAW</option>
                        <option value="SWE">SWE</option>
                        <option value="DS">DS</option>
                    </select>
                </div>

                <div class="field">
                    <label>ID</label>
                    <input type="text" id="idInput" placeholder="Search ID">
                </div>

                <div class="field">
                    <label>Name</label>
                    <input type="text" id="nameInput" placeholder="Search Name">
                </div>

                <div class="results" id="results">

                    <h3>Search Results</h3>

                    <p>Use the search fields above to find students.</p>

                </div>

            </div>

        </div>

    </main>

    <script>
        // Real users now (api/friends_search.php + api/friends_request_send.php)
        // instead of the old hardcoded 7-user list.

        const topSearch = document.getElementById("topSearch");
        const topSearchBtn = document.getElementById("topSearchBtn");
        const emailInput = document.getElementById("emailInput");
        const departmentInput = document.getElementById("departmentInput");
        const idInput = document.getElementById("idInput");
        const nameInput = document.getElementById("nameInput");
        const resultBox = document.getElementById("results");

        async function searchUsers() {

            const params = new URLSearchParams({
                q: topSearch.value.trim(),
                email: emailInput.value.trim(),
                department: departmentInput.value,
                id: idInput.value.trim(),
                name: nameInput.value.trim(),
            });

            const res = await fetch("api/friends_search.php?" + params.toString());
            const data = await res.json();

            resultBox.innerHTML = "<h3>Search Results</h3>";

            if (!data.success || data.users.length === 0) {
                resultBox.innerHTML += "<p>No users found.</p>";
                return;
            }

            data.users.forEach(user => {

                const card = document.createElement("div");
                card.className = "user-card";

                card.innerHTML = `
                    <h4>${user.name}</h4>
                    <p><strong>ID:</strong> ${user.studentId || 'N/A'}</p>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Department:</strong> ${user.department || 'N/A'}</p>
                    <button ${user.requestSent ? "disabled" : ""}>
                        ${user.requestSent ? "Request Sent" : "Add Friend"}
                    </button>
                `;

                const btn = card.querySelector("button");

                btn.addEventListener("click", async function () {
                    const res = await fetch("api/friends_request_send.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "receiver_id=" + user.id
                    });
                    const result = await res.json();

                    if (!result.success) {
                        alert(result.message || "Couldn't send the request.");
                        return;
                    }

                    btn.textContent = result.status === "friends" ? "Friends" : "Request Sent";
                    btn.disabled = true;
                    alert(result.message);
                });

                resultBox.appendChild(card);

            });

        }

        topSearchBtn.addEventListener("click", searchUsers);
        topSearch.addEventListener("keyup", function (e) { if (e.key === "Enter") searchUsers(); });
        emailInput.addEventListener("input", searchUsers);
        departmentInput.addEventListener("change", searchUsers);
        idInput.addEventListener("input", searchUsers);
        nameInput.addEventListener("input", searchUsers);
        window.addEventListener("focus", searchUsers);

        searchUsers();
    </script>

</body>

</html>
