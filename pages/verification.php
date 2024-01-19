<?php
require_once __DIR__.'/../vendor/autoload.php';

use Configuration\SessionManager;
SessionManager::start();

if (empty(SessionManager::get('verificationEmail'))) {
  header("location: signup.php");
}
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
<body class="verification-body">
<div class="container verification-container">
  <div class="row">
      <div class="col-md-12 verification-contain">
        <img class="gmail-logo" src="../assets/icon/gmail/gmail.png" alt="gmail logo">
        <h1 class="verification-h1">Thank you for choosing viceNet.</h1>
        <h4>To complete the verification process, please enter verification code and click "verify"</h4>
        <p>Please note that the OTP is valid for the next 5 minutes. Ensure you complete the verification process within this time frame.</p>
      </div>
  </div>
  <div class="row">
    <div class="col-md-12 verification-form">
      <form id="verificationForm" action="../controller/verificationController" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="verificationCode" class="form-label">Enter Your Verification Code</label>
          <input type="text" class="form-control" id="verificationCode" name="verificationCode" pattern="[0-9]*" inputmode="numeric" maxlength="4">
        </div>
        <div class="mb-3">
          <input type="hidden" name="formType" value="checkCode">
          <button type="submit" id="verificationBtn" class="btn btn-primary verification-btn">Verify</button>
        </div>
      </form>
    </div>
  </div>
  <!-- message-container -->
  <div class="row justify-content-md-center" id="message-container" style="color: darkred;"></div>

  <div class="row">
    <div class="col-md-12 resend-form">
      <h5>Did't received verification code ?</h5>
      <form id="resendForm" action="../controller/verificationController" method="post" enctype="multipart/form-data">
        <button type="submit" id="resendBtn" class="btn btn-link resend-btn">Resend Code</button>
      </form>
    </div>
  </div>
</div>

    <!-- jQuery function -->
    <script type="module"  src="../Handler/verification.js"></script>
    <script type="module"  src="../Handler/resend.js"></script>

    <!-- Include jQuery -->
    <script src="../vendor/components/jquery/jquery.min.js"></script>
    <script src="../vendor/components/jquery/jquery.js"></script>
    <!-- Include Bootstrap JavaScript -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>