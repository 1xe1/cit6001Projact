<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="Sidebar.css">

    <!----===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

    <title>Dashboard Sidebar Menu ac</title>
</head>

<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="#" alt="">
                </span>

                <div class="text logo-text">
                    <span class="name">Anun</span>
                    <span class="profession">Web developer</span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="text" placeholder="Search...">
                </li>

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="/cit6001Projact/Sidebar.php">
                            <i class='bx bx-home-alt icon'></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="/cit6001Projact/Componects/Customer/index.php">
                            <i class='bx bx-user icon'></i>
                            <span class="text nav-text">Customers</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/cit6001Projact/Componects/Employee/index.php">
                            <i class='bx bxs-user-detail icon'></i>
                            <span class="text nav-text">Employee</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/cit6001Projact/Componects/Products/index.php">
                            <i class='bx bx-package icon'></i>
                            <span class="text nav-text">Stock</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/cit6001Projact/Componects/Project/index.php">
                            <i class='bx bx-shield-quarter icon'></i>
                            <span class="text nav-text">Project</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/cit6001Projact/Componects/Project_close/index.php">
                            <i class='bx bxs-dashboard icon'></i>
                            <span class="text nav-text">Project close</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/cit6001Projact/Componects/SystemUser/index.php">
                            <i class='bx bxs-cog icon'></i>
                            <span class="text nav-text">System Users</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="#">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
            </div>
        </div>

    </nav>

    <section class="home">
        <div class="text">Dashboard Sidebar</div>
    </section>

    <script>
    const body = document.querySelector('body'),
      sidebar = body.querySelector('nav'),
      toggle = body.querySelector(".toggle"),
      searchBtn = body.querySelector(".search-box"),
      modeSwitch = body.querySelector(".toggle-switch"),
      modeText = body.querySelector(".mode-text");


toggle.addEventListener("click" , () =>{
    sidebar.classList.toggle("close");
})

searchBtn.addEventListener("click" , () =>{
    sidebar.classList.remove("close");
})

modeSwitch.addEventListener("click" , () =>{
    body.classList.toggle("dark");

    if(body.classList.contains("dark")){
        modeText.innerText = "Light mode";
    }else{
        modeText.innerText = "Dark mode";

    }
});
    </script>

</body>

</html>