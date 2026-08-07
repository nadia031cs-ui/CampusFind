<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Post</title>
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
justify-content: center;
align-items: center;
min-height: 100vh;
padding: 25px;
}
.container {
width: 700px;
max-width: 100%;
}
h2 {
color: white;
margin-bottom: 15px;
}
form {
background: var(--color-card-bg);
color: var(--color-card-text);
padding: 40px;
border-radius: 12px;
box-shadow: 0 6px 15px var(--shadow-color);
}
label {
display: block;
margin: 12px 0 6px;
font-weight: bold;
color: var(--color-accent-text);
}
textarea,
input,
select {
width: 100%;
padding: 12px;
border: 1px solid var(--color-placeholder-border);
border-radius: 10px;
outline: none;
margin-bottom: 15px;
font-size: 15px;
background: var(--color-card-bg);
color: var(--color-card-text);
}
textarea {
height: 120px;
resize: vertical;
}
input:focus,
textarea:focus,
select:focus {
border-color: var(--color-app-bg);
box-shadow: 0 0 6px var(--color-primary);
}
#buttons {
display: flex;
justify-content: space-between;
margin-top: 20px;
}
button {
width: 48%;
padding: 12px;
border: none;
border-radius: 8px;
cursor: pointer;
font-size: 16px;
font-weight: bold;
}
#post {
background: var(--color-app-bg);
color: white;
}
#post:hover {
background: var(--color-sidebar-item-bg);
}
#post:disabled {
opacity: .6;
cursor: not-allowed;
}
#Cancel {
background: var(--color-danger);
color: white;
}
#Cancel:hover {
background: var(--color-danger-hover);
}
@media(max-width:600px) {
form {
padding: 25px;
}
#buttons {
flex-direction: column;
gap: 12px;
}
button {
width: 100%;
}
}
</style>
</head>
<body>
<div class="container">
<h2>Create a Post</h2>
<form id="postForm" enctype="multipart/form-data">
<label for="description">Description</label>
<textarea id="description" name="description" placeholder="What did you lose or find?" required></textarea>
<label for="image">Upload Image</label>
<input type="file" id="image" name="image" accept="image/*">
<label for="location">Location</label>
<input type="text" id="location" name="location" placeholder="Enter Location" required>
<label for="date">Date</label>
<input type="date" id="date" name="date" required>
<label for="itemType">Item Type</label>
<select id="itemType" name="itemType" required>
<option value="">Select Item Type</option>
<option>Electronics</option>
<option>Documents</option>
<option>Accessories</option>
<option>Books and Study Materials</option>
<option>Personal Items</option>
<option>Others</option>
</select>
<div id="buttons">
<button id="post" type="submit">Post</button>
<button id="Cancel" type="button">Cancel</button>
</div>
</form>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
const form = document.getElementById("postForm");
const description = document.getElementById("description");
const image = document.getElementById("image");
const location = document.getElementById("location");
const date = document.getElementById("date");
const itemType = document.getElementById("itemType");
const cancelBtn = document.getElementById("Cancel");
const postBtn = document.getElementById("post");

const today = new Date();
const todayStr = today.getFullYear() + "-" +
String(today.getMonth() + 1).padStart(2, "0") + "-" +
String(today.getDate()).padStart(2, "0");
date.max = todayStr;

const fields = document.querySelectorAll("textarea, input, select");
fields.forEach(function (field) {
field.addEventListener("focus", function () { field.style.boxShadow = "0 0 8px var(--color-primary)"; });
field.addEventListener("blur", function () { field.style.boxShadow = "none"; });
});

image.addEventListener("change", function () {
if (this.files.length > 0) {
const file = this.files[0];
const sizeMB = (file.size / (1024 * 1024)).toFixed(1);
alert("Selected Image: " + file.name + " (" + sizeMB + " MB — will be compressed on the server before posting)");
}
});

cancelBtn.addEventListener("click", function () {
if (confirm("Are you sure you want to cancel?")) {
window.location.href = "Home_Feed.php";
}
});

form.addEventListener("submit", async function (e) {
e.preventDefault();

if (description.value.trim() === "") { alert("Please enter a description."); description.focus(); return; }
if (location.value.trim() === "") { alert("Please enter the location."); location.focus(); return; }
if (date.value === "") { alert("Please select a date."); date.focus(); return; }
if (date.value > todayStr) { alert("The date can't be in the future. Please select today or an earlier date."); date.focus(); return; }
if (itemType.value === "") { alert("Please select an item type."); itemType.focus(); return; }

postBtn.disabled = true;
postBtn.textContent = "Posting...";

try {
const res = await fetch("api/items_create.php", {
method: "POST",
body: new FormData(form)   // real multipart upload, server compresses via GD
});
const data = await res.json();

if (!data.success) {
alert(data.message || "Couldn't save your post.");
postBtn.disabled = false;
postBtn.textContent = "Post";
return;
}

alert("Post Created Successfully!");
form.reset();
window.location.href = "Home_Feed.php";
} catch (err) {
console.error("Failed to save post:", err);
alert("Something went wrong. Please try again.");
postBtn.disabled = false;
postBtn.textContent = "Post";
}
});
});
</script>
</body>
</html>
