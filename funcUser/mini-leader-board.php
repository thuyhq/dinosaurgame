<?php
					$rq_tabName =  $_GET['id'];
					
                    ini_set('display_errors', 1);
                    ini_set('display_startup_errors', 1);
                  
                    error_reporting(E_ALL);
					$configs = include('../config.php');
					$public_link = $configs['public_link'];
                    $url_mongodb = $configs['url_mongodb'];
                    $databaseName = $configs['databaseName'];
                        //get row perpage
                        //get startrow
                        try{
                            $rowPerPage = 5;
                            $stt = 1;
                            $mng = new MongoDB\Driver\Manager($url_mongodb);                     
						
							if($rq_tabName == '_all'){
								$filter = [];
								$options = [
									'sort' => ['score' => -1],
									'limit' => $rowPerPage
								];
							}else if($rq_tabName == '_month'){
								$firstDay=date('Y-m-d',strtotime("first day of this month"));
								$lastDay=date('Y-m-d',strtotime("last day of this month"));	
								$startMonth = new MongoDB\BSON\UTCDateTime((new DateTime($firstDay))->getTimestamp()*1000);
								$endMonth = new MongoDB\BSON\UTCDateTime((new DateTime($lastDay))->getTimestamp()*1000);
								$filter = [
								'time' => ['$gte' => $startMonth, '$lte' => $endMonth]
								];
								$options = [
									'sort' => ['score' => -1],
									'limit' => $rowPerPage,
									
								];
								
							}
							else if($rq_tabName == '_week'){
								$day = date('w');
								$time = date('Y-m-d', strtotime('-'.$day.' days'));
								$timeEnd = date('Y-m-d', strtotime('+'.(6-$day).' days'));
								$startWeek = new MongoDB\BSON\UTCDateTime((new DateTime($time))->getTimestamp()*1000);
								$endWeek = new MongoDB\BSON\UTCDateTime((new DateTime($timeEnd))->getTimestamp()*1000);
								$filter = [
								'time' => ['$gte' => $startWeek, '$lte' => $endWeek]
								];
								$options = [
									'sort' => ['score' => -1],
									'limit' => $rowPerPage,
									
								];
								
							}
							else if($rq_tabName == "_today"){
								$start = date('Y-m-d',strtotime("today"));
								$end   = date('Y-m-d',strtotime("tomorrow"));
								$startDay =  new MongoDB\BSON\UTCDateTime((new DateTime($start))->getTimestamp()*1000);
								$endDay =  new MongoDB\BSON\UTCDateTime((new DateTime($end))->getTimestamp()*1000);
								$filter = [
								'time' => ['$gte' => $startDay, '$lte' => $endDay]
								];
								$options = [
									'sort' => ['score' => -1],
									'limit' => $rowPerPage,
									
								];
							}
							
                            $query = new MongoDB\Driver\Query($filter, $options);
                            $pageRecord = $mng->executeQuery($databaseName.'leaderboard', $query);                        
							echo '<table id="tbl_leaderboard">';                              
							echo '<tr><th style="text-align: center;">Place</th><th style="text-align: left">Name</th><th style="text-align: center;">High Score</th></tr>';
							foreach($pageRecord as $doc)
							{
								echo "<tr>";
								echo"<td>".$stt."</td>";
								if(isset($doc->avatar) && ($doc->avatar != "")){
									echo('
										<td style="text-align: left">
											<img src="'.$public_link.$doc -> avatar.'" 
												width="16" height="16" />
											<span class="">
											'.$doc -> userName.'
											</span>
										</td>
									');
								}else{
									echo('
										<td style="text-align: left">
											<img src="'.$public_link.'user/noImg.jpg" 
												width="16" height="16" />
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
                           // }
                         
                        
                        }catch(\Exceptions $e){
                            echo($e);
                        }
                    ?>
              
          
            


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