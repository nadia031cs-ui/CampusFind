# CampusFind Backend — Batches 1–2 (Auth + Posts/Notifications)

## Batch 1 — Auth
- `sql/schema.sql` — `users` table (matches every field signup.html already collects, plus student_id/department/semester/batch/photo)
- `config/db.php` — MySQL connection (PDO)
- `includes/auth.php` — session start, `requireLogin()`, `currentUser()`, `h()` output-escaping helper
- `api/register.php`, `api/login.php`, `api/logout.php` — real DB-backed auth (bcrypt via `password_hash`, PDO prepared statements, server-side validation mirroring the client-side checks)
- `Login.php`, `signup.php` — same design/CSS as the originals, but now `fetch()` the API instead of reading/writing `localStorage`

## Batch 2 — Lost & Found posts + Notifications
- `sql/schema.sql` — added `items`, `item_likes` (one like per user — the old version let you click Like infinitely), `notifications`
- `includes/notifications.php` — `createNotification()`, `unreadNotificationCount()`
- `api/items_create.php` — real multipart file upload; server-side resizes to 800px / 70% JPEG quality with GD (same numbers as the old client-side canvas compression, just done properly on the server instead of in the browser)
- `api/items_list.php` — feed data, with `?q=` search across description/location/type
- `api/items_like.php`, `api/items_delete.php` — like (owner notified) and delete (only the post's owner, image file cleaned up too)
- `api/notifications_list.php`, `api/notifications_read.php`
- `Home_Feed.php` — real feed pulled from `items`, search works server-side now, delete button only shows for your own posts (old version showed it on every post)
- `Create_Post.php` — real upload via `FormData`, no more localStorage quota crashes on big images

## Install (XAMPP)
1. Copy the whole `campusfind/` folder into `htdocs/`.
2. Start Apache + MySQL in the XAMPP control panel.
3. Open `http://localhost/phpmyadmin`, create nothing manually — just run `sql/schema.sql` in the SQL tab (it creates the `campusfind` database itself).
4. Visit `http://localhost/campusfind/signup.php`, register, then log in at `Login.php`.

## What's NOT done yet
- **Static/reference pages** (`aboutus.html`, `intro.html`, `University_Map.html`, `Table.html`, `XL.html`, `reports.html`, the `Floors/*.html` maps) are unchanged .html — links to them from the wired pages work, but they have no server logic and don't need any (they were never dynamic).
- **`intro.html`** references a `LandingPage.css` that isn't in the project — pre-existing gap from before Batch 1, not something this batch touched. Add that file or point `intro.html` at `theme.css` only.
- **`default-profile.png`** is referenced (new signups, empty profile photos) but isn't included in the zip — add an actual image at the project root with that filename.
- **True "forgot password" (logged-out, via email)** is still not implemented — `Forget_Password.php` is the *logged-in* "Change Password" flow, which is what the page's own markup/JS actually was. A real logged-out reset needs SMTP or a token-based flow; say the word if you want that added.
- **FriendList.html / Friend_req.js / Friends.js / FindFriends.js / "extra codes.html"** were dead/duplicate files (not linked from any nav) — removed rather than wired.

## Batch 3 — Friends, Messages, Notifications page, Profile/Settings, Change Password
- `sql/schema.sql` — added `friend_requests`, `friends` (canonical pair table), `messages`
- `includes/friends.php` — `areFriends()`, `addFriendship()`, `removeFriendship()`
- **Friends system**: `api/friends_search.php` (real user search, replaces the old 7-fake-user list), `api/friends_request_send.php` (auto-accepts if the other side already requested you), `api/friends_requests_list.php`, `api/friends_request_accept.php`, `api/friends_request_decline.php`, `api/friends_list.php`, `api/friends_remove.php` — wired into `FindFriends.php`, `Friend_req.php`, `friends.php`
- **Messages**: `api/messages_send.php` (text + optional image, GD-resized like post images), `api/messages_list.php` (real conversations = your real friends, with last-message preview + unread count), `api/messages_thread.php` (marks incoming messages read on open) — wired into `Messages.php`. You can only message people you're friends with.
- **Notifications page**: `api/notifications_delete.php` (single delete / clear all) added alongside the existing list/read APIs — `Notifications.php` now pulls real notifications instead of the shared-localStorage simulation
- **Profile/Settings**: `api/profile_update.php` (shared by both pages — only updates fields actually submitted, so Settings.php posting a subset never wipes fields Edit Profile.php owns; also handles profile photo upload, center-cropped square) — wired into `profiledashboard.php`, `Settings.php`, `Edit Profile.php`
- **Change Password**: `api/profile_password.php` (verifies current password, bcrypt-hashes the new one) — wired into `Forget_Password.php`
- All `.html` pages that got a `.php` counterpart were removed (`Login.html`, `signup.html`, `Home_Feed.html`, `Create_Post.html`, `Notifications.html`, `Messages.html`, `FindFriends.html`, `Friend_req.html`, `friends.html`, `Settings.html`, `profiledashboard.html`, `Edit Profile.html`, `Forget_Password.html`) and every internal link across the whole site was updated to point at the `.php` version.

## Install (XAMPP) — unchanged from Batch 1
1. Copy the whole `campusfind/` folder into `htdocs/`.
2. Start Apache + MySQL in the XAMPP control panel.
3. Open `http://localhost/phpmyadmin`, run `sql/schema.sql` in the SQL tab (creates the DB and every table, safe to re-run — everything is `CREATE TABLE IF NOT EXISTS`).
4. Visit `http://localhost/campusfind/signup.php`, register, then log in at `Login.php`.

