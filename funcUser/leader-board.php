<div id="menuscreen" class="screen">
    <div class="tbl-leader-board animated jello">
        <div class="leader text-center">
            <h3>Leaderboard</h3>
                    <?php
                    ini_set('display_errors', 1);
                    ini_set('display_startup_errors', 1);
                  
                    error_reporting(E_ALL);
                   
                    $url_mongodb = $configs['url_mongodb'];
                    $databaseName = $configs['databaseName'];
                        //get row perpage
                        //get startrow
                        try{
                            $rowPerPage = 10;
                            $stt = 1;
                            $mng = new MongoDB\Driver\Manager($url_mongodb);
                            //check if the starting row variable was passed in the URL or not
                            if (!isset($_POST['startrow']) or !is_numeric($_POST['startrow'])) {
                            //we give the value of the starting row to 0 because nothing was found in URL
                                $startrow = 0;
                            //otherwise we take the value from the URL
                            } else {
                                $startrow = (int)$_POST['startrow'];
                                $stt += $startrow ;
                            }
                            //count record
                            $filter = [];
                            $options = [
                                'sort' => ['score' => -1],
                            ];

                            $query = new MongoDB\Driver\Query($filter, $options);
                            $res = $mng->executeQuery($databaseName.'leaderboard', $query);
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
                            $pageRecord = $mng->executeQuery($databaseName.'leaderboard', $query);


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
                            echo('<p style="font-size:20px;margin-top: 10px">');
                         
                            //only print a "Previous" link if a "Next" was clicked
                            if ($prev >= 0)
                                echo '<a style="color: #FFF;" onclick="return nextPage('.$pre.');" >Previous</a>';

                                    //now this is the link..
                           if($totalRow > $startrow+$rowPerPage )
                                echo '<a style="color: #FFF;" onclick="return previewPage('.($startrow+$rowPerPage).');">Next</a>';
                            echo('</p>');
                        }catch(\Exceptions $e){
                            echo($e);
                        }
                    ?>
                </form>
            <?php
                echo('<p><a onclick="return clickChangeHash('.'\'index\''.');" style="color: #FFF; text-decoration: none;font-size:20px;">Back to home</a></p>');
            ?>
            
        </div>
    </div>
</div>

<script>
    function nextPage(startrow){
        var data = {
                        'startrow': startrow
                    };
        $.ajax({
            url: configLink.url_server+"funcUser/leader-board-function.php",
            //url: "http://localhost:1234/sco/funcUser/getUserInfoByUserName.php",
            method: "POST",
            data: data,
            dataType: "json",
            success: function (data) {
                console.log(data);
            },
            complete: function () {
            },
            error: function (msg) {
                $('.leader').html('');
                $('.leader').html(msg.response);
                console.log(msg);
            },
        });
    }

    function previewPage(startrow){
        var data = {
                    'startrow': startrow
                };
        $.ajax({
            url: configLink.url_server+"funcUser/leader-board-function.php",
            //url: "http://localhost:1234/sco/funcUser/getUserInfoByUserName.php",
            method: "POST",
            data: data,
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('.leader').innerHTML = data;
            },
            complete: function () {
            },
            error: function (msg) {
                $('.leader').html('');
                $('.leader').html(msg.response);
                console.log(msg);
            },
        });
    }
</script>