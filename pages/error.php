<?php

require_once __DIR__."/../config/SessionConfig.php";

if (isset($_SESSION["email_block_error"]) || !empty($_SESSION["email_block_error"]) ) {
    echo $_SESSION["email_block_error"];
}