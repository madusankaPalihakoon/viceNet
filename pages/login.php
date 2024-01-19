<?php
require_once __DIR__.'/../vendor/autoload.php';
use Configuration\SessionManager;
SessionManager::start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>viceNet - Login to the superhero of social networking</title>
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/modified.css">
    <!-- Include jQuery -->
    <script src="../vendor/components/jquery/jquery.min.js"></script>
    <script src="../vendor/components/jquery/jquery.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 login-div">
                <div class="jumbotron logo-container">
                    <img class="logo-large" src="../assets/icon/logo/logo-large.png" alt="logo" srcset="">
                    <p class="lead login-hero">Join the ViceNet revolution and experience social media like never before.</p>
                    <hr class="my-4">
                    <div class="d-grid justify-content-center">
                        <p class="align-items-center">Already have an account?</p>
                        <p class="lead">
                            <a class="btn btn-outline-primary btn-lg w-100 login-link-btn" href="../pages/signup" role="button">Sign Up Here</a>
                        </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-md-7 login-div">
            <div class="container mt-5 login-form-div">
                <h2 class="text-primary signup-text">Login</h2>
                <h6 class="text-secondary">Welcome To The Superhero of Social Networking.</h6>
                <form id="loginForm" action="../controller/loginController" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="inputUsername" class="form-label">Username :</label>
                        <input type="text" class="form-control" id="inputUsername" name="username">
                    </div>
                    <div class="mb-3">
                        <label for="inputPassword" class="form-label">Password :</label>
                        <input type="password" class="form-control" id="inputPassword" name="password">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn login-btn w-100" id="loginBtn">Login</button>
                    </div>
                </form>
            </div>
            <div id="message-container" style="color: red;  font-weight: 400;"></div>
        </div>
        
    </div>
<!-- jQuery form submit -->
<script type="module"  src="../Handler/login.js"></script>

<!-- Include jQuery -->
<script src="../vendor/components/jquery/jquery.min.js"></script>
<script src="../vendor/components/jquery/jquery.js"></script>
<!-- Include Bootstrap JavaScript -->
<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>