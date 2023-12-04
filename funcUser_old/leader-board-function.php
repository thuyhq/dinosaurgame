<h3>Leaderboard</h3>
    <?php
    $configs = include('./../config.php');
    $public_link = $configs['public_link'];
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
        try{
            $rowPerPage = 10;
            $stt = 1;
            $mng = new MongoDB\Driver\Manager("mongodb://dinosaurgame.io");
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
            $res = $mng->executeQuery('dino2.leaderboard', $query);
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
            $pageRecord = $mng->executeQuery('dino2.leaderboard', $query);


            //count row
            $num=$totalRow;
            if($num>0)
            {
                echo '<table id="tbl_leaderboard" border=2>';
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
                
                    $stt++;
                }//for
                echo"</table>";
            }
            
            $prev = $startrow - $rowPerPage;
            echo('<p style="font-size:14px;margin-botton:0px;">');
            //only print a "Previous" link if a "Next" was clicked
            if ($prev >= 0)
                echo '<a style="color: #FFF;" onclick="return nextPage('.$prev.');" >Previous</a>&emsp;&emsp;';
	    if($totalRow > $startrow+$rowPerPage) 
            	echo '<a style="color: #FFF;" onclick="return previewPage('.($startrow+$rowPerPage).');">Next</a>';
            echo('</p>');
        }catch(\Exceptions $e){
            echo($e);
        }
    ?>
</form>
<?php
echo('<p><a onclick="return clickChangeHash('.'\'index\''.');" style="color: #FFF; text-decoration: none;font-size:14px;">Back to home</a></p>');
?>