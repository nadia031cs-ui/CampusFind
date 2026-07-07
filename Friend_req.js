const FRIENDS_KEY = "campusfind_friends";
const REQUESTS_KEY = "campusfind_requests";

/* Sample friend requests (added only on first run) */
const seedRequests = [
    {
        name: "Emily Watson",
        department: "Computer Science",
        id: "CSE22025"
    },
    {
        name: "Hasib Rahman",
        department: "Electrical Engineering",
        id: "EEE22018"
    },
    {
        name: "Fatema Akter",
        department: "Business Administration",
        id: "BBA22031"
    },
    {
        name: "Nafis Islam",
        department: "Civil Engineering",
        id: "CE22040"
    },
    {
        name: "Anika Sultana",
        department: "English",
        id: "ENG22011"
    }
];

/* ---------- Load Requests ---------- */

function loadRequests() {

    const stored = localStorage.getItem(REQUESTS_KEY);

    if (stored) {
        try {
            return JSON.parse(stored);
        } catch (e) {
            return [];
        }
    }

    localStorage.setItem(REQUESTS_KEY, JSON.stringify(seedRequests));
    return seedRequests;
}

function saveRequests(list) {
    localStorage.setItem(REQUESTS_KEY, JSON.stringify(list));
}

/* ---------- Load Friends ---------- */

function loadFriends() {

    const stored = localStorage.getItem(FRIENDS_KEY);

    if (stored) {
        try {
            return JSON.parse(stored);
        } catch (e) {
            return [];
        }
    }

    return [];
}

function saveFriends(list) {
    localStorage.setItem(FRIENDS_KEY, JSON.stringify(list));
}

/* ---------- Variables ---------- */

let requests = loadRequests();

const table = document.getElementById("requestTable");
const searchInput = document.getElementById("searchInput");
const searchBtn = document.getElementById("searchBtn");

/* ---------- Display Requests ---------- */

function displayRequests(list) {

    table.innerHTML = "";

    if (list.length === 0) {

        table.innerHTML = `
            <tr>
                <td colspan="4" class="empty-message">
                    No friend requests available.
                </td>
            </tr>
        `;

        return;
    }

    list.forEach(function (person) {

        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${person.name}</td>
            <td>${person.department}</td>
            <td>${person.id}</td>

            <td>

                <button class="action-btn accept-btn">
                    Accept
                </button>

                <button class="action-btn delete-btn">
                    Delete
                </button>

            </td>
        `;

        /* Accept Request */

        row.querySelector(".accept-btn").addEventListener("click", function () {

            let friends = loadFriends();

            const alreadyFriend = friends.some(friend => friend.id === person.id);

            if (!alreadyFriend) {

                friends.push(person);

                saveFriends(friends);

            }

            requests = requests.filter(req => req.id !== person.id);

            saveRequests(requests);

            searchRequests();

            alert(person.name + " is now your friend!");

        });

        /* Delete Request */

        row.querySelector(".delete-btn").addEventListener("click", function () {

            if (confirm("Delete friend request from " + person.name + "?")) {

                requests = requests.filter(req => req.id !== person.id);

                saveRequests(requests);

                searchRequests();

            }

        });

        table.appendChild(row);

    });

}

/* ---------- Search ---------- */

function searchRequests() {

    const keyword = searchInput.value.toLowerCase();

    const filtered = requests.filter(function (person) {

        return (

            person.name.toLowerCase().includes(keyword) ||

            person.department.toLowerCase().includes(keyword) ||

            person.id.toLowerCase().includes(keyword)

        );

    });

    displayRequests(filtered);

}

/* ---------- Events ---------- */

searchInput.addEventListener("keyup", searchRequests);

searchBtn.addEventListener("click", searchRequests);

window.addEventListener("focus", function () {

    requests = loadRequests();

    searchRequests();

});

window.addEventListener("storage", function (e) {

    if (e.key === REQUESTS_KEY) {

        requests = loadRequests();

        searchRequests();

    }

});

/* ---------- Initial Load ---------- */

displayRequests(requests);