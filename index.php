<!DOCTYPE html>
<?php
header("Access-Control-Allow-Origin: *");
$configs = include('./config.php');
$public_link = $configs['public_link'];
?>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Dinosaur Game Multiplayer by Rapperkey - Dinosaurgame.io</title>
        <meta charset="UTF-8">
        <link rel="canonical" href="https://dinosaurgame.io/" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/x-icon" href="https://dinosaurgame.io/favicon.png" />
        <meta name="description" content="Dinosaur game is an interesting and addictive game. Control your run-automatically dinosaur, avoid all obstacles, and compete with many players. Play now!" />
        <meta http-equiv="X-UA-Compatible" content="requiresActiveX=true,IE=Edge,chrome=1" />
        <meta http-equiv="Content-Language" content="en-US" />
        <meta property="og:type" content="article" />
        <meta property="og:site_name" content="Dinosaurgame.io" />
        <meta property="og:title" content="Dinosaur Game Multiplayer by Rapperkey - Dinosaurgame.io" />
        <meta property="og:description" content="Dinosaur game is an interesting and addictive game. Control your run-automatically dinosaur, avoid all obstacles, and compete with many players. Play now!">
        <meta property="og:url" content="https://dinosaurgame.io/">
        <meta property="og:image" content="https://dinosaurgame.io/dinosaur-game-multiplayer.png" />
        <meta property="og:image:width" content="395" />
        <meta property="og:image:height" content="200" />
        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:description" content="Dinosaur game is an interesting and addictive game. Control your run-automatically dinosaur, avoid all obstacles, and compete with many players. Play now!"/>
        <meta name="twitter:title" content="Dinosaur Game Multiplayer by Rapperkey - Dinosaurgame.io"/>
        <meta name="twitter:image" content="https://dinosaurgame.io/dinosaur-game-multiplayer.png"/>

        <!-- jQuery library -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" ></script>  
        <script type="text/javascript" src="./js/jquery.ba-hashchange.js"></script>
		<?php
			echo "<script src='config.js?v=".$configs['version']."'></script>";
		?>
		<?php
			echo "<script src='function.js?v=".$configs['version']."'></script>";
		?>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="./css/style.min.css?v=1.0.0.20" rel="stylesheet">
        <link href="./css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="./css/bootstrap-social.css" rel="stylesheet" type="text/css" />

        <script async src="//api.adinplay.com/libs/aiptag/pub/FLP/dinosaurgame.io/tag.min.js"></script>
        <script>
            var aiptag = aiptag || {};
            aiptag.consented = true; //GDPR setting, please set this value to false if an EU user has declined or not yet accepted marketing cookies, for users outside the EU please use true
            aiptag.cmd = aiptag.cmd || [];
            aiptag.cmd.display = aiptag.cmd.display || [];
            aiptag.cmd.player = aiptag.cmd.player || [];

            aiptag.cmd.player.push(function () {
                adplayer = new aipPlayer({
                    AD_WIDTH: 960,
                    AD_HEIGHT: 540,
                    AD_FULLSCREEN: true,
                    AD_CENTERPLAYER: true,
                    LOADING_TEXT: 'loading advertisement',
                    PREROLL_ELEM: function () {
                        return document.getElementById('preroll')
                    },
                    AIP_COMPLETE: function (AD_TYPE) {
                        /*******************
                         ***** WARNING *****
                         *******************
                         Please do not remove the PREROLL_ELEM
                         from the page, it will be hidden automaticly.
                         If you do want to remove it use the AIP_REMOVE callback.
                         AD_TYPE can be: preroll or reward.
                         */
                        console.log('Ad Completed');
                    },
                    AIP_REMOVE: function () {
                        // Here it's save to remove the PREROLL_ELEM from the page.
                        // But it's not necessary.
                    }
                });
            });
        </script>
    </head>
    <body class="desktop">
        <div class="M main-container">
            <div class="M0">
                <div id="M1" class="M1">
                    <div class="FX AL CT M2">
                        <h1>Dinosaur Game Multiplayer Rapperkey</h1>
                    </div>
                    <?php
                    $tab = 'index';
                    include 'body.php';
                    ?>
                </div>
                <div class="LL">Copyright © 2017
                    <a href="/about" target="_blank">About</a>
                    <a href="/privacy" target="_blank">Privacy</a>
                    <a href="javascript:;" target="_blank">Contact</a>
                    <a href="https://iogames.space/" target="_blank">More .io Games</a>
                </div>
            </div>
        </div>
        <!--http://45.33.124.160/Dino2/Run  -->
        <div id="iframe-game-on-page">
            <iframe id="sco-game" src="https://dinosaurgame.io/Run/" width="900" height="600" frameborder="0" 
                    seamless="true" webkitallowfullscreen="true" mozallowfullscreen="true"
                    allowfullscreen="true" webkit-playsinline="true" scrolling="no">
            </iframe>
        </div>
		<?php
			echo "<script src='./js/index.js?v=".$configs['version']."'></script>";
		?>
        <img style="display: none" src='https://whos.amung.us/widget/dinosaurio.png' width='0' height='0' border='0'/>
    </body>

</html>
