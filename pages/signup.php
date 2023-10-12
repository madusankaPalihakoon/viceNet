<?php require __DIR__."/../functions/signupErrorChecker.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon/favicon.ico">
    <title>viceNet - SignUp to the superhero of social networking</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="signup-logo-container">
            <div class="signup-logo-div">
                <img src="../assets/images/logo/logo-large.png" alt="viceNet logo">
            </div>
            <div class="signup-logo-hero">
                <h3 class="signup-logo-hero-txt">Join the ViceNet revolution and experience social media like never before.</h3>
            </div>
            <div class="login-link-btn-div">
                <div class="login-link-text">
                    Already have an account?
                </div>
                <div class="login-link-btn">
                    <button id="goto-login" onclick="gotoLogin()">Login Here</button>
                </div>
                <script>
                    function gotoLogin() {window.location.href = "login";}
                </script>
            </div>
        </div>

        <div class="signup-form-main-container">
            <div class="signup-form-container">
                <div class="signup-head">
                    <h3>Create your account</h3>
                </div>
                <div class="signup-form-div">
                    <form action="../controller/signupAction" method="post" class="signup_form" id="signupForm">
                        <div class="input-txt-field">
                            <label for="name">Full name</label>

                            <input type="text" name="name" placeholder="Enter your name" id="name">

                            <label for="email">Enter your E-mail</label>

                            <input type="email" name="user_email" placeholder="Enter your E-mail" id="user_email">

                            <label for="uname">Enter your username</label>

                            <input type="text" name="username" placeholder="Enter your username" id="username">

                            <div class="input-txt-field select-menu">
                                <label for="country">Select your country</label>

                                <select name="country" id="countrySelect"></select>

                            </div>
                            <!-- add country option -->
                            <script src="../assets/js/country.js"></script>
                            <label for="pnumber">Enter your phone number</label>

                            <input type="text" name="pnumber" placeholder="Enter your phone number" id="phoneNumberInput">

                            <!--disable when enter the country-->
                            <script src="../assets/js_action/p_number_validation.js"></script>
                            <!-- add phone code using country -->
                            <script src="../assets/js/p_number_code.js"></script>
                            <div class="input-txt-field select-menu">
                                <label for="dob">Enter your birthday</label>

                                <input type="date" name="birthday" placeholder="Enter your birthday" id="birthday">

                            </div>
                            <div class="input-txt-field select-gender-menu">
                                <label for="">Select your gender</label>
                                <ul>
                                    <label for="male">Male</label> <input type="radio" name="gender" value="male" id="gender">
                                    <label for="female">Female</label> <input type="radio" name="gender" value="female" >
                                    <label for="male">Other</label> <input type="radio" name="gender" value="other">
                                </ul>
                            </div>
                            <label for="pword">Enter your password</label>

                            <input type="password" name="pword" placeholder="Enter your password" id="password">

                            <!-- password error -->
                            <div class="pword-create-error"></div>
                            <label for="confpword">Confirm password</label>

                            <input type="password" name="confirmpword" placeholder="Re-type your password" id="confirmpword">

                            <!-- confirm password error -->
                            <div class="confirm-pwd-error"></div>
                            <div class="input-txt-field select-term-menu">
                                <ul>
                                    <input type="checkbox" name="agree" value="agree" id="term"> i accept the <a href="#">Term of use</a> & <a href="#">privacy policy</a>
                                </ul>
                            </div>
                            <div class="signup-btn-div">
                                <button type="submit" class="btn-signup">Sign Up</button>
                            </div>
                        </div>
                        <!-- signup errors -->
                        <?php displaySignupError(); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>