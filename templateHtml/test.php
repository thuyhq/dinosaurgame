<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @font-face {
                font-family: 'M41_LOVEBIT';
                src: url('theme/fonts/M41_LOVEBIT.eot');
                src: url('theme/fonts/M41_LOVEBIT.eot?#iefix') format("embedded-opentype"),
                    url('theme/fonts/M41_LOVEBIT.woff') format("woff"),
                    url('theme/fonts/M41_LOVEBIT.ttf') format("truetype");
                font-weight: normal;
                font-style: normal;
            }
            *, :after, :before {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            body, html {
                font-family: M41_LOVEBIT;
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }
            body{
                background-color: #69C6CE;
                background-image: url('http://flaap.io/theme/img/bg-pattern.png'),url('http://flaap.io/theme/img/background.png');
                background-position: 0 bottom;
                background-repeat: repeat-x;
                background-size: auto;
            }
            a{
                text-decoration: none
            }
            button{
                cursor: pointer
            }
            div, input, button{
                font-family: M41_LOVEBIT;
            }
            .M, .M0{
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
            }
            .M0{
                background: rgba(0,0,0,.5); 
            }
            .M1{
                width: 1150px;
                height: 566px;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%,-50%);
            }
            .M2{
                text-align: center;
                margin: 20px 0
            }
            .M2 img{
                height: 95px;
                width: auto
            }
            .M3{
                width: 1150px;
                margin-right: auto;
                margin-left: auto;
                padding-left: 5px;
                padding-right: 5px;
            }
            .M3:before, .M4:before, .M7:before{
                content: " ";
                display: table;
            }
            .M4{
                margin-left: -5px;
                margin-right: -5px;
            }
            .M7{
                height: 160px;
                max-height: 340px;
                line-height: 20px;
            }
            .M7 h2{
                margin: 0 
            }
            .M7, .LL{
                font-family: Verdana,sans-serif;
                font-size: 14px;
            }
            /* Tools */
            .PN{
                margin-bottom: 20px;
                background-color: #fff;
                border: 1px solid transparent;
                border-radius: 4px;
                -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
                box-shadow: 0 1px 1px rgba(0,0,0,.05);
            }
            .PB{
                padding: 18px;
            }
            .SC{
                overflow-y: scroll;
            }
            .FG{
                margin-bottom: 15px;
            }
            .FC{
                width: 100%;
                height: 34px;
                padding: 6px 12px;
                background-color: #fff;
                border: 1px solid #ccc;
                border-radius: 4px;
                -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
                box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
                -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
                -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
                font-size: 14px;
                line-height: 1.42857143;
                color: #555;
                display: block;
            }
            .BT{
                height: 45px;
                border-radius: 5px;
                border: none;
                color: #FFF;
                font-size: 13px;
                font-weight: 900
            }
            .PL{
                width: 30%;
                margin-right: 10px;
                background-color: #449d44;
                border-color: #398439;
            }
            .PL:hover{
                background: #5cb85c;
                border-color: #4cae4c;
            }
            .PF{
                width: 70%;
                background: #c68f35 
            }
            .PF:hover{
                background: #E6A83E;
            }
            .LB, .LI{
                color: #009cff;
                font-size: 11px
            }
            .LL{
                color: #FFF;
                position: absolute;
                bottom: 10px;
                right: 12px;
            }
            .LL a{
                color: #fff;
                margin: 0 8px;
            }
            .LL a:hover{
                text-decoration: underline
            }
            .SK{
                background: rgba(0,0,0,0.2);
                position: relative;
            }
            .SK1 a, .SK3 a{
                display: block;
                width: 50px;
                height: 100px;
            }
            .SK1 a:hover, .SK3 a:hover{
                background: rgba(0,0,0,0.2);
            }
            .SK1 img{
                margin: 40px 0 0 15px;
            }
            .SK2{
                text-align: center
            }
            .SK3{
                text-align: right
            }
            .SK3 img{
                margin: 40px 15px 0 0;
            }
            .SH{
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                padding: 25px;
                background: rgba(0,0,0,0.6);
            }
            .SH1 a{
                margin-right: 5px
            }
            .SH1 img{
                width: 86px;
                height: 25px
            }
            .SH1 a:hover img{
                border: 1px solid #FFF
/*                -moz-box-shadow: 0 1px 3px 0 #FFF;
                box-shadow: 0 1px 3px 0 #FFF;*/
            }
            .SH2{
                font-size: 8px;
                color: #FFF;
                text-align: center;
                margin-top: 10px;
            }
            .FX {
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
            }
            .AL {
                -ms-flex-align: center;
                -webkit-align-items: center;
                align-items: center;
            }
            .CT {
                -ms-flex-pack: center;
                -webkit-justify-content: center;
                justify-content: center;
            }
            .GR {
                -webkit-flex-grow: 1;
                -ms-flex-positive: 1;
                flex-grow: 1;
            }
            .CG{
                text-align: center
            }
            .CO{
                position: relative;
                min-height: 1px;
                padding-left: 5px;
                padding-right: 5px;
                width: 33.33333333%;
                float: left
            }
            .CL{
                left: 33.33333333%;
            }
            .CR{
                right: 33.33333333%;
            }
            .CB{
                clear: both
            }
            /* Media */
            @media (min-width: 1150px){
                .ID {
                    height: 45px;
                    padding: 13px 17px;
                }
            }

        </style>
    </head>
    <body>
        <?php 
            $configs = include('./../config.php');
            $public_link = $configs['public_link'];
        ?>
        <div class="M">
            <div class="M0">
                <div id="M1" class="M1">
                    <div class="FX AL CT M2">
                    <?php
                    echo('
                        <img class="LG" src="'.$public_link.'/img/unity/logImg.jpg" alt="Flaap.io"/>
                        <img class="BR" src="'.$public_link.'/img/unity/iconImg.png" alt="Flappy Bird"/>
                    ');
                    ?>
                    </div>
                    <div class="M3">
                        <div class="M4">
                            <div class="CO CL">
                                <div class="PN M5">
                                    <div class="PB">
                                        <div class="FG">
                                            <input class="ID CG FC" type="text" name="nickname" placeholder="Your nickname" maxlength="15" autofocus="autofocus">
                                        </div>
                                        <div class="FG FX AL CT">
                                            <button class="PL BT">PLAY</button>
                                            <button class="PF BT">PLAY WITH FRIEND</button>
                                        </div>
                                        <div class="FG FX AL CT">
                                            <a class="GR LB" href="#">Leaderboard</a>
                                            <a class="LI" href="#">Login</a>
                                        </div>
                                        <div class="SK FX AL">
                                            <div class="SK1">
                                                <a class="" href="javascript:;"><img src="http://flaap.io/theme/img/left-arrow.png"></a>    
                                            </div>
                                            <div class="GR SK2">
                                                <img src="http://flaap.io/images/bird1.gif">
                                            </div>
                                            <div class="SK3">
                                                <a class="" href="javascript:;"><img src="http://flaap.io/theme/img/right-arrow.png"></a>
                                            </div>
                                            <div class="SH">
                                                <div class="FX AL CT SH1">
                                                    <a href="//www.facebook.com/sharer.php?u=http://flaap.io" title="Share on Facebook" target="_blank">
                                                        <img src="http://flaap.io/theme/img/share-fb.png">
                                                    </a>
                                                    <a href="//twitter.com/intent/tweet?text=Flappy+Bird+Multiplayer+has+come+back+with+many+crazier+new+birds.+Join+and+play+with+your+friends+now!+%40FlaapIo+%3d%3E+&amp;url=http%3A%2F%2Fflaap.io" title="Share on Twitter" target="_blank">
                                                        <img src="http://flaap.io/theme/img/share-tw.png">
                                                    </a>
                                                    <a href="//plus.google.com/share?url=http://flaap.io" title="Share on Google Plus" target="_blank">
                                                        <img src="http://flaap.io/theme/img/share-gg.png">
                                                    </a>
                                                </div>
                                                <div class="SH2">
                                                    SHARE WITH FRIENDS TO UNLOCK SKINS
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="PN M6">
                                    <div class="PB SC">
                                        <div class="M7">
                                            <h2>About Flaap.io</h2>
                                            <p>Flaap.io is an io game based on the scenario of Dong Nguyen's famous Flappy Bird. Your task is to drive the bird flying through the pipes without touching them. So what is new in this game?</p>
                                            <ol>
                                                <li>Allow to play online with many other players around the world.</li>
                                                <li>Allow to create Room to challenge friends.</li>
                                                <li>There are many levels of bird transformation. Each bird will have different flying speeds, so your bird will be "crazy" and more difficult to control afterward.</li>
                                            </ol>
                                            <p>This game is made for entertainment purposes. So do not get angry while playing! Enjoy and show your abilities! Do not forget to share the bird level as well as the highest score compared to others you reach. Have Fun!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="CO CR">
                                <div class="PN">
                                    <div class="PB">
                                        <img src="https://tpc.googlesyndication.com/simgad/7270528229665677716"/>
                                    </div>
                                </div>
                            </div>
                            <div class="CO">
                                <div class="PN">
                                    <div class="PB">
                                        <img src="https://tpc.googlesyndication.com/simgad/7299774637395305523"/>
                                    </div>
                                </div>
                            </div>
                            <div class="CB"></div>
                        </div>
                    </div>
                </div>
                <div class="LL">Copyright © 2017
                    <a href="#">About</a>
                    <a href="#">Privacy</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </div>

        <script>
            function getByID(id) {
                return e = document.getElementById(id);
            }
            function autoScale() {
                var windowWidth = window.innerWidth;
                var windowHeight = window.innerHeight;
                var e = getByID("M1");
                var s1 = windowHeight / 566;
                var s2 = windowWidth / 1150;
                if (windowWidth >= 1150 && windowHeight >= 566) {
                    e.style.transform = "translate(-50%, -50%) scale(1)";
                } else {
                    if (s1 < s2) {
                        e.style.transform = "translate(-50%, -50%) scale(" + s1 + ")";
                    } else {
                        e.style.transform = "translate(-50%, -50%) scale(" + s2 + ")";
                    }
                }
            }

            autoScale();
            window.addEventListener("resize", function () {
                autoScale();
            });
        </script>
    </body>
</html>
