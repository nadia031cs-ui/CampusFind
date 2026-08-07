<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$me = currentUser();

$postsStmt = $pdo->prepare('SELECT id, description, location, item_date, item_type, image_path FROM items WHERE user_id = ? ORDER BY created_at DESC');
$postsStmt->execute([$me['id']]);
$myPosts = $postsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Dashboard</title>

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
            margin-bottom: 20px;
        }

        .profile-link {
            display: block;
            text-decoration: none;
            color: white;
            background: var(--color-sidebar-item-bg);
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 25px;
            font-size: 18px;
            transition: .3s;
        }

        .profile-link:hover {
            background: var(--color-icon-btn-bg);
            color: var(--color-app-bg);
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
            background: var(--color-icon-btn-bg);
            color: var(--color-app-bg);
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

        .search {
            margin-bottom: 25px;
        }

        .search input {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            outline: none;
            font-size: 16px;
            background: var(--color-card-bg);
            color: var(--color-card-text);
        }

        .card {
            background: var(--color-card-bg);
            color: var(--color-card-text);
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 6px 15px var(--shadow-color);
        }

        .card h2 {
            color:var(--color-accent-text);
            margin-bottom: 10px;
        }

        .subtitle {
            color: var(--color-text-muted);
            margin-bottom: 30px;
        }

        .profile-section {
            display: flex;
            align-items: flex-start;
            gap: 40px;
            margin-bottom: 35px;
        }

        .profile-image {
            text-align: center;
        }

        .profile-image img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid var(--color-app-bg);
            background: var(--color-placeholder-bg);
        }

        .profile-info {
            flex: 1;
        }

        .info-row {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--color-border);
        }

        .info-title {
            width: 180px;
            font-weight: bold;
            color: var(--color-accent-text);
        }

        .info-value {
            color: var(--color-card-text);
            font-size: 16px;
        }

        .edit-btn {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            background: var(--color-app-bg);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            transition: .3s;
        }

        .edit-btn:hover {
            background: var(--color-sidebar-item-bg);
        }

        .posts {
            margin-top: 30px;
        }

        .posts h3 {
            color: var(--color-app-bg);
            margin-bottom: 15px;
        }

        .posts select {
            width: 250px;
            padding: 12px;
            border: 1px solid var(--color-placeholder-border);
            border-radius: 8px;
            outline: none;
            font-size: 15px;
            background: var(--color-card-bg);
            color: var(--color-card-text);
        }

        .posts select:focus {
            border-color: var(--color-app-bg);
        }

        #myPostsContainer {
            margin-top: 20px;
        }

        .postCard {
            background: var(--color-card-bg);
            color: var(--color-card-text);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px var(--shadow-color);
        }

        .postCard img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin: 10px 0;
        }

        .deleteBtn {
            background: var(--color-danger);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        .deleteBtn:hover {
            background: var(--color-danger-hover);
        }

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

            .profile-section {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-info {
                width: 100%;
            }

            .info-row {
                flex-direction: column;
                gap: 6px;
                text-align: center;
            }

            .info-title {
                width: auto;
            }

            .posts select {
                width: 100%;
            }
        }
    </style>

</head>

<body>

    <aside>

        <div>

            <div class="logo">
                <img src="cmpLOGO.jpeg" alt="CampusFind Logo" width="180">
            </div>

            <a href="profiledashboard.php" class="profile-link">
                Your Profile
            </a>

            <nav>
                <a href="Home_Feed.php">Home Feed</a>
                <a href="Create_Post.php">Post an Item</a>
                <a href="University_Map.html">University Map</a>
                <a href="Messages.php">Messages</a>
                <a href="profiledashboard.php">Profile</a>
                <a href="Settings.php">Settings</a>
                <a href="Notifications.php">Notifications</a>
            </nav>

        </div>

        <div class="logout">
            <form action="api/logout.php" method="post" onsubmit="return confirm('Are you sure you want to logout?');">
                <button type="submit">Logout</button>
            </form>
        </div>

    </aside>

    <main>

        <div class="search">
            <input type="text" placeholder="Search anything...">
        </div>

        <div class="card">

            <h2>Profile Dashboard</h2>

            <p class="subtitle">
                View and manage your profile information.
            </p>

            <div class="profile-section">

                <div class="profile-image">
                    <img id="profilePic" src="<?php echo htmlspecialchars($me['photo'] ?: 'default-profile.png'); ?>" alt="Profile Picture">
                </div>

                <div class="profile-info">

                    <div class="info-row">
                        <div class="info-title">Name</div>
                        <div class="info-value"><?php echo htmlspecialchars($me['full_name']); ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-title">Campus ID</div>
                        <div class="info-value"><?php echo htmlspecialchars($me['student_id'] ?: 'N/A'); ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-title">Department</div>
                        <div class="info-value"><?php echo htmlspecialchars($me['department'] ?: 'N/A'); ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-title">Semester</div>
                        <div class="info-value"><?php echo htmlspecialchars($me['semester'] ?: 'N/A'); ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-title">Batch</div>
                        <div class="info-value"><?php echo htmlspecialchars($me['batch'] ?: 'N/A'); ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-title">University Email</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($me['email']); ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-title">Phone Number</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($me['phone'] ?: 'N/A'); ?>
                        </div>
                    </div>

                    <a href="Edit Profile.php" class="edit-btn">
                        Edit Profile
                    </a>

                </div>

            </div>

            <div class="posts">

                <h3>My Posts</h3>

                <select id="AllPosts">
                    <option>All Posts</option>
                    <option>Lost Items</option>
                    <option>Found Items</option>
                </select>

            </div>

            <div id="myPostsContainer">
                <?php if (empty($myPosts)): ?>
                    <h3>No Posts Yet</h3>
                <?php else: foreach ($myPosts as $post): ?>
                    <div class="postCard" data-type="<?php echo htmlspecialchars($post['item_type']); ?>">
                        <h3><?php echo htmlspecialchars($post['item_type']); ?></h3>
                        <p><?php echo htmlspecialchars($post['description']); ?></p>
                        <p><b>Location:</b> <?php echo htmlspecialchars($post['location']); ?></p>
                        <p><b>Date:</b> <?php echo htmlspecialchars($post['item_date']); ?></p>
                        <?php if ($post['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($post['image_path']); ?>">
                        <?php endif; ?>
                        <button class="deleteBtn" data-id="<?php echo (int) $post['id']; ?>">Delete Post</button>
                    </div>
                <?php endforeach; endif; ?>
            </div>

        </div>

    </main>

    <script>

        document.addEventListener("DOMContentLoaded", function () {

            const logoutBtn = document.querySelector(".logout button");
            const navLinks = document.querySelectorAll("nav a");

            navLinks.forEach(function (link) {
                link.addEventListener("mouseenter", function () { link.style.transform = "translateX(5px)"; });
                link.addEventListener("mouseleave", function () { link.style.transform = "translateX(0)"; });
            });

            const searchBox = document.querySelector(".search input");
            searchBox.addEventListener("focus", function () { searchBox.style.boxShadow = "0 0 8px var(--color-primary)"; });
            searchBox.addEventListener("blur", function () { searchBox.style.boxShadow = "none"; });

            // My Posts Filter (client-side, over the server-rendered posts)
            const postsSelect = document.getElementById("AllPosts");
            const postCards = document.querySelectorAll(".postCard");

            postsSelect.addEventListener("change", function () {
                const filter = postsSelect.value;
                postCards.forEach(card => {
                    const type = card.dataset.type;
                    if (filter === "All Posts") {
                        card.style.display = "";
                    } else if (filter === "Lost Items") {
                        card.style.display = "";
                    } else if (filter === "Found Items") {
                        card.style.display = "";
                    }
                });
            });

            // Delete post buttons
            document.querySelectorAll(".deleteBtn").forEach(btn => {
                btn.addEventListener("click", async function () {
                    if (!confirm("Delete this post?")) return;
                    const res = await fetch("api/items_delete.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + btn.dataset.id
                    });
                    const result = await res.json();
                    if (result.success) {
                        location.reload();
                    } else {
                        alert(result.message || "Couldn't delete this post.");
                    }
                });
            });

        });
    </script>

</body>

</html>
