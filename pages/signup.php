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
<body class="signup-body">
    <div class="container-fluid">
        <div class="row">
        <div class="col-md-5 login-div signup-div">
                <div class="jumbotron logo-container">
                    <img class="logo-large" src="../assets/icon/logo/logo-large.png" alt="logo" srcset="">
                    <p class="lead login-hero">Welcome To The Superhero of Social Networking.</p>
                    <hr class="my-4">
                    <div class="d-grid justify-content-center">
                        <p class="align-items-center">Already have an account?</p>
                        <p class="lead">
                            <a class="btn btn-outline-primary btn-lg w-100 login-link-btn" href="../pages/login" role="button">Login Here</a>
                        </p>
                    </div>
                    
                </div>
            </div>

            <div class="col-md-7 form-div signup-form-div">
            <!-- signup form -->
                <h2 class="text-primary signup-text">Sign Up</h2>
                <h6 class="text-secondary">Join the ViceNet revolution and experience social media like never before.</h6>
                <form id="signupForm" action="../controller/signupController" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="inputName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="inputName" name="name">
                </div>
                <div class="mb-3">
                    <label for="inputEmail" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="inputEmail" name="email" >
                </div>
                <div class="mb-3">
                    <label for="inputUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="inputUsername" name="username" >
                </div>
                <div class="mb-3">
                    <label for="selectCountry" class="form-label">Country</label>
                    <select class="form-control" id="selectCountry" name="country" onchange="updatePhoneCode()" > </select>
                </div>
                <div class="mb-3">
                    <label for="inputPhone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="inputPhone" name="phone" >
                </div>
                <div class="mb-3">
                    <label for="inputBirthday" class="form-label">Birthday</label>
                    <input type="date" class="form-control" id="inputBirthday" name="birthday" >
                </div>
                <div class="mb-3">
                    <label class="form-check-label">Gender</label>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" >
                    <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" >
                    <label class="form-check-label" for="female">Female</label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="other" value="Other" >
                    <label class="form-check-label" for="other">Other</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password must be at least 8 characters long" >
                </div>
                <div class="mb-3">
                    <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="inputConfirmPassword" name="confirmPassword" >
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkTerm" name="termCheckbox">
                        <label class="form-check-label" for="checkTerm"> I agree <a href="">Term</a>  & <a href="">Condition</a> </label>
                    </div>
                </div>
                    <button type="submit" class="btn signup-btn w-100" id="signupBtn">Sign Up</button>
                </form>
                <!-- message display -->
                <div id="message-container" style="text-align: center; color: red;"></div>
                <!-- message display -->
            </div>
        </div>
    </div>
<!-- jQuery form submit -->
<script type="module"  src="../Handler/signup.js"></script>
<script src="../assets/js/country.js"></script>
<!-- Include jQuery -->
<script src="../vendor/components/jquery/jquery.min.js"></script>
<script src="../vendor/components/jquery/jquery.js"></script>
<!-- Include Bootstrap JavaScript -->
<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>