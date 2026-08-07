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
<title>Home Feed</title>
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
color: white;
font-size: 28px;
font-weight: bold;
margin-bottom: 20px;
text-align: center;
}
.theme-toggle {
margin: 0 auto 20px auto;
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
position: relative;
}
nav a:hover {
background: white;
color: black;
}
.nav-badge {
position: absolute;
right: 12px;
top: 50%;
transform: translateY(-50%);
background: var(--color-danger);
color: white;
font-size: 12px;
font-weight: bold;
padding: 2px 8px;
border-radius: 999px;
display: none;
}
#logout button {
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
#logout button:hover {
background: var(--color-danger-hover);
}
main {
flex: 1;
padding: 35px;
}
#search {
width: 100%;
padding: 13px;
border: none;
border-radius: 10px;
outline: none;
font-size: 16px;
margin-bottom: 25px;
background: var(--color-card-bg);
color: var(--color-card-text);
}
h2 {
color: white;
margin-bottom: 8px;
}
.subtitle {
color: var(--color-muted-text);
margin-bottom: 25px;
}
.filter-toggle-btn {
position: fixed;
top: 25px;
right: 25px;
width: 50px;
height: 50px;
background: var(--color-sidebar-item-bg);
color: white;
border-radius: 10px;
display: flex;
justify-content: center;
align-items: center;
font-size: 24px;
cursor: pointer;
box-shadow: 0 6px 15px var(--shadow-color);
z-index: 1001;
transition: .3s;
}
.filter-toggle-btn:hover {
transform: scale(1.08);
}
#right {
position: fixed;
top: 0;
right: -320px;
width: 300px;
height: 100vh;
overflow-y: auto;
padding: 80px 20px 20px;
z-index: 1000;
box-shadow: -5px 0 20px var(--shadow-color);
transition: .4s ease;
}
#right.active {
right: 0;
}
.close-filter-btn {
position: absolute;
top: 18px;
right: 20px;
color: white;
font-size: 34px;
cursor: pointer;
}
#right h3 {
color: white;
margin-bottom: 15px;
}
#right select {
width: 100%;
padding: 12px;
border: none;
border-radius: 10px;
margin-bottom: 15px;
outline: none;
background: var(--color-card-bg);
color: var(--color-card-text);
}
#filter {
width: 100%;
padding: 12px;
border: none;
border-radius: 10px;
background: var(--color-card-bg);
color:var(--color-accent-text);
font-weight: bold;
cursor: pointer;
transition: .3s;
}
#filter:hover {
background: var(--color-muted-text);
}
#tips,
#loc {
margin-top: 35px;
color: white;
}
#tips h3,
#loc h3 {
margin-bottom: 12px;
}
#tips p {
margin-bottom: 10px;
line-height: 1.6;
color: var(--color-muted-text);
}
ul {
padding-left: 18px;
}
li {
margin-bottom: 10px;
color: var(--color-muted-text);
}
.postCard {
background: var(--color-card-bg);
color: var(--color-card-text);
border-radius: 12px;
margin-bottom: 25px;
overflow: hidden;
box-shadow: 0 5px 15px var(--shadow-color);
}
.postHeader {
display: flex;
align-items: center;
gap: 15px;
padding: 15px;
}
.profilePic {
width: 50px;
height: 50px;
border-radius: 50%;
object-fit: cover;
}
.postImage {
width: 100%;
max-height: 450px;
object-fit: cover;
cursor: pointer;
transition: .3s;
}
.postImage:hover {
opacity: .9;
}
.postBody {
padding: 20px;
color: var(--color-card-text);
}
.postFooter {
display: flex;
justify-content: space-around;
padding: 15px;
border-top: 1px solid var(--color-border);
}
.postFooter button {
border: none;
background: none;
cursor: pointer;
font-size: 16px;
font-weight: bold;
color: var(--color-card-text);
}
#imageModal {
display: none;
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background: rgba(0, 0, 0, .85);
z-index: 2000;
justify-content: center;
align-items: center;
}
#imageModal.active {
display: flex;
}
#imageModal img {
max-width: 90%;
max-height: 85vh;
border-radius: 10px;
box-shadow: 0 10px 30px rgba(0, 0, 0, .5);
}
.close-modal-btn {
position: absolute;
top: 25px;
right: 35px;
color: white;
font-size: 40px;
font-weight: bold;
cursor: pointer;
line-height: 1;
}
.close-modal-btn:hover {
color: var(--color-danger);
}
@media(max-width:600px) {
body {
flex-direction: column;
}
aside:not(#right) {
width: 100%;
}
main {
padding: 20px;
}
#right {
width: 260px;
right: -280px;
}
#imageModal img {
max-width: 95%;
}
}
</style>
</head>
<body>
<aside>
<div>
<div class="logo">
CampusFind
</div>
<button id="theme-toggle" class="theme-toggle" aria-label="Toggle dark mode">🌙</button>
<nav>
<a href="Home_Feed.php">Home Feed</a>
<a href="Create_Post.php">Post an Item</a>
<a href="University_Map.php">University Map</a>
<a href="Messages.php">Messages</a>
<a href="friends.php">Friends</a>
<a href="profiledashboard.php">Profile</a>
<a href="Settings.php">Settings</a>
<a href="Notifications.php">Notifications <span class="nav-badge" id="navBadge">0</span></a>
</nav>
</div>
<div id="logout">
<button>Logout</button>
</div>
</aside>
<main>
<input id="search" type="text" placeholder="Search Anything">
<h2>Home Feed</h2>
<p class="subtitle">
See the latest lost and found items posted by our university community.
</p>
<div id="feedContainer"><p style="color:white;">Loading...</p></div>
</main>
<div class="filter-toggle-btn" onclick="toggleFilterBar()">▤</div>
<aside id="right">
<span class="close-filter-btn" onclick="toggleFilterBar()">×</span>
<div>
<h3>Filter Posts</h3>
<select>
<option>All Categories</option>
</select>
<select>
<option>All Locations</option>
</select>
<select>
<option>All Status</option>
</select>
<button id="filter">Clear Filters</button>
</div>
<div id="tips">
<h3>Quick Tips</h3>
<p>If you found an item, make sure to post it so the owner can find it easily.</p>
<p>Provide accurate location details to help others locate items quickly.</p>
</div>
<div id="loc">
<h3>Popular Locations</h3>
<ul>
<li>Library</li>
<li>Floor 1</li>
<li>Canteen</li>
<li>Floor 4</li>
<li>Prayer Spot</li>
</ul>
</div>
</aside>
<div id="imageModal" onclick="closeImageModal(event)">
<span class="close-modal-btn" onclick="closeImageModal(event)">&times;</span>
<img id="imageModalImg" src="" alt="Full size post image">
</div>
<script src="theme.js"></script>
<script>
const CURRENT_USER_ID = <?php echo (int) $me['id']; ?>;

