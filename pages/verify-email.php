<?php 
    require_once __DIR__."/../config/SessionConfig.php";
    require_once __DIR__."/../functions/verifyErrorChecker.php";
?>
<?php 
    if (isset($_SESSION['verification_email']))
    {
        $verification_email = $_SESSION['verification_email'];
    } else {
        header("locatio: signup.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>viceNet - E-mail verification</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background-color: #1e90ff;">
    <div class="verification-container">
        <div class="verification-logo">
            <img src="../assets//images/gmail/gmail_logo.gif" alt="gmail logo">
        </div>
        <div class="verification-txt">
            <ul>Verify your email address</ul>
            <p>
                We've sent a verification code to <br><b>
                <?php echo htmlspecialchars($verification_email); ?> </b><br>
                Enter the code to complete your signup.
            </p>
        </div>
        <div class="verification-form-contanier">
            <form action="../controller/verifyEmailAction" method="post" enctype="multipart/form-data">
                <input type="hidden" name="verification_email" value='<?php echo htmlspecialchars($verification_email); ?>'>
                <input type="hidden" name="form_type" value="verify_code"> <!--form_type field -->
                <input type="text" name="verification_code" id="numeric-input" pattern="\d*" maxlength="4" oninput="this.value = this.value.replace(/\D/g, '')"  />
                <button type="submit">Verify Email</button>
            </form>
        </div>
        <!-- Display error message -->
        <?php displayVerificationMessage(); ?>
        <div class="new-code">
            <label for="this_button">Didn't Receive Email ?</label>
            <form action="../controller/verifyEmailAction" method="post" enctype="multipart/form-data">
                <input type="hidden" name="resend_email" value='<?php echo htmlspecialchars($verification_email); ?>'>
                <input type="hidden" name="form_type" value="resend_code"> <!--form_type field -->
                <button type="submit">Resend Email Verification</button>
            </form>
            <!-- Show resend message -->
            <?php displayResendVerifyMessage(); ?>
            <ul></ul>
        </div>
    </div>
</body>
</html>