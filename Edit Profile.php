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
    <title>Edit Profile</title>

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
            background: var(--color-card-bg);
            color: var(--color-card-text);
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
            transition: .3s;
            font-size: 18px;
        }

        nav a:hover {
            background: var(--color-card-bg);
            color: var(--color-card-text);
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
            padding: 30px;
            box-shadow: 0 6px 15px var(--shadow-color);
        }

        h2 {
            color: var(--color-accent-text);
            margin-bottom: 10px;
        }

        p {
            color: var(--color-card-text);
            margin-bottom: 25px;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: var(--color-accent-text);
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--color-placeholder-border);
            border-radius: 10px;
            outline: none;
            font-size: 15px;
            background: var(--color-card-bg);
            color: var(--color-card-text);
        }

        input:focus,
        select:focus {
            border-color: var(--color-app-bg);
            box-shadow: 0 0 5px var(--color-primary);
        }

        .full {
            grid-column: 1 / 3;
        }

        .buttons {
            grid-column: 1 / 3;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 10px;
        }

        .cancel {
            background: var(--color-danger);
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        .save {
            background: var(--color-app-bg);
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        .cancel:hover {
            background: var(--color-danger-hover);
        }

        .save:hover {
            background: var(--color-sidebar-item-bg);
        }

        .save:disabled {
            opacity: .6;
            cursor: not-allowed;
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

            form {
                grid-template-columns: 1fr;
            }

            .full,
            .buttons {
                grid-column: auto;
            }

            .buttons {
                flex-direction: column;
            }

            .buttons button {
                width: 100%;
            }
        }

        #profilePhoto {
            padding: 10px;
            background: var(--color-card-bg);
            cursor: pointer;
        }

        #previewImage {
            display: block;
            margin: 15px auto 0;
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid var(--color-app-bg);
            background: var(--color-placeholder-bg);
        }

        input[type="file"] {
            border: none;
            background: transparent;
            padding: 0;
        }

        input[type="file"]::file-selector-button {
            background: var(--color-app-bg);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: .3s;
            font-size: 15px;
        }

        input[type="file"]::file-selector-button:hover {
            background: var(--color-sidebar-item-bg);
        }
    </style>

</head>