function toggleFilterBar() {
document.getElementById("right").classList.toggle("active");
}

function openImageModal(src) {
const modal = document.getElementById("imageModal");
const modalImg = document.getElementById("imageModalImg");
modalImg.src = src;
modal.classList.add("active");
}
function closeImageModal(event) {
if (event.target.id === "imageModal" || event.target.classList.contains("close-modal-btn")) {
document.getElementById("imageModal").classList.remove("active");
document.getElementById("imageModalImg").src = "";
}
}

async function updateNavBadge() {
try {
const res = await fetch("api/notifications_list.php");
const data = await res.json();
const navBadge = document.getElementById("navBadge");
if (navBadge && data.success) {
navBadge.textContent = data.unreadCount;
navBadge.style.display = data.unreadCount === 0 ? "none" : "inline-block";
}
} catch (e) { /* non-fatal */ }
}

async function likePost(id) {
const res = await fetch("api/items_like.php", {
method: "POST",
headers: { "Content-Type": "application/x-www-form-urlencoded" },
body: new URLSearchParams({ id })
});
const data = await res.json();
if (!data.success) {
alert(data.message || "Couldn't like this post.");
return;
}
loadFeed();
}

async function deletePost(id) {
if (!confirm("Are you sure you want to delete this post?")) return;
const res = await fetch("api/items_delete.php", {
method: "POST",
headers: { "Content-Type": "application/x-www-form-urlencoded" },
body: new URLSearchParams({ id })
});
const data = await res.json();
if (!data.success) {
alert(data.message || "Couldn't delete this post.");
return;
}
alert("Post deleted successfully.");
loadFeed();
}

