<?php
require_once __DIR__."/../config/SessionConfig.php";
require_once __DIR__."/../functions/loginErrorChecker.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>viceNet - Login to the superhero of social networking</title>
    <link rel="stylesheet" href="../assets//css/style.css">
    <script src="../assets/js_action/index_page_action.js"></script>
</head>
<body>
    <!-- login container -->

<div class="login-container" id="login-container">
        <div class="login-form-main-container">
            <div class="signup-form-container">
                <div class="signup-head">
                    <h3>Login your account</h3>
                </div>
                <div class="signup-form-div">
                    <form action="../controller/loginAction" method="post" class="login_form">
                        <div class="input-txt-field">
                            <label for="name">username or E-mail</label>
                            <input type="text" name="usernameOrPassword" placeholder="Enter your username or E-mail">
                            <label for="password">Enter your password</label>
                            <input type="password" name="pword" placeholder="Enter your password">
                            <div class="signup-btn-div">
                                <button type="submit" class="btn-login">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- error display -->
                <?php displayLoginErrorMessage(); ?>
            </div>
        </div>

        <div class="login-logo-container">
            <div class="signup-logo-div">
                <img src="icon/logo-large.png" alt="viceNet logo">
            </div>
            <div class="signup-logo-hero">
                <h3 class="signup-logo-hero-txt">Welcome To The Superhero of Social Networking</h3>
            </div>
            <div class="login-link-btn-div">
                <div class="login-link-text">
                    You don't have account yet?
                </div>
                <div class="login-link-btn">
                    <button onclick="gotoSignup()">Signup Here</button>
                </div>
                <script>
                    function gotoSignup() {window.location.href = "signup.php";}
                </script>
            </div>
        </div>
    </div>
</body>
</html>