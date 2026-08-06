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

            <a href="profiledashboard.html" class="profile-link">Your Profile</a>

            <nav>
                <a href="Home_Feed.html">Home Feed</a>
                <a href="Post_an_Item.html">Post an Item</a>
                <a href="University_Map.html">University Map</a>
                <a href="Messages.html">Messages</a>
                <a href="Profile.html">Profile</a>
                <a href="Settings.html">Settings</a>
            </nav>

        </div>

        <div class="logout">
            <button>Logout</button>
        </div>

    </aside>

    <main>

        <div class="search">
            <input type="text" placeholder="Search anything...">
        </div>

        <div class="card">

            <h2>Edit Profile</h2>
            <p>Update your personal and academic information below.</p>

            <form action="">

                <div class="full">
                    <label>Profile Picture</label>

                    <input type="file" id="profilePhoto" accept="image/*">

                    <br><br>

                    <img id="previewImage" src="default-profile.png" alt="Profile Preview" width="150" height="150"
                        style="border-radius:50%; object-fit:cover; border:4px solid var(--color-app-bg);">
                </div>

                <div class="full">
                    <label>Name</label>
                    <input type="text" id="name" placeholder="Enter Your Name">
                </div>

                <div>
                    <label>Campus ID</label>
                    <input type="text" id="campusId" placeholder="Enter Your ID">
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
                    <input type="email" id="email" placeholder="Enter Your Email">
                </div>

                <div>
                    <label>Batch</label>
                    <input type="text" id="batch" placeholder="Enter Your Batch">
                </div>

                <div class="full">
                    <label>Phone Number</label>
                    <input type="tel" id="phone" placeholder="Enter Your Number">
                </div>

                <div class="buttons">
                    <button type="reset" class="cancel">Cancel</button>
                    <button type="submit" class="save" id="saveBtn">Save Changes</button>
                </div>

            </form>

        </div>

    </main>


    <script>

        // ============================================================
        // IMAGE COMPRESSION (same approach as Create_Post.html)
        // Profile photos display small (circular, ~160px), so we can
        // compress harder than post images: max 400px wide, 70% JPEG
        // quality. This is what was missing before — the old code
        // saved the raw, uncompressed file straight into localStorage,
        // which is why `profile` grew to 1.75MB from a single photo.
        // ============================================================

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

                        const ctx = canvas.getContext("2d");
                        ctx.drawImage(img, 0, 0, width, height);

                        resolve(canvas.toDataURL("image/jpeg", quality));

                    };

                    img.onerror = function () {
                        reject(new Error("Could not read the selected image."));
                    };

                    img.src = e.target.result;

                };

                reader.onerror = function () {
                    reject(new Error("Could not read the selected file."));
                };

                reader.readAsDataURL(file);

            });

        }

        document.addEventListener("DOMContentLoaded", function () {

            console.log("Edit Profile Page Loaded");

            // Logout Button
            const logoutBtn = document.querySelector(".logout button");

            logoutBtn.addEventListener("click", function () {

                if (confirm("Are you sure you want to logout?")) {
                    alert("You have been logged out.");
                    window.location.href = "Login.html";
                }

            });

            // Navigation Hover
            const navLinks = document.querySelectorAll("nav a");

            navLinks.forEach(function (link) {

                link.addEventListener("mouseenter", function () {
                    link.style.transform = "translateX(5px)";
                });

                link.addEventListener("mouseleave", function () {
                    link.style.transform = "translateX(0)";
                });

            });

            // Search Box
            const searchBox = document.querySelector(".search input");

            searchBox.addEventListener("keydown", function (event) {

                if (event.key === "Enter") {

                    event.preventDefault();

                    const keyword = searchBox.value.trim();

                    if (keyword === "") {
                        alert("Please enter something to search.");
                        return;
                    }

                    alert("Searching for: " + keyword);

                }

            });

            searchBox.addEventListener("focus", function () {
                searchBox.style.boxShadow = "0 0 8px var(--color-primary)";
            });

            searchBox.addEventListener("blur", function () {
                searchBox.style.boxShadow = "none";
            });

            // Profile Picture Preview — now compressed before it ever
            // touches the <img> src, so what gets previewed is exactly
            // what gets saved (small, and already the final version).
            const profilePhoto = document.getElementById("profilePhoto");
            const previewImage = document.getElementById("previewImage");

            profilePhoto.addEventListener("change", function () {

                const file = this.files[0];

                if (!file) {
                    return;
                }

                compressImage(file, 200, 0.4)
                    .then(function (compressedDataUrl) {
                        previewImage.src = compressedDataUrl;
                    })
                    .catch(function (err) {
                        console.error("Image compression failed:", err);
                        alert("Couldn't process that image. Please try a different photo.");
                    });

            });

            // Load Saved Profile
            const savedProfile = JSON.parse(localStorage.getItem("profile"));

            if (savedProfile) {

                document.getElementById("name").value = savedProfile.name || "";
                document.getElementById("campusId").value = savedProfile.id || "";
                document.getElementById("semester").value = savedProfile.semester || "Semester 1";
                document.getElementById("department").value = savedProfile.department || "CSE";
                document.getElementById("email").value = savedProfile.email || "";
                document.getElementById("batch").value = savedProfile.batch || "";
                document.getElementById("phone").value = savedProfile.phone || "";

                if (savedProfile.photo) {
                    previewImage.src = savedProfile.photo;
                }

            }

            // Save Profile
            const form = document.querySelector("form");
            const saveBtn = document.getElementById("saveBtn");

            form.addEventListener("submit", function (event) {

                event.preventDefault();

                const profile = {

                    name: document.getElementById("name").value.trim(),

                    id: document.getElementById("campusId").value.trim(),

                    semester: document.getElementById("semester").value,

                    department: document.getElementById("department").value,

                    email: document.getElementById("email").value.trim(),

                    batch: document.getElementById("batch").value.trim(),

                    phone: document.getElementById("phone").value.trim(),

                    photo: previewImage.src

                };

                if (
                    profile.name === "" ||
                    profile.id === "" ||
                    profile.email === "" ||
                    profile.batch === "" ||
                    profile.phone === ""
                ) {

                    alert("Please fill in all required fields.");
                    return;

                }

                saveBtn.disabled = true;
                saveBtn.textContent = "Saving...";

                try {

                    localStorage.setItem("profile", JSON.stringify(profile));

                    alert("Profile updated successfully!");

                    window.location.href = "profiledashboard.html";

                } catch (err) {

                    console.error("Failed to save profile:", err);

                    alert(
                        "Couldn't save your profile — local storage is full. " +
                        "Try deleting a few old posts (with images) from your profile and save again."
                    );

                    saveBtn.disabled = false;
                    saveBtn.textContent = "Save Changes";

                }

            });

            // Reset Button
            const resetBtn = document.querySelector(".cancel");

            resetBtn.addEventListener("click", function (event) {

                if (!confirm("Are you sure you want to clear all fields?")) {
                    event.preventDefault();
                }

            });

            // Input Highlight
            const inputs = document.querySelectorAll("input, select");

            inputs.forEach(function (input) {

                input.addEventListener("focus", function () {

                    input.style.backgroundColor = "var(--color-card-bg)";

                });

                input.addEventListener("blur", function () {

                    input.style.backgroundColor = "var(--color-card-bg)";

                });

            });

        });
    </script>



</body>

</html>
