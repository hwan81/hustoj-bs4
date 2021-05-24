<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
<?php
$MSG_Running = "대회중"
?>
<div class="container">
    <div class="h4 text-center">
            <?php echo $MSG_CONTEST_ID?> : <?php echo $view_cid?> - <?php echo $view_title ?>
        </div>

        <ul class="nav nav-tabs " id="myTab" role="tablist">
            <li class="nav-item " role="presentation">
                <a class="nav-link pl-5 pr-5 bg-primary text-light" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">대회개요</a>
            </li>
            <li class="nav-item " role="presentation">
                <a class="nav-link pl-5 pr-5 bg-success text-light active" id="profile-tab" data-toggle="tab" href="#problems" role="tab" aria-controls="problems" aria-selected="false">문제</a>
            </li>
        </ul>
        <div class="tab-content pt-4" id="myTabContent">
            <div class="tab-pane fade show " id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="card border-success p-3">
                    <?php echo $view_description?>
                </div>
                <div class="mt-2">
                    <table class="table table-bordered border-success" >
                        <tr>
                            <td><?php echo $MSG_CONTEST_STATUS?></td>
                            <td>
                                <?php
                                if ($now>$end_time)
                                    echo "<span class=text-muted>".$MSG_End."</span>";
                                else if ($now<$start_time)
                                    echo "<span class=text-success>".$MSG_Start."</span>";
                                else
                                    echo "<span class=text-danger>".$MSG_Running."</span>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>대회기간</td>
                            <td> <?php echo $view_start_time?> ~ <?php echo $view_end_time?></td>
                        </tr>
                        <tr>
                            <td><?php echo $MSG_SERVER_TIME?></td>
                            <td><span id=nowdate > <?php echo date("Y-m-d H:i:s")?></span></td>
                        </tr>
                        <tr>
                            <td><?php echo $MSG_CONTEST_OPEN?></td>
                            <td>
                                <?php if ($view_private=='0')
                                    echo "<span class=text-primary>".$MSG_Public."</span>";
                                else
                                    echo "<span class=text-danger>".$MSG_Private."</span>";
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <?php if ($now>$end_time) {
                                echo "<td colspan='2'><span class=text-muted>$MSG_Ended</span></td>";
                            }
                            else if ($now<$start_time) {
                                echo "<td><span class=text-success>$MSG_Start&nbsp;</span></td>";
                                echo "<td><span class=text-success>$MSG_TotalTime</span>"." ".formatTimeLength($end_time-$start_time)."</td>";
                            }
                            else {
                                echo "<td><span class=text-danger>$MSG_Running</span></td>";
                                echo "<td><span class=text-danger>$MSG_LeftTime</span>"." ".formatTimeLength($end_time-$now)."</td>";
                            }
                            ?>
                        </tr>
                        <?php if (isset($OJ_RANK_LOCK_PERCENT)&&$OJ_RANK_LOCK_PERCENT!=0) { ?>
                            <tr>
                                <td>Lock Board Time</td>
                                <td>
                                    <?php echo date("Y-m-d H:i:s", $view_lock_time) ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade show active" id="problems" role="tabpanel" aria-labelledby="profile-tab">
                <div class="btn-group">
                    <a href="contest.php?cid=<?php echo $cid?>" class="btn btn-primary btn-sm"><?php echo $MSG_PROBLEMS?></a>
                    <a href="status.php?cid=<?php echo $view_cid?>" class="btn btn-primary btn-sm"><?php echo $MSG_SUBMIT?></a>
                    <a href="contestrank.php?cid=<?php echo $view_cid?>" class="btn btn-primary btn-sm"><?php echo $MSG_STANDING?></a>
                    <a href="contestrank-oi.php?cid=<?php echo $view_cid?>" class="btn btn-primary btn-sm"><?php echo "OI".$MSG_STANDING?></a>
                    <a href="conteststatistics.php?cid=<?php echo $view_cid?>" class="btn btn-primary btn-sm"><?php echo $MSG_STATISTICS?></a>
                    <a href="suspect_list.php?cid=<?php echo $view_cid?>" class="btn btn-warning btn-sm"><?php echo $MSG_IP_VERIFICATION?></a>
                    <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])) {?>
                        <a href="user_set_ip.php?cid=<?php echo $view_cid?>" class="btn btn-success btn-sm"><?php echo $MSG_SET_LOGIN_IP?></a>
                        <a target="_blank" href="../../admin/contest_edit.php?cid=<?php echo $view_cid?>" class="btn btn-success btn-sm"><?php echo "EDIT"?></a>
                    <?php } ?>
                </div>
                <table id='problemset' class='table table-striped'  width='90%'>
                    <thead>
                    <tr align=center class='toprow'>
                        <td></td>
                        <td style="cursor:hand" onclick="sortTable('problemset', 1, 'int');" ><?php echo $MSG_PROBLEM_ID?></td>
                        <td><?php echo $MSG_TITLE?></td>
                        <td><?php echo $MSG_SOURCE?></td>
                        <td style="cursor:hand" onclick="sortTable('problemset', 4, 'int');"><?php echo $MSG_SOVLED?></td>
                        <td style="cursor:hand" onclick="sortTable('problemset', 5, 'int');"><?php echo $MSG_SUBMIT?></td>
                    </tr>
                    </thead>
                    <tbody align='center'>
                    <?php
                    $cnt=0;
                    foreach ($view_problemset as $row) {
                        if ($cnt)
                            echo "<tr class='oddrow'>";
                        else
                            echo "<tr class='evenrow'>";

                        foreach ($row as $table_cell) {
                            echo "<td>";
                            echo "\t".$table_cell;
                            echo "</td>";
                        }
                        echo "</tr>";
                        $cnt=1-$cnt;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

  </div>
</div>

<!-- /container -->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<?php include("template/$OJ_TEMPLATE/js.php");?>      

<script src="include/sortTable.js"></script>

<script>
  var diff = new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
  //alert(diff);
  function clock() {
    var x,h,m,s,n,xingqi,y,mon,d;
    var x = new Date(new Date().getTime()+diff);
    y = x.getYear()+1900;

    if (y>3000)
      y -= 1900;

    mon = x.getMonth()+1;
    d = x.getDate();
    xingqi = x.getDay();
    h = x.getHours();
    m = x.getMinutes();
    s = x.getSeconds();
    n = y+"-"+(mon>=10?mon:"0"+mon)+"-"+(d>=10?d:"0"+d)+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);

    //alert(n);
    document.getElementById('nowdate').innerHTML = n;
    setTimeout("clock()",1000);
  }
  clock();
</script>
<script>
    $('#myTab a').on('click', function (event) {
    event.preventDefault()
    $(this).tab('show')
    });
</script>
</body>
</html>