function renderPosts(posts) {
const feedContainer = document.getElementById("feedContainer");
if (posts.length === 0) {
feedContainer.innerHTML = `<h3 style="text-align:center;color:white;">No posts yet.</h3>`;
return;
}
feedContainer.innerHTML = posts.map(function (post) {
return `
<div class="postCard">
<div class="postHeader">
<img class="profilePic" src="${post.profileImage || 'default-profile.png'}">
<div>
<h3>${post.username}</h3>
<small>${post.createdAt}</small>
</div>
</div>
${post.image ? `<img class="postImage" src="${post.image}" onclick="openImageModal('${post.image}')">` : ""}
<div class="postBody">
<h4>${post.itemType}</h4>
<p>${post.description}</p>
<p><b>Location:</b> ${post.location}</p>
<p><b>Date:</b> ${post.date}</p>
</div>
<div class="postFooter">
<button onclick="likePost(${post.id})" ${post.likedByMe ? 'disabled' : ''}>
👍 Like (${post.likes})
</button>
<button>💬 Comment</button>
<button>📤 Share</button>
${post.ownerId === CURRENT_USER_ID ? `<button onclick="deletePost(${post.id})">🗑 Delete</button>` : ""}
</div>
</div>
`;
}).join("");
}

async function loadFeed(keyword) {
const feedContainer = document.getElementById("feedContainer");
feedContainer.innerHTML = `<p style="color:white;">Loading...</p>`;
const url = "api/items_list.php" + (keyword ? "?q=" + encodeURIComponent(keyword) : "");
try {
const res = await fetch(url);
const data = await res.json();
if (!data.success) {
feedContainer.innerHTML = `<p style="color:white;">Couldn't load posts.</p>`;
return;
}
renderPosts(data.items);
} catch (e) {
feedContainer.innerHTML = `<p style="color:white;">Couldn't load posts.</p>`;
}
}

document.addEventListener("DOMContentLoaded", function () {
console.log("Welcome to CampusFind!");
updateNavBadge();
loadFeed();

const logoutBtn = document.querySelector("#logout button");
logoutBtn.addEventListener("click", function () {
if (confirm("Are you sure you want to logout?")) {
window.location.href = "api/logout.php";
}
});

const navLinks = document.querySelectorAll("nav a");
navLinks.forEach(function (link) {
link.addEventListener("mouseenter", function () { link.style.transform = "translateX(5px)"; });
link.addEventListener("mouseleave", function () { link.style.transform = "translateX(0)"; });
});

const search = document.getElementById("search");
search.addEventListener("keydown", function (event) {
if (event.key === "Enter") {
event.preventDefault();
const keyword = search.value.trim();
if (keyword === "") { loadFeed(); return; }
loadFeed(keyword);
}
});
search.addEventListener("focus", function () { search.style.boxShadow = "0 0 8px white"; });
search.addEventListener("blur", function () { search.style.boxShadow = "none"; });

const filterBtn = document.getElementById("filter");
filterBtn.addEventListener("click", function () {
document.querySelectorAll("#right select").forEach(function (s) { s.selectedIndex = 0; });
alert("Filters have been cleared.");
});

document.querySelectorAll("#loc li").forEach(function (loc) {
loc.style.cursor = "pointer";
loc.addEventListener("click", function () {
search.value = loc.textContent;
loadFeed(loc.textContent);
});
});
});
</script>
</body>
</html>
