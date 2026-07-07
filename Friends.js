 let friends = [
            {
                name: "John Doe",
                department: "CSE",
                id: "22101111",
                connected: "12 Jun 2026"
            },
            {
                name: "Sarah Ahmed",
                department: "EEE",
                id: "22102222",
                connected: "18 May 2026"
            },
            {
                name: "Rakib Hasan",
                department: "BBA",
                id: "22103333",
                connected: "25 Apr 2026"
            },
            {
                name: "Nusrat Jahan",
                department: "CSE",
                id: "22104444",
                connected: "30 Mar 2026"
            },
            {
                name: "Tanvir Islam",
                department: "LAW",
                id: "22105555",
                connected: "15 Feb 2026"
            }
        ];


        const searchInput = document.getElementById("searchFriend");
        const tableBody = document.getElementById("friendsTable");


        function displayFriends(friendList) {

            tableBody.innerHTML = "";

            if (friendList.length === 0) {
                tableBody.innerHTML = `
            <tr>
                <td colspan="5">No friends found.</td>
            </tr>
        `;
                return;
            }

            friendList.forEach((friend) => {

                const row = document.createElement("tr");

                row.innerHTML = `
            <td>${friend.name}</td>
            <td>${friend.department}</td>
            <td>${friend.id}</td>
            <td>${friend.connected}</td>
            <td>
                <button class="message-btn">Message</button>
                <button class="remove-btn">Remove</button>
            </td>
        `;


                row.querySelector(".message-btn").addEventListener("click", () => {
                    alert("Opening chat with " + friend.name);
                    window.location.href = "Messages.html";
                });


                row.querySelector(".remove-btn").addEventListener("click", () => {

                    if (confirm("Remove " + friend.name + " from your friends?")) {

                        friends = friends.filter(f => f.id !== friend.id);

                        displayFriends(friends);
                    }

                });

                tableBody.appendChild(row);

            });

        }


        function searchFriends() {

            const searchValue = searchInput.value.toLowerCase();

            const filteredFriends = friends.filter(friend =>

                friend.name.toLowerCase().includes(searchValue) ||
                friend.department.toLowerCase().includes(searchValue) ||
                friend.id.toLowerCase().includes(searchValue)

            );

            displayFriends(filteredFriends);

        }


        searchInput.addEventListener("keyup", searchFriends);


        displayFriends(friends);