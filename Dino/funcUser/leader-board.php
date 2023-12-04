<!doctype html>
<?php 
    $configs = include('./config.php');
    $public_link = $configs['public_link'];
?>
<html>
<head>
    <title>SCO</title>
    <link href="./../desktop.css" rel="stylesheet" type="text/css" />
    <link href="./../css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="./../css/bootstrap-social.css" rel="stylesheet" type="text/css" />
    <link href="./../css/font-awesome.css" rel="stylesheet" type="text/css" />
    <!-- jQuery library -->
    <script src="./../js/jquery.min.js"></script>
    <script src="../function.js"></script>
    <script src="../config.js"></script>
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
        body, html {
            font-family: M41_LOVEBIT;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .game-name img {
            height: 60px;
            width: auto;
        }
        #tbl_leaderboard{
            font-size:12px;
        }
        /* Media */
        @media (min-width: 1920px){
            #tbl_leaderboard {
                font-size: 20px;
            }
        }
    </style>
</head>
<body class="desktop">
<div class="main-container">
    <div class="screens">
        <div id="menuscreen" class="screen">
            <div class="title animated jello">
                <div class="leader text-center" style="color: #000;">
                    <h3>Leaderboard</h3>
                            <?php
                            // ini_set('display_errors', 1);
                            // ini_set('display_startup_errors', 1);
                            // error_reporting(E_ALL);
                            
                            $configs = include('./../config.php');
                            $public_link = $configs['public_link'];
                                try{
                                    $rowPerPage = 10;
                                    $stt = 1;
                                    $mng = new MongoDB\Driver\Manager("mongodb://45.33.124.160");
                                    //check if the starting row variable was passed in the URL or not
                                    if (!isset($_GET['startrow']) or !is_numeric($_GET['startrow'])) {
                                    //we give the value of the starting row to 0 because nothing was found in URL
                                        $startrow = 0;
                                    //otherwise we take the value from the URL
                                    } else {
                                        $startrow = (int)$_GET['startrow'];
                                        $stt += $startrow ;
                                    }
                                    //count record
                                    $filter = [];
                                    $options = [
                                        'sort' => ['score' => -1],
                                    ];

                                    $query = new MongoDB\Driver\Query($filter, $options);
                                    $res = $mng->executeQuery('dino.leaderboard', $query);
                                    //$cursor = current($res->toArray());
                                    $totalRow = 0;
                                    foreach ($res as $document){
                                        $totalRow += 1;
                                    }
                                    

                                    //1 decending -1 accending
                                    $options = [
                                        'sort' => ['score' => -1],
                                        'limit' => $rowPerPage,
                                        'skip' => $startrow
                                    ];

                                    $query = new MongoDB\Driver\Query($filter, $options);
                                    $pageRecord = $mng->executeQuery('dino.leaderboard', $query);


                                    //count row
                                    $num=$totalRow;
                                    if($num>0)
                                    {
                                        echo '<table id="tbl_leaderboard" style="width:500px;" class="leader-board" border=2>';
                                        echo '<tr><th style="text-align: center;">Place</th><th style="text-align: left">Name</th><th style="text-align: center;">High Score</th></tr>';
                                        foreach($pageRecord as $doc)
                                        {
                                            echo "<tr>";
                                            echo"<td>".$stt."</td>";
                                            if(isset($doc->avatar) && ($doc->avatar != "")){
                                                echo('
                                                    <td style="text-align: left">
                                                        <img src="'.$public_link.$doc -> avatar.'" 
                                                            width="35" height="35" />
                                                        <span class="">
                                                        '.$doc -> userName.'
                                                        </span>
                                                    </td>
                                                ');
                                            }else{
                                                echo('
                                                    <td style="text-align: left">
                                                        <img src="'.$public_link.'user/noImg.jpg" 
                                                            width="35" height="35" />
                                                        <span class="">
                                                        '.$doc -> userName.'
                                                        </span>
                                                    </td>
                                                ');
                                            }
                                            echo"<td>".$doc -> score."</td>";
                                            //echo('
                                              //      <td style="text-align: left">
                                             //           <img src="'.$public_link.'/img/equipment/body/'.$doc -> skinId.'.png" 
                                             //               width="35" height="35" />
                                             //       </td>
                                             //   ');
                                            echo"</tr>";
                                            $stt++;
                                        }//for
                                        echo"</table>";
                                    }
                                   
                                    $prev = $startrow - $rowPerPage;
                                    echo('<p style="font-size:14px;margin-botton:0px;">');
                                    //only print a "Previous" link if a "Next" was clicked
                                    if ($prev >= 0)
                                        echo '<a href="'.$_SERVER['PHP_SELF'].'?startrow='.$prev.'">Previous</a>';

                                         //now this is the link..
                                    echo '<a href="'.$_SERVER['PHP_SELF'].'?startrow='.($startrow+$rowPerPage).'">Next</a>';
                                    echo('</p>');
                                }catch(\Exceptions $e){
                                    echo($e);
                                }

                                
                            ?>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .footer {
        color: #ef6100;
        position: absolute;
        bottom: 10px;
        right: 12px;
        font-weight : bold;
    }
    .footer a{
        color: #ef6100;
        margin: 0 8px;
        font-weight : bold;
    }
</style>


</body>
</html>