<body>

    <aside>

        <div>

            <div class="logo">
                <img src="cmpLOGO.jpeg" alt="" width="180">
            </div>

            <a href="profiledashboard.php" class="profile-link">Your Profile</a>

            <nav>
                <a href="Home_Feed.php">Home Feed</a>
                <a href="Create_Post.php">Post an Item</a>
                <a href="University_Map.html">University Map</a>
                <a href="Messages.php">Messages</a>
                <a href="profiledashboard.php">Profile</a>
                <a href="Settings.php">Settings</a>
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

            <h2>Edit Profile</h2>
            <p>Update your personal and academic information below.</p>

            <form id="editProfileForm">

                <div class="full">
                    <label>Profile Picture</label>

                    <input type="file" id="profilePhoto" accept="image/*">

                    <br><br>

                    <img id="previewImage" src="<?php echo htmlspecialchars($me['photo'] ?: 'default-profile.png'); ?>" alt="Profile Preview" width="150" height="150"
                        style="border-radius:50%; object-fit:cover; border:4px solid var(--color-app-bg);">
                </div>

                <div class="full">
                    <label>Name</label>
                    <input type="text" id="name" placeholder="Enter Your Name" value="<?php echo htmlspecialchars($me['full_name']); ?>">
                </div>

                <div>
                    <label>Campus ID</label>
                    <input type="text" id="campusId" placeholder="Enter Your ID" value="<?php echo htmlspecialchars($me['student_id'] ?? ''); ?>">
                </div>

                <div>
                    <label>Current Semester</label>
                    <select id="semester">
                        <option>Semester 1</option>
                        <option>Semester 2</option>
                        <option>Semester 3</option>
                        <option>Semester 4</option>
                        <option>Semester 5</option>
                        <option>Semester 6</option>
                        <option>Semester 7</option>
                        <option>Semester 8</option>
                        <option>Semester 9</option>
                        <option>Semester 10</option>
                        <option>Semester 11</option>
                        <option>Semester 12</option>
                    </select>
                </div>

                <div>
                    <label>Department</label>
                    <select id="department">
                        <option>CSE</option>
                        <option>BBA</option>
                        <option>ENG</option>
                        <option>LAW</option>
                        <option>SWE</option>
                        <option>DS</option>
                    </select>
                </div>

                <div>
                    <label>University Email</label>
                    <input type="email" id="email" placeholder="Enter Your Email" value="<?php echo htmlspecialchars($me['email']); ?>">
                </div>

                <div>
                    <label>Batch</label>
                    <input type="text" id="batch" placeholder="Enter Your Batch" value="<?php echo htmlspecialchars($me['batch'] ?? ''); ?>">
                </div>

                <div class="full">
                    <label>Phone Number</label>
                    <input type="tel" id="phone" placeholder="Enter Your Number" value="<?php echo htmlspecialchars($me['phone'] ?? ''); ?>">
                </div>

                <div class="buttons">
                    <button type="button" class="cancel" onclick="window.location.href='profiledashboard.php'">Cancel</button>
                    <button type="submit" class="save" id="saveBtn">Save Changes</button>
                </div>

            </form>

        </div>

    </main>

    <script>

        function compressImage(file, maxWidth, quality) {
            return new Promise(function (resolve, reject) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = new Image();
                    img.onload = function () {
                        let width = img.width;
                        let height = img.height;
                        if (width > maxWidth) {
                            height = Math.round(height * (maxWidth / width));
                            width = maxWidth;
                        }
                        const canvas = document.createElement("canvas");
                        canvas.width = width;
                        canvas.height = height;
                        canvas.getContext("2d").drawImage(img, 0, 0, width, height);
                        resolve(canvas.toDataURL("image/jpeg", quality));
                    };
                    img.onerror = reject;
                    img.src = e.target.result;
                };
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        }

        document.addEventListener("DOMContentLoaded", function () {

            const form = document.getElementById("editProfileForm");
            const searchBox = document.querySelector(".search input");
            const profilePhoto = document.getElementById("profilePhoto");
            const previewImage = document.getElementById("previewImage");

            const name = document.getElementById("name");
            const campusId = document.getElementById("campusId");
            const semester = document.getElementById("semester");
            const department = document.getElementById("department");
            const email = document.getElementById("email");
            const batch = document.getElementById("batch");
            const phone = document.getElementById("phone");

            semester.value = <?php echo json_encode($me['semester'] ?: 'Semester 1'); ?> || "Semester 1";
            department.value = <?php echo json_encode($me['department'] ?: 'CSE'); ?> || "CSE";

            let selectedPhotoFile = null;

            profilePhoto.addEventListener("change", function () {
                const file = profilePhoto.files[0];
                if (!file) return;
                selectedPhotoFile = file;
                compressImage(file, 400, 0.8).then(function (dataUrl) {
                    previewImage.src = dataUrl;
                }).catch(function () {
                    alert("Couldn't preview that image.");
                });
            });

            searchBox.addEventListener("focus", function () { searchBox.style.boxShadow = "0 0 8px var(--color-primary)"; });
            searchBox.addEventListener("blur", function () { searchBox.style.boxShadow = "none"; });

            form.addEventListener("submit", async function (event) {

                event.preventDefault();

                if (name.value.trim() === "") {
                    alert("Please enter your name.");
                    name.focus();
                    return;
                }

                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email.value.trim() !== "" && !emailPattern.test(email.value.trim())) {
                    alert("Please enter a valid email address.");
                    email.focus();
                    return;
                }

                const formData = new FormData();
                formData.append("full_name", name.value.trim());
                formData.append("student_id", campusId.value.trim());
                formData.append("semester", semester.value);
                formData.append("department", department.value);
                formData.append("email", email.value.trim());
                formData.append("batch", batch.value.trim());
                formData.append("phone", phone.value.trim());
                if (selectedPhotoFile) formData.append("photo", selectedPhotoFile);

                const res = await fetch("api/profile_update.php", { method: "POST", body: formData });
                const result = await res.json();

                if (!result.success) {
                    alert(result.message || "Couldn't save changes.");
                    return;
                }

                alert("Profile updated successfully!");
                window.location.href = "profiledashboard.php";

            });

        });
    </script>

</body>

</html>
