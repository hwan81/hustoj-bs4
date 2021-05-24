<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
<?php
switch ($Rank) {
    case 1:
        $bg = "bg-danger text-light";
        break;
    case 2:
        $bg = "bg-primary text-light";
        break;
    case 3:
        $bg = "bg-warning";
        break;
    default:
        $bg = "bg-secondary text-light";
        break;
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js" integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg==" crossorigin="anonymous"></script>
<div class="container">
    <div class="alert  font-italic shadow mb-3 p-3">
        <table class="font-italic">
            <tr>
                <td><span class="alert border font-weight-bolder h3 mr-4 <?=$bg?>"><?=$Rank?></span></td>
                <td>
                    <span class="h2 d-sm-block d-none"><?=$user?>(<?=htmlentities($nick,ENT_QUOTES,"UTF-8")?>)</span>
                    <span class="h5 d-sm-none d-block"><?=$user?>(<?=htmlentities($nick,ENT_QUOTES,"UTF-8")?>)</span>
                    <span><?=$school?>&nbsp;&nbsp;&nbsp;<a href=mail.php?to_user=<?=$user?>><i class="far fa-envelope"></i>메시지보내기</a></span>

                </td>
            </tr>
        </table>
    </div>
    <div class="row mt-5">
        <div class="col-6">
           <table class="table">
               <tr>
                   <td>제출횟수</td>
                   <td><a href='status.php?user_id=<?php echo $user?>'><?=$Submit?></a></td>
               </tr>
               <tr>
                   <td class="w-75">문제해결</td>
                   <td><a href='status.php?user_id=<?php echo $user?>&jresult=4'><?=$AC?></a></td>
               </tr>
               <?php
               foreach($view_userstat as $row){
                   $msg_result = '';
                   switch($jresult[$row[0]]){
                       case "AC":
                           $msg_result ="성공횟수";break;
                       case "WA":
                           $msg_result ="틀린횟수";break;
                       case "CE":
                           $msg_result = "컴파일오류";break;
                       default:
                           $msg_result = $jresult[$row[0]];
                   }
                   ?>
                   <tr>
                       <td class="chart-title"><?=$msg_result?></td>
                       <td class="chart-data"><a href="status.php?user_id=<?=$user?>&jresult=<?=row[0]?>"><?=$row[1]?></a></td>
                   </tr>

               <?php
                   //echo "<tr ><td>".$jresult[$row[0]]."<td align=center><a href=status.php?user_id=$user&jresult=".$row[0]." >".$row[1]."</a>ㅋㅋㅋ</tr>";
               }
               ?>
           </table>
       </div>
        <div class="col-6 text-center" >
            <canvas class="shadow" id="userinfo" style="height:20vh; width:20vw">
            </canvas>
        </div>

    </div>
    <div class="mt-5 ">
        <div class="card">
            <div class="card-header h5">
                해결한 문제 목록
            </div>
            <div class="card-body">
                <div class="row">
                <?php $sql="SELECT `problem_id`,count(1) from solution where `user_id`=? and result=4 group by `problem_id` ORDER BY `problem_id` ASC";
                if ($result=pdo_query($sql,$user)){
                    foreach($result as $row){
                        ?>
                        <div class="col-lg-2 col-md-3 col-sm-4 text-center col-6 ">
                            <a class="" href="problem.php?id=<?=$row[0]?>">P:<?=$row[0]?></a>
                            (<a href="status.php?user_id=<?=$user?>&problem_id=<?=$row[0]?>">AC:<?=$row[1]?></a>)
                        </div>
                <?php
                    }
                }
                ?>
                </div>
                <div class="mt-3 p-5">
                    <canvas class="shadow " id="resolve-chart"></canvas>
                </div>

            </div>
        </div>
    </div>


<?php
    if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
?>
    <div>
        <table class="table">
            <tr class="text-center">
                <td>UserID<td>Password<td>IP<td>Time
            </tr>
            <tbody>
            <?php
            $cnt=0;
            foreach($view_userinfo as $row){
                echo "<tr class='text-center'>";
                for($i=0;$i<count($row)/2;$i++){
                    echo "<td>";
                    echo "\t".$row[$i];
                    echo "</td>";
                }
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

<?php
}
?>
</center>
      </div>

    </div> <!-- /container -->



<!--chart script-->
<script>
    function timeConverter(UNIX_timestamp){
        var a = new Date(UNIX_timestamp*1);
        var months = ['1','2','3','4','5','6','7','8','9','10','11','12'];
        var year = a.getFullYear();
        var month = months[a.getMonth()];
        var date = a.getDate();
        var time = year +"/"+ month +"/"+ date+"" ;
        return time;
    }
    var d_all_data = new Array();
    var d_ac_data = new Array();
    var d_date = new Array();
    <?php
        $ac = 0;
        $chart_data_ac_date = array_keys($chart_data_ac);
        $chart_data_ac = array_values($chart_data_ac);

        foreach ($chart_data_all as $k => $d){
            ?>
            d_date.unshift(timeConverter(<?=$k?>));
            d_all_data.unshift(<?=$d?>);
            <?php
                if($chart_data_ac_date[$ac]== $k){
                    echo "d_ac_data.unshift($chart_data_ac[$ac]);\n";
                    $ac++;
                    }
                else{
                    echo "d_ac_data.unshift(0);\n";
                }
        }
    ?>


    var labels = d_date;
    var data = {
        labels: labels,
        datasets: [{
            label: '제출횟수',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: d_all_data,
            stack:'combined'
        },
            {
                label: '성공',
                backgroundColor: 'rgb(0, 210, 70)',
                borderColor: 'rgb(0, 210, 70)',
                data: d_ac_data,

                type:'bar'
            }]
    };
    var config = {
        type: 'line',
        data: data,
        options: {}
    };
    var myChart = new Chart(
        document.getElementById('resolve-chart'),
        config
    );
</script>
<script>
    //pie
    var chart_title = new Array();
    for(i = 0 ; i < $(".chart-title").length ; i++){
        chart_title.push($(".chart-title").eq(i).text());
    }
    var chart_data = new Array();
    for(i = 0 ; i < $(".chart-data").length ; i++){
        chart_data.push(eval($(".chart-data").eq(i).text()));
    }

    var data = {
        labels: chart_title,
        datasets: [{
            label: 'My First Dataset',
            data: chart_data,
            backgroundColor: [
                '#28a745',
                '#dc3545',
                '#343a40'
            ],
            hoverOffset: 4
        }]
    };
    var config = {
        type: 'pie',
        data: data,
        height:100,
        options: {
            response: false,
            maintainAspectRatio:false

        }
    };

    var myChart = new Chart(
        document.getElementById('userinfo'),
        config
    );

</script>
<!--end of chart script-->

<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>
