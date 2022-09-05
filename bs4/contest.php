<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
<?php
$MSG_Running = "대회중"
?>
<div class="container">
    <?php require_once "template/$OJ_TEMPLATE/contest-tab.php"; ?>


    <div class="" id="problems" >

        <div class="table table-responsive">
            <table id='problemset' class='table table-bordered table-hover text-center' >
                <thead>
                <tr class='toprow '>
                    <td></td>
                    <td style="cursor:hand" onclick="sortTable('problemset', 1, 'int');" ><?php echo $MSG_PROBLEM_ID?></td>
                    <td><?php echo $MSG_TITLE?></td>
                    <td><?php echo $MSG_SOURCE?></td>
                    <td style="cursor:hand" onclick="sortTable('problemset', 4, 'int');"><?php echo $MSG_SOVLED?></td>
                    <td style="cursor:hand" onclick="sortTable('problemset', 5, 'int');"><?php echo $MSG_SUBMIT?></td>
                </tr>
                </thead>
                <tbody >

                <?php
                $cnt=0;
                foreach ($view_problemset as $row) {
                    ?>
                        <tr>
                            <td><?=$row[0]?></td>
                            <td><?=$row[1]?></td>
                            <td class="text-nowrap text-left"><?=$row[2]?></td>
                            <td><?=$row[3]?></td>
                            <td><?=$row[4]?></td>
                            <td><?=$row[5]?></td>
                        </tr>
                    <?php

//
//                    if ($cnt)
//                        echo "<tr class='oddrow'>";
//                    else
//                        echo "<tr class='evenrow'>";
//
//                    foreach ($row as $table_cell) {
//                        echo "<td>";
//                        print_r($row);
//                        echo "\t".$table_cell;
//                        echo "</td>";
//                    }
//                    echo "</tr>";
//                    $cnt=1-$cnt;

                }
                ?>
                </tbody>
            </table>
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

<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>