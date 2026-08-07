<?php
require_once __DIR__ . '/includes/auth.php';
// If already logged in, skip straight to the feed
if (isLoggedIn()) {
    header('Location: Home_Feed.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In</title>
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
background-image: url("./Floors/uni.jpeg");
min-height: 100vh;
display: flex;
justify-content: center;
align-items: center;
padding: 30px;
}
.theme-toggle {
position: fixed;
top: 25px;
right: 25px;
background: var(--color-icon-btn-bg);
}
.container {
width: 450px;
max-width: 100%;
text-align: center;
}
.logo img {
width: 180px;
margin-bottom: 20px;
}
h2 {
color:var(--color-accent-text);
margin-bottom: 10px;
font-size: 30px;
}
.subtitle {
color:var(--color-accent-text);
margin-bottom: 30px;
}
form {
background: var(--color-card-bg);
padding: 35px;
border-radius: 12px;
box-shadow: 0 6px 15px var(--shadow-color);
text-align: left;
}
label {
display: block;
margin-bottom: 8px;
color: var(--color-accent-text);
font-weight: bold;
}
input[type="text"],
input[type="password"],
input[type="email"] {
width: 100%;
padding: 12px;
border: 1px solid var(--color-placeholder-border);
border-radius: 10px;
outline: none;
margin-bottom: 18px;
font-size: 15px;
background: var(--color-card-bg);
color: var(--color-card-text);
}
.options {
display: flex;
justify-content: space-between;
align-items: center;
margin-bottom: 25px;
}
.remember {
display: flex;
align-items: center;
gap: 8px;
}
.remember label {
color: var(--color-card-text);
font-weight: normal;
}
.options a {
color: var(--color-accent-text);
text-decoration: none;
font-weight: bold;
}
.options a:hover {
text-decoration: underline;
}
button {
width: 100%;
padding: 13px;
border: none;
border-radius: 10px;
background: var(--color-app-bg);
color: white;
font-size: 17px;
font-weight: bold;
cursor: pointer;
transition: .3s;
}
button:hover {
background: var(--color-sidebar-item-bg);
}
button:disabled {
opacity: .6;
cursor: not-allowed;
}
.signup {
margin-top: 25px;
color:var(--color-accent-text);
}
.signup a {
color: var(--color-accent-text);
text-decoration: none;
font-weight: bold;
}
.signup a:hover {
text-decoration: underline;
}
@media(max-width:600px) {
.container {
width: 100%;
}
form {
padding: 25px;
}
.options {
flex-direction: column;
align-items: flex-start;
gap: 15px;
}
}
</style>
</head>
<body>
<button id="theme-toggle" class="theme-toggle" aria-label="Toggle dark mode">🌙</button>
<div class="container">
<div class="logo">
<img src="MU.jpeg" alt="CampusFind Logo">
</div>
<h2>CampusFind Login</h2>
<p class="subtitle">
Sign in to access your CampusFind account.
</p>
<form action="api/login.php" method="post">
<label for="email">Email</label>
<input type="text" id="email" name="email" placeholder="Enter Your Email" required>
<label for="password">Password</label>
<input type="password" id="password" name="password" placeholder="Enter Your Password" required>
<div class="options">
<div class="remember">
<input type="checkbox" id="remember">
<label for="remember">Remember Me</label>
</div>
<a href="Forget_Password.php">Forgot Password?</a>
</div>
<button type="submit" id="submitBtn">Login</button>
</form>
<div class="signup">
<p>
Don't have an account?
<a href="signup.php">Sign Up</a>
</p>
</div>
</div>
<script src="theme.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
const form = document.querySelector("form");
const email = document.getElementById("email");
const password = document.getElementById("password");
const remember = document.getElementById("remember");
const submitBtn = document.getElementById("submitBtn");

email.addEventListener("focus", function () { email.style.boxShadow = "0 0 8px var(--color-primary)"; });
email.addEventListener("blur", function () { email.style.boxShadow = "none"; });
password.addEventListener("focus", function () { password.style.boxShadow = "0 0 8px var(--color-primary)"; });
password.addEventListener("blur", function () { password.style.boxShadow = "none"; });

form.addEventListener("submit", async function (event) {
event.preventDefault();

const emailValue = email.value.trim();
const passwordValue = password.value.trim();
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

if (emailValue === "") { alert("Please enter your email."); email.focus(); return; }
if (!emailPattern.test(emailValue)) { alert("Please enter a valid email address."); email.focus(); return; }
if (passwordValue === "") { alert("Please enter your password."); password.focus(); return; }
if (passwordValue.length < 6) { alert("Password must contain at least 6 characters."); password.focus(); return; }

submitBtn.disabled = true;
submitBtn.textContent = "Signing in...";

try {
const res = await fetch("api/login.php", {
method: "POST",
headers: { "Content-Type": "application/x-www-form-urlencoded" },
body: new URLSearchParams({
email: emailValue,
password: passwordValue,
remember: remember.checked ? "true" : "false"
})
});
const data = await res.json();

if (!data.success) {
alert(data.message || "Login failed.");
submitBtn.disabled = false;
submitBtn.textContent = "Login";
return;
}

alert("Login Successful!");
window.location.href = "Home_Feed.php";
} catch (err) {
alert("Something went wrong. Please try again.");
submitBtn.disabled = false;
submitBtn.textContent = "Login";
}
});

email.addEventListener("keydown", function (event) {
if (event.key === "Enter") { event.preventDefault(); password.focus(); }
});
password.addEventListener("keydown", function (event) {
if (event.key === "Enter") { event.preventDefault(); form.requestSubmit(); }
});
});
</script>
</body>
</html>
