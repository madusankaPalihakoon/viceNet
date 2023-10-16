<?php
require_once __DIR__."/../config/SessionConfig.php";
$sessionId = $_SESSION['session_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .parent {
        height: 100%;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: 100px repeat(5, 1fr);
        grid-column-gap: 0px;
        grid-row-gap: 0px;
    }

    .nav { grid-area: 1 / 1 / 2 / 4; display: grid;}
    .navigator{
        display: inline-flex;
        width: 100%;
        height: 45px;
        background-color: red;
    }
    .navigator_tab{
        display: inline-flex;
        place-content: center;
        place-items: center;
        width: 20%;
        font-weight: 600;
    }
    .logo{
        margin: 2px;
    }
    .logo .logo_img{
        width: 100%;
        height: inherit;
    }
    .home,.profile,.friends,.setting{
        height: 100%;
    }
    .home:hover, .profile:hover, .friends:hover, .setting:hover {
        background-color: #CCCCCC;
        color: #333;
        border-radius: 0.5rem;
    }
    .friend_content { grid-area: 2 / 1 / 4 / 2; }
    .request_content { grid-area: 4 / 1 / 7 / 2; }
    .store_content { grid-area: 2 / 3 / 7 / 4; }
    .story_content { grid-area: 2 / 2 / 3 / 3; }
    .upload_content { grid-area: 3 / 2 / 4 / 3; }
    .post_content { grid-area: 4 / 2 / 7 / 3; }
    </style>
</head>
<body>
    <div class="parent">
        <div class="nav">
            <div class="navigator">
                <div class="navigator_tab logo">
                    <img class="logo_img" src="../assets/images/logo/logo-large.png" alt="" srcset="">
                </div>
                <div class="navigator_tab home">Home</div>
                <div class="navigator_tab profile">Profile</div>
                <div class="navigator_tab friends">Friends</div>
                <div class="navigator_tab setting">Setting</div>
                <div class="navigator_tab action"><?php echo htmlspecialchars($sessionId); ?></div>
            </div>
        </div>
        <div class="friend_content"> friend_content </div>
        <div class="request_content"> request_content </div>
        <div class="store_content"> store_content </div>
        <div class="story_content"> story_content </div>
        <div class="upload_content"> upload_content </div>
        <div class="post_content"> post_content </div>
    </div>
</body>
</html>