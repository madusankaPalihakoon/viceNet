<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/modified.css">
    <!-- Local Bootstrap Icons CSS -->
    <link rel="stylesheet" href="../assets/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../assets/Components/jquery-3.2.1.slim.min.js"></script>
    <script src="../assets/Components/popper.min.js"></script>
    <script src="../assets/Components/bootstrap.min.js"></script>
    <style>
        #loadingContainer{
            width: 100vw;
            display: grid;
            text-align: center;
            place-content: center;
            place-items: center;
        }
        #loadingSpinner{
            margin-top: 20vh;
            width: 5rem;
            height: 5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../assets/icon/logo/vicenet-small-logo.png" alt="Logo" width="50" height="24" class="d-inline-block align-text-center">
            ViceNet
        </a>
        <!-- Responsive navigation links -->
        <a id="homeLink" class="nav-link d-none d-md-grid" aria-current="page" href="#">Home</a>
        <a class="d-grid d-md-none" href=""><i class="bi bi-house"></i></a>
        <a id="profileLink" class="nav-link d-none d-md-grid" href="#">Profile</a>
        <a class="d-grid d-md-none" href=""><i class="bi bi-people"></i></a>
        <a id="friendsLink" class="nav-link d-none d-md-grid" href="#">Friends</a>
        <a class="d-grid d-md-none" href=""><i class="bi bi-person"></i></a>
        <a id="settingLink" class="nav-link d-none d-md-grid" href="#">Setting</a>
        <a class="d-grid d-md-none" href=""><i class="bi bi-gear"></i></a>
        <!-- Responsive search form -->
        <form class="d-none d-md-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
        </div>
    </nav>
    <!-- add navigation link -->
    <script src="../assets/js/nav.js"></script>

    <!-- loading -->
    <div class="container" id="loadingContainer">
        
    </div>
    <!-- loading -->

    <div class="container text-center" data-bs-theme="light" id="mainContainer">
    <div class="row">
        <div class="col d-none d-md-block">
        <!-- This column will be hidden on small screens -->
        1 of 3
        </div>

        <div class="col-12 col-md-10 border border-secondary-subtle" id="profile_container">
            
        </div>

        <div class="col d-none d-md-block">
        <!-- This column will be hidden on small screens -->
        3 of 3
        </div>
    </div>
    </div>

<script type="module"  src="../includes/profileRender.js"></script>
<!-- Bootstrap JS and dependencies -->
<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
