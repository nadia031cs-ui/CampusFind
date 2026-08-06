<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Second Floor</title>

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
            background-color: var(--color-app-bg);
                        background-image: url("./Floors/uni.jpeg");

            min-height: 100vh;
            padding: 25px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .menu-btn {
            position: fixed;
            top: 25px;
            left: 25px;
            width: 50px;
            height: 50px;
            background: var(--color-icon-btn-bg);
            color: var(--color-app-bg);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            cursor: pointer;
            box-shadow: 0 6px 15px var(--shadow-color);
            z-index: 1001;
            transition: .3s;
        }

        .menu-btn:hover {
            transform: scale(1.08);
        }

        .navbar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 260px;
            height: 100vh;
            background: var(--color-sidebar-bg);
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 80px 20px 20px;
            transition: .4s ease;
            z-index: 1000;
            box-shadow: 5px 0 20px var(--shadow-color);
        }

        .navbar.active {
            left: 0;
        }

        .navbar a {
            text-decoration: none;
            color: white;
            background: var(--color-sidebar-item-bg);
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 18px;
            transition: .3s;
        }

        .navbar a:hover {
            background: white;
            color: black;
        }

        .close-btn {
            position: absolute;
            top: 18px;
            right: 20px;
            color: white;
            font-size: 34px;
            cursor: pointer;
        }

        .top-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .top-bar input {
            width: 100%;
            max-width: 600px;
            padding: 12px 18px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            outline: none;
            background: var(--color-card-bg);
            color: var(--color-card-text);
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo img {
            width: 180px;
        }

        .content {
            display: grid;
            grid-template-columns: 260px 1fr 280px;
            gap: 20px;
        }

        aside,
        main {
            background: var(--color-card-bg);
            color: var(--color-card-text);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 15px var(--shadow-color);
        }

        aside h3,
        main h3 {
            color: var(--color-accent-text);
            margin-bottom: 15px;
        }

        aside h4 {
            margin-top: 20px;
            margin-bottom: 10px;
            color: var(--color-accent-text);
        }

        aside p {
            line-height: 1.6;
            color: var(--color-text-muted);
        }

        ul {
            list-style: none;
        }

        ul a {
            display: block;
            text-decoration: none;
            color: var(--color-list-item-text);
            background: var(--color-list-item-bg);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: .3s;
        }

        ul a:hover {
            background: var(--color-app-bg);
            color: white;
        }

        main {
            text-align: center;
        }

        main img {
            width: 100%;
            height: 500px;
            border: 2px dashed var(--color-placeholder-border);
            border-radius: 10px;
            object-fit: contain;
            margin-top: 20px;
            background: var(--color-placeholder-bg);
        }

        @media(max-width:900px) {
            .content {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width:600px) {
            .top-bar input {
                width: 100%;
            }

            .navbar {
                width: 220px;
                left: -240px;
            }

            .menu-btn {
                width: 45px;
                height: 45px;
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

    <div class="menu-btn" onclick="toggleMenu()">
        ☰
    </div>

    <div class="navbar" id="navbar">

        <span class="close-btn" onclick="toggleMenu()">×</span>

        <a href="Home_Feed.html">Home</a>
        <a href="Create_Post.html">Create Post</a>
        <a href="University_Map.html">Map</a>
        <a href="profiledashboard.html">Profile</a>

    </div>

    <div class="container">

        <div class="top-bar">
            <input type="text" placeholder="Search anything...">
        </div>

        <div class="logo">
            <img src="cmpLOGO.jpeg" alt="">
        </div>

        <div class="content">

            <aside>

                <div>
                    <h3>Floors</h3>

                    <ul>
                        <a href="Ground_Floor.html">Ground Floor</a>
                        <a href="First_Floor.html">First Floor</a>
                        <a href="Second_Floor.html">Second Floor</a>
                        <a href="Third_Floor.html">Third Floor</a>
                        <a href="Fourth_Floor.html">Fourth Floor</a>
                         <a href="XL.html">CSE Society</a>
                    </ul>
                </div>

                <div>
                    <h4>How it works?</h4>
                    <p>Select a floor and click on any room to view or report lost items in that location.</p>
                </div>

            </aside>

            <main>

                <h3>Fourth Floor</h3>

                <img src="/Floors/F4.jpeg" alt="Fourth Floor Map">

            </main>

            <aside>

                <h3>Location Information</h3>

                <h4>About this location</h4>

                <p>
                   <ul>
                    <li>Stairs (Front)</li>
                    <li>Room 401</li>
                    <li>Room 402</li>
                    <li>WASHROOM</li>
                    <li>Room 403</li>
                    <li>Room 404</li>
                    <li>Room 405</li>
                    <li>Room 406</li>
                    <li>Room 407</li>
                    <li>Room 408</li>
                    <li>Room 409</li>
                    <li>Stairs (Back)</li>
                    <li>Balcony</li>

                   </ul>
                </p>

            </aside>

        </div>

    </div>

    <script>
        function toggleMenu() {
            document.getElementById("navbar").classList.toggle("active");
        }
    </script>

</body>

</html>