<?php include("template/$OJ_TEMPLATE/oj-header.php");?>

<div class="">
    <?php require_once "template/$OJ_TEMPLATE/contest-tab.php"; ?>
  <!-- Main component for a primary marketing message or call to action -->
    <div class="">
        <div class="alert alert-warning">
            <?php echo $MSG_CONTEST_SUSPECT1?>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <td>IP address</td>
                    <td colspan=2>Used ID</td>
                    <td>Time</td>
                    <td>IP address count</td>
                </tr>

                <?php
                foreach ($result1 as $row) {
                    echo "<tr>";
                    echo "<td>".$row['ip']."</td>";
                    echo "<td>".$row['user_id']."</td>";
                    echo "<td>";
                    echo "<a href='../userinfo.php?user=".$row['user_id']."'><sub>".$MSG_USERINFO."</sub></a> <sub>/</sub> ";
                    echo "<a href='../status.php?cid=$contest_id&user_id=".$row['user_id']."'><sub>".$MSG_CONTEST." ".$MSG_SUBMIT."</sub></a>";
                    echo "</td>";
                    echo "<td>".$row['in_date'];
                    echo "<td>".$row['c']."</td>";
                    echo "</tr>";
                }
                ?>

            </table>
        </div>

		<div class="alert alert-danger">
            <?php echo $MSG_CONTEST_SUSPECT2?>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <tr>
                    <td colspan=2>User ID</td>
                    <td>Used IP address</td>
                    <td>Time</td>
                    <td>IP address count</td>
                </tr>

                <?php
                foreach ($result2 as $row) {
                    $ip = explode(".",$row['ip']);
                    echo "<tr>";
                    echo "<td>".$row['user_id']."</td>";
                    echo "<td>";
                    echo "<a href='../userinfo.php?user=".$row['user_id']."'><sub>".$MSG_USERINFO."</sub></a> <sub>/</sub> ";
                    echo "<a href='../status.php?cid=$contest_id&user_id=".$row['user_id']."'><sub>".$MSG_CONTEST." ".$MSG_SUBMIT."</sub></a>";
                    echo "</td>";
                    echo "<td>{$ip[0]}.{$ip[1]}.{$ip[2]}.*";
                    echo "<td>".$row['time'];
                    echo "<td>".$row['c'];
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

			</center>
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

<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>