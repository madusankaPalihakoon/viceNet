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
    <!-- Include jQuery -->
    <script src="../vendor/components/jquery/jquery.min.js"></script>
    <script src="../vendor/components/jquery/jquery.js"></script>
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
        <a id="galleryLink" class="nav-link d-none d-md-grid" href="#">Gallery</a>
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

    <div class="container text-center" data-bs-theme="dark" id="gallery-main">
        <div class="col-md-12">
            <!-- loading -->
            <div class="container" id="loadingContainer"> </div>
            <!-- loading -->

            <!-- img container -->
            <div class="row" id="img-gallery" style="display: none;">
                <div class="col" id="img-viewer">
                    <!-- images display dynamically in here -->
                </div>
            </div>
        </div>
    </div>
    <script src="../jQuery/gallery.js"></script>
<!-- Bootstrap JS and dependencies -->
<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
