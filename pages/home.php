<?php
require_once __DIR__."/../config/SessionConfig.php";
$sessionId = $_SESSION['session_id'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="../assets/bootstrap/main.css">
    <link rel="stylesheet" href="../assets/bootstrap/style.css">
    <link rel="stylesheet" href="../assets/bootstrap/custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../assets/images/logo/logo-large.png" alt="Logo" width="50" height="24" class="d-inline-block align-text-center">
            ViceNet
        </a>
        <!-- Responsive navigation links -->
        <a class="nav-link d-none d-md-grid" aria-current="page" href="#">Home</a>
        <a class="d-grid d-md-none" href=""><i class="bi bi-house"></i></a>
        <a class="nav-link d-none d-md-grid" href="#">Friends</a>
        <a class="d-grid d-md-none" href=""><i class="bi bi-people"></i></a>
        <a class="nav-link d-none d-md-grid" href="#">Account</a>
        <a class="d-grid d-md-none" href=""><i class="bi bi-person"></i></a>
        <a class="nav-link d-none d-md-grid" href="#">Setting</a>
        <a class="d-grid d-md-none" href=""><i class="bi bi-gear"></i></a>
        <!-- Responsive search form -->
        <form class="d-none d-md-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
        </div>
    </nav>  

    <div class="container text-center" data-bs-theme="dark">
    <div class="row">
        <div class="col d-none d-md-block">
        <!-- This column will be hidden on small screens -->
        1 of 3
        </div>

        <div class="col-12 col-md-6 border border-secondary-subtle" id="main_container">
            <!-- story -->
            <div class="overflow-x-auto d-flex flex-row mb-3 story-container">
                <div class="card story-item">
                <img src="https://images.freeimages.com/images/large-previews/c0d/gerbera-series-1-1486599.jpg" class="card-img-top" alt="...">
                <button class="story-btn"><i class="bi bi-cloud-plus"> Add Story</i></button>
                <div class="card-body">
                    <h6 class="card-title">Card title</h6>
                </div>
                </div>

                <div class="card story-item">
                <img src="https://images.freeimages.com/images/large-previews/c0d/gerbera-series-1-1486599.jpg" class="card-img-top" alt="...">
                <button class="story-btn"><i class="bi bi-cloud-plus"> Add Story</i></button>
                <div class="card-body">
                    <h6 class="card-title">Card title</h6>
                </div>
                </div>
            </div>
            <!-- story -->

            <!--! create post -->
            <div class="card create-post-container">
                <div class="row">
                <div class="col small-profile">
                    <img src="https://images.freeimages.com/images/large-previews/6e3/flower-1370341.jpg" alt="">
                    What's on your mind
                </div>
                </div>
                <!-- ! create post model button -->
                <div class="col create-post-btn-container">
                <button type="button" class="btn btn-primary w-75" data-bs-toggle="modal" data-bs-target="#postModel" data-bs-whatever="@mdo"><i class="bi bi-card-image"></i> Post Image</button>
                </div>
                <!-- ! create post model button -->
                
                <!--! Modal for create post -->
                <div class="modal fade" id="postModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Create Post</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="Close_Btn"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" id="createPost">
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Write a Caption :</label>
                                <input type="text" class="form-control" id="post_caption" name="caption"></input>
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Select a Picture :</label>
                                <input type="file" class="form-control" id="post_photo" name="photo"></input>
                            </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="create_post_btn">Submit Post</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--! Modal for create post -->
            </div>
            <!--! create post -->
            
            <!-- Loading div -->
            <div class="text-center text-primary" style="margin-top: 10px;" id="loading_screen">
                <div class="spinner-border" style="width: 4rem; height: 4rem;" role="status"></div>
            </div>
            <!-- Loading div -->

            <!-- Comment Modal -->        
            
            <!-- Comment Modal -->

            
            <!--! post container -->
            
            <!--! post container -->

        </div>

        <div class="col d-none d-md-block">
        <!-- This column will be hidden on small screens -->
        3 of 3
        </div>
    </div>
    </div>

    <!--! like button action -->
    <script src="../ajax/createPostAsync.js"></script>
    <script src="../ajax/createCommentAsync.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <!-- Add your custom scripts here -->
</body>
</html>
