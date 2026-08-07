<?php require_once __DIR__ . '/includes/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account</title>
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
flex-direction: column;
justify-content: center;
align-items: center;
padding: 30px;
}
h1 {
color: var(--color-app-bg);
margin-bottom: 10px;
text-align: center;
}
body>div:first-of-type p {
color: var(--color-app-bg);
margin-bottom: 25px;
text-align: center;
}
form {
width: 700px;
max-width: 100%;
background: var(--color-card-bg);
color: var(--color-card-text);
padding: 40px;
border-radius: 12px;
box-shadow: 0 6px 15px var(--shadow-color);
}
form div {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 15px;
margin-bottom: 18px;
align-items: center;
}
label {
font-weight: bold;
color: var(--color-accent-text);
}
input,
select {
width: 100%;
padding: 12px;
border: 1px solid var(--color-placeholder-border);
border-radius: 10px;
font-size: 15px;
outline: none;
background: var(--color-card-bg);
color: var(--color-card-text);
}
input:focus,
select:focus {
border-color: var(--color-accent-text);
box-shadow: 0 0 5px var(--color-primary);
}
button {
width: 700px;
max-width: 100%;
margin-top: 20px;
padding: 13px;
background: var(--color-app-bg);
color: white;
border: none;
border-radius: 10px;
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
body>div:last-of-type {
margin-top: 20px;
}
body>div:last-of-type p {
color: var(--color-app-bg);
}
a {
color: var(--color-app-bg);
font-weight: bold;
text-decoration: none;
}
a:hover {
text-decoration: underline;
}
@media(max-width:768px) {
form {
padding: 25px;
}
form div {
grid-template-columns: 1fr;
}
button {
width: 100%;
}
}
</style>
</head>
<body>
<h1>Create Your Account</h1>
<div>
<p>Fill in the details below to get started</p>
</div>
<form action="api/register.php" method="post" id="signupForm">
<div>
<label for="fullName">Full Name</label>
<input type="text" id="fullName" name="full_name" placeholder="Enter your full name" required>
<label for="age">Age</label>
<input type="text" id="age" name="age" placeholder="Age" required>
</div>
<div>
<label for="Gender">Gender</label>
<select id="Gender" name="gender">
<option value="Female">Female</option>
<option value="Male">Male</option>
<option value="Others">Others</option>
</select>
<label for="phone">Phone Number</label>
<input type="tel" id="phone" name="phone" placeholder="Enter Your Number" required>
</div>
<div>
<label for="dob">Date of Birth</label>
<input type="date" id="dob" name="dob" placeholder="DOB" required>
<label for="email">Email</label>
<input type="text" id="email" name="email" placeholder="Enter Your Email Address">
</div>
<div>
<label for="Password">Password</label>
<input type="password" id="Password" name="password" placeholder="Create a strong password" required>
</div>
<button type="submit" id="submitBtn">Create Account</button>
</form>
<div>
<p>Already have an account? <a href="Login.php">Login</a></p>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
const form = document.getElementById("signupForm");
const fullName = document.getElementById("fullName");
const age = document.getElementById("age");
const gender = document.getElementById("Gender");
const phone = document.getElementById("phone");
const dob = document.getElementById("dob");
const email = document.getElementById("email");
const password = document.getElementById("Password");
const submitBtn = document.getElementById("submitBtn");
const fields = document.querySelectorAll("input, select");

fields.forEach(function (field) {
field.addEventListener("focus", function () { field.style.boxShadow = "0 0 8px var(--color-primary)"; });
field.addEventListener("blur", function () { field.style.boxShadow = "none"; });
});

form.addEventListener("submit", async function (event) {
event.preventDefault();

const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const phonePattern = /^[0-9]{11}$/;

if (fullName.value.trim() === "") { alert("Please enter your full name."); fullName.focus(); return; }
if (age.value.trim() === "" || isNaN(age.value) || age.value < 16) { alert("Please enter a valid age."); age.focus(); return; }
if (phone.value.trim() === "") { alert("Please enter your phone number."); phone.focus(); return; }
if (!phonePattern.test(phone.value)) { alert("Phone number must contain exactly 11 digits."); phone.focus(); return; }
if (dob.value === "") { alert("Please select your date of birth."); dob.focus(); return; }
if (email.value.trim() === "") { alert("Please enter your email."); email.focus(); return; }
if (!emailPattern.test(email.value)) { alert("Please enter a valid email address."); email.focus(); return; }
if (password.value.length < 6) { alert("Password must contain at least 6 characters."); password.focus(); return; }

submitBtn.disabled = true;
submitBtn.textContent = "Creating account...";

try {
const res = await fetch("api/register.php", {
method: "POST",
headers: { "Content-Type": "application/x-www-form-urlencoded" },
body: new URLSearchParams({
full_name: fullName.value.trim(),
age: age.value.trim(),
gender: gender.value,
phone: phone.value.trim(),
dob: dob.value,
email: email.value.trim(),
password: password.value
})
});
const data = await res.json();

if (!data.success) {
alert(data.message || "Registration failed.");
submitBtn.disabled = false;
submitBtn.textContent = "Create Account";
return;
}

alert("Registration Successful!");
window.location.href = "Login.php";
} catch (err) {
alert("Something went wrong. Please try again.");
submitBtn.disabled = false;
submitBtn.textContent = "Create Account";
}
});

fields.forEach(function (input) {
input.addEventListener("keydown", function (event) {
if (event.key === "Enter") { event.preventDefault(); form.requestSubmit(); }
});
});
});
</script>
</body>
</html>
