document.addEventListener("DOMContentLoaded", function () {

    const FRIENDS_KEY = "campusfind_friends";
    const REQUESTS_KEY = "campusfind_requests";
    const SENT_KEY = "campusfind_sent_requests";

    /* Sample Users */

    const users = [
        {
            name: "Rahim Ahmed",
            id: "221-15-1234",
            email: "rahim@diu.edu.bd",
            department: "CSE"
        },
        {
            name: "Karim Hasan",
            id: "221-15-5678",
            email: "karim@diu.edu.bd",
            department: "BBA"
        },
        {
            name: "Nusrat Jahan",
            id: "222-15-1111",
            email: "nusrat@diu.edu.bd",
            department: "SWE"
        },
        {
            name: "Sabbir Hossain",
            id: "223-15-2222",
            email: "sabbir@diu.edu.bd",
            department: "CSE"
        },
        {
            name: "Farzana Akter",
            id: "224-15-3333",
            email: "farzana@diu.edu.bd",
            department: "LAW"
        },
        {
            name: "Mehedi Hasan",
            id: "225-15-4444",
            email: "mehedi@diu.edu.bd",
            department: "DS"
        },
        {
            name: "Anika Islam",
            id: "226-15-5555",
            email: "anika@diu.edu.bd",
            department: "ENG"
        }
    ];

    /* Inputs */

    const topSearch = document.getElementById("topSearch");
    const topSearchBtn = document.getElementById("topSearchBtn");

    const emailInput = document.getElementById("emailInput");
    const departmentInput = document.getElementById("departmentInput");
    const idInput = document.getElementById("idInput");
    const nameInput = document.getElementById("nameInput");

    const resultBox = document.getElementById("results");

    /* Local Storage */

    let sentRequests = JSON.parse(localStorage.getItem(SENT_KEY)) || [];

    function loadFriends() {
        return JSON.parse(localStorage.getItem(FRIENDS_KEY)) || [];
    }

    function loadRequests() {
        return JSON.parse(localStorage.getItem(REQUESTS_KEY)) || [];
    }

    function saveRequests(list) {
        localStorage.setItem(REQUESTS_KEY, JSON.stringify(list));
    }

    /* Search */

    function searchUsers() {

        const email = emailInput.value.trim().toLowerCase();
        const dept = departmentInput.value;
        const id = idInput.value.trim().toLowerCase();
        const name = nameInput.value.trim().toLowerCase();
        const keyword = topSearch.value.trim().toLowerCase();

        const friends = loadFriends();

        resultBox.innerHTML = "<h3>Search Results</h3>";

        const matches = users.filter(function (user) {

            const alreadyFriend = friends.some(friend => friend.id === user.id);

            return (

                !alreadyFriend &&

                (email === "" || user.email.toLowerCase().includes(email)) &&

                (dept === "" || user.department === dept) &&

                (id === "" || user.id.toLowerCase().includes(id)) &&

                (name === "" || user.name.toLowerCase().includes(name)) &&

                (

                    keyword === "" ||

                    user.name.toLowerCase().includes(keyword) ||

                    user.id.toLowerCase().includes(keyword) ||

                    user.email.toLowerCase().includes(keyword) ||

                    user.department.toLowerCase().includes(keyword)

                )

            );

        });

        if (matches.length === 0) {

            resultBox.innerHTML += "<p>No users found.</p>";
            return;

        }

        matches.forEach(function (user) {

            const card = document.createElement("div");
            card.className = "user-card";

            const alreadySent = sentRequests.includes(user.id);

            card.innerHTML = `
                <h4>${user.name}</h4>

                <p><strong>ID:</strong> ${user.id}</p>

                <p><strong>Email:</strong> ${user.email}</p>

                <p><strong>Department:</strong> ${user.department}</p>

                <button ${alreadySent ? "disabled" : ""}>
                    ${alreadySent ? "Request Sent" : "Add Friend"}
                </button>
            `;

            const btn = card.querySelector("button");

            btn.addEventListener("click", function () {

                let requests = loadRequests();

                if (requests.some(r => r.id === user.id)) {

                    alert("Friend request already sent.");
                    return;

                }

                requests.push({
                    name: user.name,
                    department: user.department,
                    id: user.id
                });

                saveRequests(requests);

                sentRequests.push(user.id);

                localStorage.setItem(SENT_KEY, JSON.stringify(sentRequests));

                btn.textContent = "Request Sent";
                btn.disabled = true;

                alert("Friend request sent to " + user.name);

            });

            resultBox.appendChild(card);

        });

    }

    /* Events */

    topSearchBtn.addEventListener("click", searchUsers);

    topSearch.addEventListener("keyup", function (e) {

        if (e.key === "Enter") {
            searchUsers();
        }

    });

    emailInput.addEventListener("input", searchUsers);
    departmentInput.addEventListener("change", searchUsers);
    idInput.addEventListener("input", searchUsers);
    nameInput.addEventListener("input", searchUsers);

    window.addEventListener("focus", searchUsers);

    /* Initial Load */

    searchUsers();

});