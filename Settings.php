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
    <title>Document</title>

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
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
}

body{
    background:var(--color-app-bg);
    display:flex;
    min-height:100vh;
}

img{
    position:fixed;
    top:20px;
    left:20px;
    width:180px;
    z-index:1000;
}

aside{
    width:250px;
    min-height:100vh;
    background:var(--color-sidebar-bg);
    padding:140px 20px 20px;
    position:fixed;
    left:0;
    top:0;
}

aside nav{
     width: 230px;
            background: var(--color-sidebar-bg);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
}

aside nav a{
    text-decoration:none;
    color:white;
    background:var(--color-sidebar-item-bg);
    padding:12px 15px;
    border-radius:8px;
    font-size:18px;
    transition:.3s;
    position:relative;
    display:inline-block;
}

aside nav a:hover{
    background:var(--color-icon-btn-bg);
    color:var(--color-app-bg);
}

.nav-badge{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    background:var(--color-danger);
    color:white;
    font-size:12px;
    font-weight:bold;
    padding:2px 8px;
    border-radius:999px;
    display:none;
}

main{
    margin-left:270px;
    width:100%;
    padding:30px;
}

main input[type="text"]:first-child{
    width:100%;
    padding:12px 15px;
    border:none;
    border-radius:10px;
    margin-bottom:25px;
    font-size:16px;
    outline:none;
    background:var(--color-card-bg);
    color:var(--color-card-text);
}

form{
    background:var(--color-card-bg);
    color:var(--color-card-text);
    padding:30px;
    border-radius:12px;
    box-shadow:0 6px 15px var(--shadow-color);
    margin-bottom:25px;
}

h2{
    color:var(--color-accent-text);
    margin-bottom:10px;
}

p{
    color:var(--color-text-muted);
    margin-bottom:20px;
}

label{
    display:block;
    margin-top:12px;
    margin-bottom:6px;
    font-weight:bold;
    color:var(--color-accent-text);
}

form input,
form select{
    width:100%;
    padding:12px;
    border:1px solid var(--color-placeholder-border);
    border-radius:10px;
    margin-bottom:15px;
    font-size:15px;
    background:var(--color-card-bg);
    color:var(--color-card-text);
}

button{
    background:var(--color-app-bg);
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
    font-weight:bold;
    transition:.3s;
}

button:hover{
    background:var(--color-sidebar-item-bg);
}

@media(max-width:600px){

    body{
        flex-direction:column;
    }

    img{
        position:static;
        display:block;
        margin:20px auto;
    }

    aside{
        position:static;
        width:100%;
        min-height:auto;
        padding:20px;
    }

    main{
        margin-left:0;
        padding:20px;
    }
}
</style>
   
</head>

<body>

    <img src="cmpLOGO.jpeg" alt="" width="180">
    <aside>
        <nav>
        <a href="Home_Feed.php">Home Feed</a>
        <br>
        <a href="Create_Post.php">Post an Item</a>
        <br>
        <a href="University_Map.html">University Map</a>
        <br>
        <a href="Messages.php">Messages</a>
        <br>
        <a href="profiledashboard.php">Profile</a>
        <br>
        <a href="Settings.php">Settings</a>
        <br>
        <a href="Notifications.php">Notifications <span class="nav-badge" id="navBadge"><?php echo unreadNotificationCount($me['id']); ?></span></a>

        </nav>
    </aside>
    <main>
         <form id="settingsForm">
        <input type="text" placeholder="Search anything...">
       <br>
       <h2>Settings</h2>
  <p> Manage your account preferences and application settings.</p>
     <label for="fullName">Full Name</label>
     <input type="text" id="fullName" placeholder="Enter Your Name" value="<?php echo htmlspecialchars($me['full_name']); ?>">
     <br>
     <label for="campusId">Campus ID</label>
     <input type="text" id="campusId" placeholder="Campus ID" value="<?php echo htmlspecialchars($me['student_id'] ?? ''); ?>">
     <br>
     <label for="email">Email Address</label>
     <input type="email" id="email" placeholder="Enter Your Email" value="<?php echo htmlspecialchars($me['email']); ?>">
     <br>
     <label for="Department">Department</label>
     <select  id="Department">
        <option value="CSE">CSE</option>
            <option value="BBA">BBA</option>
            <option value="ENG">ENG</option>
            <option value="LAW">LAW</option>
            <option value="SWE">SWE</option>
            <option value="DS">DS</option>
     </select>
     <br>
     <label for="Current Semester">Current Semester</label>
        <select id="Current Semester">
            <option value="Semester 1">Semester 1</option>
            <option value="Semester 2">Semester 2</option>
            <option value="Semester 3">Semester 3</option>
            <option value="Semester 4">Semester 4</option>
            <option value="Semester 5">Semester 5</option>
            <option value="Semester 6">Semester 6</option>
            <option value="Semester 7">Semester 7</option>
            <option value="Semester 8">Semester 8</option>
            <option value="Semester 9">Semester 9</option>
            <option value="Semester 10">Semester 10</option>
            <option value="Semester 11">Semester 11</option>
            <option value="Semester 12">Semester 12</option>

        </select>
        <br>

        <label for="batch">Batch</label>
        <input type="text" id="batch" placeholder="Batch" value="<?php echo htmlspecialchars($me['batch'] ?? ''); ?>">
        <br>

     <p style="margin-top:20px;"><a href="Forget_Password.php">Change Password</a></p>

        <button type="submit">Save Changes</button>

    </form>

    </main>
    <script>

        document.addEventListener("DOMContentLoaded", function () {

            const form = document.getElementById("settingsForm");
            const search = document.querySelector('main input[type="text"]:first-of-type');

            const fullName = document.getElementById("fullName");
            const campusId = document.getElementById("campusId");
            const email = document.getElementById("email");
            const department = document.getElementById("Department");
            const semester = document.getElementById("Current Semester");
            const batch = document.getElementById("batch");

            department.value = <?php echo json_encode($me['department'] ?? 'CSE'); ?> || "CSE";
            semester.value = <?php echo json_encode($me['semester'] ?? 'Semester 1'); ?> || "Semester 1";

            const inputs = document.querySelectorAll("input, select");

            inputs.forEach(function (input) {
                input.addEventListener("focus", function () {
                    input.style.boxShadow = "0 0 8px var(--color-primary)";
                });
                input.addEventListener("blur", function () {
                    input.style.boxShadow = "none";
                });
            });

            form.addEventListener("submit", async function (event) {

                event.preventDefault();

                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (fullName.value.trim() === "") {
                    alert("Please enter your full name.");
                    fullName.focus();
                    return;
                }
                if (email.value.trim() !== "" && !emailPattern.test(email.value.trim())) {
                    alert("Please enter a valid email address.");
                    email.focus();
                    return;
                }

                const formData = new FormData();
                formData.append("full_name", fullName.value.trim());
                formData.append("student_id", campusId.value.trim());
                formData.append("email", email.value.trim());
                formData.append("department", department.value);
                formData.append("semester", semester.value);
                formData.append("batch", batch.value.trim());

                const res = await fetch("api/profile_update.php", { method: "POST", body: formData });
                const result = await res.json();

                if (!result.success) {
                    alert(result.message || "Couldn't save changes.");
                    return;
                }

                alert("Settings updated successfully!");
                window.location.href = "profiledashboard.php";

            });

        });
    </script>
</body>
</html>
