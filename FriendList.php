<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: rgb(60, 45, 131);
        }

        aside {
            width: 250px;
            background: rgb(14, 3, 59);
            padding: 20px;
            text-align: center;
        }

        aside img {
            margin-bottom: 30px;
        }

        aside nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px;
            margin: 8px 0;
            border-radius: 8px;
            transition: .3s;
        }

        aside nav a:hover {
            background: rgb(92, 72, 190);
        }

        main {
            flex: 1;
            padding: 40px;
            background: #f4f4ff;
        }

        h2 {
            color: rgb(60, 45, 131);
            margin-bottom: 10px;
        }

        p {
            color: #555;
            margin-bottom: 25px;
        }

        input {
            width: 350px;
            padding: 12px;
            border: 2px solid rgb(60, 45, 131);
            border-radius: 8px;
            outline: none;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
        }

        thead {
            background: rgb(60, 45, 131);
            color: white;
        }

        th,
        td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f1f1ff;
        }

        button {
            border: none;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            margin: 2px;
        }

        .message-btn {
            background: #28a745;
        }

        .remove-btn {
            background: #dc3545;
        }

        .message-btn:hover {
            background: #218838;
        }

        .remove-btn:hover {
            background: #c82333;
        }
    </style>
</head>

<body>
    <aside>
        <img src="cmpLOGO.jpeg" alt="" width="180">

        <nav>

            <nav>

                <a href="Home_Feed.html">Home Feed</a>
                <br>
                <a href="Post_an_Item.html">Post an Item</a>
                <br>
                <a href="University_Map.html">University Map</a>
                <br>
                <a href="Messages.html">Messages</a>
                <br>
                <a href="Profile.html">Profile</a>
                <br>
                <a href="Friends.html">Friends</a>
                <br>
                <a href="Settings.html">Settings</a>


            </nav>

        </nav>
    </aside>
    <main>
        <h2>Friends</h2>

        <p>
            Connect with friends and stay updated together.
        </p>
        <div>
            <form action="">
                <input type="text" placeholder="Search friends...">
            </form>
        </div>
        <div>
            <h3>Your Friends</h3>
            <table>
                <thead>
                    <tr>
                        <th>
                            Friend
                        </th>
                        <th>
                            Department
                        </th>
                        <th>
                            ID
                        </th>
                        <th>
                            Connected On
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </main>

    <script>

        const STORAGE_KEY = "campusfind_friends";

        let friends = JSON.parse(localStorage.getItem(STORAGE_KEY) || "[]");


        const table = document.getElementById("friendsTable");
        const search = document.getElementById("searchFriend");

        function loadFriends(list) {

            table.innerHTML = "";

            if (list.length === 0) {
                table.innerHTML = `
    <tr>
        <td colspan="5">No friends found.</td>
    </tr>`;
                return;
            }

            list.forEach(friend => {

                table.innerHTML += `
    <tr>
        <td>${friend.name}</td>
        <td>${friend.department}</td>
        <td>${friend.id}</td>
        <td>${friend.connected}</td>
        <td>
            <button class="message-btn" onclick="messageFriend('${friend.name}')">Message</button>

            <button class="remove-btn" onclick="removeFriend('${friend.id}')">Remove</button>
        </td>
    </tr>
    `;

            });

        }

        function messageFriend(name) {
            alert("Opening chat with " + name);
            window.location.href = "Messages.html";
        }
        function removeFriend(id) {

            const index = friends.findIndex(friend => friend.id === id);

            if (index !== -1) {

                if (confirm("Remove this friend?")) {

                    friends.splice(index, 1);

                    localStorage.setItem(STORAGE_KEY, JSON.stringify(friends));
                    window.addEventListener("focus", function () {

                        friends = JSON.parse(localStorage.getItem(STORAGE_KEY) || "[]");

                        loadFriends(friends);

                    });

                    loadFriends(friends);

                }



            }

            

        }

        search.addEventListener("keyup", function () {

            const value = this.value.toLowerCase();

            const filtered = friends.filter(friend =>

                friend.name.toLowerCase().includes(value) ||

                friend.department.toLowerCase().includes(value) ||

                friend.id.toLowerCase().includes(value)

            );

            loadFriends(filtered);

        });

        loadFriends(friends);
    </script>



</body>

</html>