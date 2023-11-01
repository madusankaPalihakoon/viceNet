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
    /* profile_setup */
    .logo_container {
        display: inline-flex;
        justify-content: space-around;
        place-items: center;
        width: 90vw;
        height: 20vh;
        background-color: #EE9322;
        border-radius: 0.4rem;
    }
    .logo_img{
        display: grid;
        place-items: center;
        width: 40%;
        height: auto;
    }
    .logo_img img{
        width: 100%;
        height: auto;
    }
    .hero_logo{
        place-items: center;
        display: grid;
        width: 40%;
        height: 100%;
    }
    .about_prof_set{
        display: grid;
        justify-content: left;
        background-color: #807d7d;
        border-radius: 0.5rem;
        height: 100%;
        width: 90vw;
    }
    .headline_prof_set{
        display: grid;
        width: 100vw;
        justify-content: center;
    }
    .cont_prof_set .prof_set_head{
        display: grid;
        width: 100vw;
        margin-left: 4vw;
    }
    .cont_prof_set .prof_set_sub_head {
        display: grid;
        width: 100vw;
        margin-left: 6vh;
    }
    .cont_prof_set .profile_update_box{
        margin-left: 7vh;
    }
    .update_btn{
        width: 30vw;
        padding: 7px;
        border-radius: 0.4rem;
        outline: none;
        border: none;
        cursor: pointer;
    }
    .update_text{
        padding: 5px;
        width: 70vw;
        border: none;
        outline: none;
        border-radius: 0.4rem;
    }
    .prifile_set_form_action{
        margin-top: 20px;
        display: flex;
        justify-content: center;
        gap: 1vw;
        margin-bottom: 20px;
        width: 90vw;
    }
    .set_form_act_btn{
        width: 40vw;
        padding: 5px;
        border-radius: 0.4rem;
        outline: none;
        border: none;
        cursor: pointer;
        background-color: #379237;
        color: #000;
        font-weight: 700;
    }
    .set_form_act_btn:hover{
        background-color: #54B435;
    }
    /* profile_setup */
    </style>
</head>
<body>
    <div class="logo_container">
        <div class="logo_img">
            <img src="../assets/images/logo/logo-large.png" alt="viceNet logo">
        </div>
        <div class="hero_logo">
            Welcome to viceNet, Please config your profile
        </div>
    </div>
    <div class="about_prof_set">
        <div class="headline_prof_set">
            <h1>Setup your profile</h1>
        </div>
        <form action="../controller/profileSetupAction" method="POST" enctype="multipart/form-data">
            <div class="cont_prof_set">
                <h3 class="prof_set_head">Add Profile Picture</h3>
                <div class="profile_pic_upl_container">
                    <input class="profile_update_box update_btn" class="prof_set_upload" type="file" name="profile_picture">
                </div>
                <h3 class="prof_set_head">Add Cover Picture</h3>
                <div class="cover_pic_upl_container">
                    <input class="profile_update_box update_btn" class="prof_set_upload" type="file" name="cover_picture">
                </div>
                <h3 class="prof_set_head">Add your Bio</h3>
                    <h4 class="prof_set_sub_head">Home Town</h4>
                    <input class="profile_update_box update_text" type="text" name="home_town">
                    <h4 class="prof_set_sub_head">Contact Information</h4>
                    <input class="profile_update_box update_text" type="text" name="contact_info">
                    <h4 class="prof_set_sub_head">Education</h4>
                    <input class="profile_update_box update_text" type="text" name="education">
                    <h4 class="prof_set_sub_head">Employment</h4>
                    <input class="profile_update_box update_text" type="text" name="employment">
                    <h4 class="prof_set_sub_head">Relationship Status</h4>
                    <input class="profile_update_box update_text" type="text" name="relationship_status">
                    <h4 class="prof_set_sub_head">Hobbies</h4>
                    <input class="profile_update_box update_text" type="text" name="hobbies">
            </div>
            <div class="prifile_set_form_action">
                <button class="set_form_act_btn" type="submit">Upload Profile</button>
                <input class="set_form_act_btn" type="submit" value="Skip for Now">
            </div>
        </form>
    </div> 
</body>
</html>