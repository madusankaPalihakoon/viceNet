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
    #menu {
        background: #00394D;
        color: #FFF;
        height: 50px;
        padding-left: 18px;
        border-radius: 7px;
    }
    #nav_logo_img {
        margin-top: 2px;
        width: 100px;
    }
    #menu ul, #menu li {
        margin: 5px auto;
        padding: 0;
        list-style: none
    }
    #menu ul {
        width: 100%;
    }
    #menu li {
        float: left;
        display: inline;
        position: relative;
    }
    #menu a {
        display: block;
        line-height: 37px;
        padding: 0 14px;
        text-decoration: none;
        color: #FFFFFF;
        font-size: 15px;
    }
    #menu a.dropdown-arrow:after {
        content: "\23F7";
        margin-left: 5px;
    }
    #menu li a:hover {
        color: #F0F0F0;
        background: #124673;
    }
    #menu input {
        display: none;
        margin: 0;
        padding: 0;
        height: 37px;
        width: 100%;
        opacity: 0;
        cursor: pointer
    }
    #menu label {
        display: none;
        line-height: 37px;
        text-align: center;
        position: absolute;
        left: 35px
    }
    #menu label:before {
        font-size: 1.6em;
        content: "\2261"; 
        margin-left: 20px;
    }
    #menu ul.sub-menus{
        height: auto;
        overflow: hidden;
        width: 170px;
        background: #063D52;
        position: absolute;
        z-index: 99;
        display: none;
    }
    #menu ul.sub-menus li {
        display: block;
        width: 100%;
    }
    #menu ul.sub-menus a {
        color: #FFFFFF;
        font-size: 16px;
    }
    #menu li:hover ul.sub-menus {
        display: block
    }
    #menu ul.sub-menus a:hover{
        background: #F2F2F2;
        color: #444444;
    }
    @media screen and (max-width: 800px){
        #menu {position:relative}
        #menu ul {background:#111;position:absolute;top:100%;right:0;left:0;z-index:3;height:auto;display:none}
        #menu ul.sub-menus {width:100%;position:static;}
        #menu ul.sub-menus a {padding-left:30px;}
        #menu #nav_link {display:block;float:none;width:auto;}
        #nav_logo {position: absolute; left: 40%;}
        #menu input, #menu label {position:absolute;top:0;left:0;display:block;width: 30px;}
        #menu input {z-index:4}
        #menu input:checked + label {color:white}
        #menu input:checked + label:before {content:"\00d7"}
        #menu input:checked ~ ul {display:block}
    }

    #home {
        display: grid;
        border: #00394D 1px solid;
        width: 100%;
    }
    #main_content {
        display: inline-flex;
        width: 100%;
    }
    #left_content {
        width: 20%;
        display: grid;
        justify-content: left;
    }
    #center_content {
        width: 60%;
        display: grid;
        justify-content: center;
        border: #00394D 1px solid;
    }
    #right_content {
        width: 20%;
        display: grid;
        justify-content: right;
    }
    </style>
    <script>
        function updatemenu() {
            if (document.getElementById('responsive-menu').checked == true) {
                document.getElementById('menu').style.borderBottomRightRadius = '0';
                document.getElementById('menu').style.borderBottomLeftRadius = '0';
            }else{
                document.getElementById('menu').style.borderRadius = '7px';
            }
        }
    </script>
</head>
<body>
    <nav id='menu'>
        <input type='checkbox' id='responsive-menu' onclick='updatemenu()'><label></label>
        <li id="nav_logo"><a href='home'><img id="nav_logo_img" src="../assets/images/logo/logo-large.png" alt="" srcset=""></a></li>
        <ul>
            <li id="nav_link"><a href='home'>home</a></li>
            <li id="nav_link"><a href='Profile'>Profile</a></li>
            <li id="nav_link"><a href='Friends'>Friends</a></li>
            <li id="nav_link"><a href='Setting'>Setting</a></li>
            <li id="nav_link"><a href='menu'>Menu</a></li>
        </ul>
    </nav>
    <div id="home">
        <div id="main_content">
            <div id="left_content">
                <ul>store</ul>
            </div>
            <div id="center_content">
                <ul>center</ul>
            </div>
            <div id="right_content">
                <ul>friends</ul>
            </div>
        </div>
    </div>
</body>
</html>