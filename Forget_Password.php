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
    <title>Change Password</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        body {
            background: rgb(60, 45, 131);
            display: flex;
            min-height: 100vh;
        }

        aside {
            width: 250px;
            background: rgb(14, 3, 77);
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            color: white;
            font-size: 28px;
            font-weight: bold;
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
            background: rgb(20, 8, 91);
            padding: 12px;
            border-radius: 8px;
            transition: .3s;
            font-size: 18px;
        }

        nav a:hover {
            background: white;
            color: black;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background-image:
                linear-gradient(rgba(60, 45, 131, .55), rgba(60, 45, 131, .55)),
                url("ForgetPass.jpeg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .container {
            width: 520px;
            background: rgba(255,255,255,.95);
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 8px 20px rgba(0,0,0,.3);
        }

        h1 {
            color: rgb(14, 3, 77);
            text-align: center;
            margin-bottom: 10px;
        }

        p {
            color: rgb(70,70,70);
            text-align: center;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: rgb(14,3,77);
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            font-size: 16px;
            margin-bottom: 20px;
            transition: .3s;
        }

        input:focus {
            border-color: rgb(60,45,131);
            box-shadow: 0 0 8px rgba(60,45,131,.4);
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: rgb(20,8,91);
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
        }

        button:hover {
            background: rgb(60,45,131);
        }

        .back {
            text-align: center;
            margin-top: 20px;
        }

        .back a {
            color: rgb(20,8,91);
            text-decoration: none;
            font-weight: bold;
        }

        .back a:hover {
            text-decoration: underline;
        }

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

            .container{
                width:100%;
                max-width:450px;
            }

        }
    </style>

</head>

<body>

    <aside>

        <div class="logo">
            CampusFind
        </div>

        <nav>
            <a href="Home_Feed.php">Home Feed</a>
            <a href="profiledashboard.php">Profile</a>
            <a href="Settings.php">Settings</a>
        </nav>

    </aside>

    <main>

        <div class="container">

            <h1>Change Password</h1>

            <p>
                Update your password to keep your CampusFind account secure.
            </p>

            <form id="changePasswordForm">

                <label for="currentPassword">Current Password</label>
                <input type="password" id="currentPassword" placeholder="Enter Current Password" required>

                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" placeholder="Enter New Password" required>

                <label for="confirmPassword">Confirm New Password</label>
                <input type="password" id="confirmPassword" placeholder="Confirm New Password" required>

                <button type="submit">Change Password</button>

            </form>

            <div class="back">
                <p>Back to <a href="Settings.php">Settings</a></p>
            </div>

        </div>

    </main>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("changePasswordForm");
    const currentPassword = document.getElementById("currentPassword");
    const newPassword = document.getElementById("newPassword");
    const confirmPassword = document.getElementById("confirmPassword");

    const fields = [currentPassword, newPassword, confirmPassword];

    fields.forEach(function (field) {
        field.addEventListener("focus", function () { field.style.boxShadow = "0 0 8px rgba(60,45,131,.5)"; });
        field.addEventListener("blur", function () { field.style.boxShadow = "none"; });
    });

    form.addEventListener("submit", async function (event) {

        event.preventDefault();

        const currentValue = currentPassword.value.trim();
        const newValue = newPassword.value.trim();
        const confirmValue = confirmPassword.value.trim();

        if (currentValue === "") { alert("Please enter your current password."); currentPassword.focus(); return; }
        if (newValue === "") { alert("Please enter a new password."); newPassword.focus(); return; }
        if (newValue.length < 6) { alert("New password must contain at least 6 characters."); newPassword.focus(); return; }
        if (confirmValue !== newValue) { alert("New passwords do not match."); confirmPassword.focus(); return; }

        const params = new URLSearchParams({
            currentPassword: currentValue,
            newPassword: newValue,
            confirmPassword: confirmValue
        });

        const res = await fetch("api/profile_password.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: params.toString()
        });
        const result = await res.json();

        if (!result.success) {
            alert(result.message || "Couldn't change your password.");
            return;
        }

        alert("Password changed successfully!");
        window.location.href = "Settings.php";

    });

});
</script>
</body>
</html>
