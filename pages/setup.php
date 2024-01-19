<?php
require_once __DIR__.'/../vendor/autoload.php';
use Configuration\SessionManager;
SessionManager::start();

// print_r($_SESSION);
?>
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
</head>
<body class="setup-body">
<div class="container-fluid pro-setup-container" style="border: 1px solid red;">
    <div class="col">
        <div class="pro-setup-head">
            <img class="pro-setup-logo" src="../assets/icon/logo/vicenet-logo.png" alt="" srcset="">
            <h2 class="text-primary signup-text">ViceNet Profile Setup</h2>
            <h6 class="text-secondary">Welcome To The Superhero of Social Networking.</h6>
        </div>

        <div class="pro-setup-form">
            <form id="setupForm" action="../controller/setupController" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="selectProfilePic" class="form-label">Select Profile Photo : <b style="color: darkred;">require</b></label>
                    <input type="file" class="form-control" id="selectProfilePic" name="ProfileImg">
                </div>
                <div class="mb-3">
                    <label for="selectCoverPic" class="form-label">Select Cover Photo : <b style="color: darkred;">require</b></label>
                    <input type="file" class="form-control" id="selectCoverPic" name="CoverImg">
                </div>
                <h5>Bio Information</h5>
                <div class="mb-3">
                    <label for="homeTown" class="form-label">Enter Your Home Town : <b style="color: darkred;">require</b></label>
                    <input type="text" class="form-control" id="homeTown" name="Home">
                </div>
                <div class="mb-3">
                    <label for="selectBirthday" class="form-label">Enter Date of Birth : <b style="color: darkred;">require</b></label>
                    <input type="date" class="form-control" id="selectBirthday" name="Birthday">
                </div>
                <div class="mb-3">
                    <label for="selectRelationship" class="form-label">Select Your Relationship status :</label>
                    <select name="RelationshipStatus" id="selectRelationship" class="form-control">
                        <option value="">Select Your Relationship status</option>
                        <option value="Single">Single</option>
                        <option value="InRelationship">In a Relationship</option>
                        <option value="Married">Married</option>
                        <option value="Engaged">Engaged</option>
                        <option value="Divorced">Divorced</option>
                    </select>
                </div>
                <h5>Contact Information</h5>
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Enter your Mobile Number :</label>
                    <input type="text" class="form-control" id="phoneNumber" name="Mobile">
                </div>
                <div class="mb-3">
                    <button type="submit" id="setupBtn" class="btn login-btn w-100" id="setupBtn">Setup Profile</button>
                </div>
                <div class="mb-3" id="message-container" style="text-align: center; color: red;"></div>
                <div class="mb-3 skip-btn">
                    <button type="button" class="btn setup-skip-btn btn-warning"><a class="skip-link" href="home">Skip For Now</a></button>
                    <h5>When skipping setup now, this page will reappear during your next login. Alternatively, you can edit your profile through settings.</h5>
                </div>
            </form>
            
        </div>
    </div>
</div>
<!-- jQuery -->
<script type="module"  src="../Handler/setup.js"></script>
<!-- Bootstrap JS and dependencies -->
<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
