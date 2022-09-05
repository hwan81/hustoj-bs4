<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
<?php
$MSG_Running = "대회중"
?>
<div class="container">
    <?php require_once "template/$OJ_TEMPLATE/contest-tab.php"; ?>

  <div class="">

    <div align=center class="input-append">
      <form id=simform class=form-inline action="status.php" method="get">
       <?php echo $MSG_PROBLEM_ID?>
        <input class="form-control" type=text size=4 name=problem_id value='<?php echo htmlspecialchars($problem_id, ENT_QUOTES)?>'>&nbsp;&nbsp;

        <?php echo $MSG_USER?>
        <input class="form-control" type=text size=4 name=user_id value='<?php echo htmlspecialchars($user_id, ENT_QUOTES)?>'>&nbsp;&nbsp;
        <?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
      
        <?php echo $MSG_LANG?> 
        <select class="form-control" size="1" name="language">
          <option value="-1">All</option>
          <?php
          if (isset($_GET['language'])) {
            $selectedLang = intval($_GET['language']);
          }
          else {
            $selectedLang = -1;
          }
          
          $lang_count = count($language_ext);
          $langmask = $OJ_LANGMASK;
          $lang = (~((int)$langmask))&((1<<($lang_count))-1);
          for ($i=0; $i<$lang_count; $i++) {
            if ($lang&(1<<$i))
              echo "<option value=$i ".($selectedLang==$i?"selected":"").">".$language_name[$i]."</option>";
          }
          ?>
        </select>&nbsp;&nbsp;

        <?php echo $MSG_RESULT?> 
        <select class="form-control" size="1" name="jresult">
        <?php
          if (isset($_GET['jresult']))
            $jresult_get = intval($_GET['jresult']);
          else
            $jresult_get = -1;

          if ($jresult_get>=12 || $jresult_get<0)
            $jresult_get = -1;
          /*if ($jresult_get!=-1){
          $sql=$sql."AND `result`='".strval($jresult_get)."' ";
          $str2=$str2."&jresult=".strval($jresult_get);
          }*/          
          if ($jresult_get==-1)
            echo "<option value='-1' selected>All</option>";
          else
            echo "<option value='-1'>All</option>";

          for ($j=0; $j<12; $j++) {
            $i = ($j+4)%12;
            if ($i==$jresult_get)
              echo "<option value='".strval($jresult_get)."' selected>".$jresult[$i]."</option>";
            else
              echo "<option value='".strval($i)."'>".$jresult[$i]."</option>";
          }
        ?>
        </select>&nbsp;&nbsp;

        <?php
        if (isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'source_browser'])) {
          if (isset($_GET['showsim']))
            $showsim = intval($_GET['showsim']);
          else
            $showsim = 0;

          echo "SIM 
          <select id=\"appendedInputButton\" class=\"form-control\" name=showsim onchange=\"document.getElementById('simform').submit();\">
            <option value=0 ".($showsim==0?'selected':'').">All</option>
            <option value=50 ".($showsim==50?'selected':'').">50</option>
            <option value=60 ".($showsim==60?'selected':'').">60</option>
            <option value=70 ".($showsim==70?'selected':'').">70</option>
            <option value=80 ".($showsim==80?'selected':'').">80</option>
            <option value=90 ".($showsim==90?'selected':'').">90</option>
            <option value=100 ".($showsim==100?'selected':'').">100</option>
          </select>&nbsp;&nbsp;&nbsp;&nbsp;";
        
          /* if (isset($_GET['cid']))
          echo "<input type=hidden name=cid value='".$_GET['cid']."'>";
          if (isset($_GET['language']))
            echo "<input type=hidden name=language value='".$_GET['language']."'>";
          if (isset($_GET['user_id']))
            echo "<input type=hidden name=user_id value='".$_GET['user_id']."'>";
          if (isset($_GET['problem_id']))
            echo "<input type=hidden name=problem_id value='".$_GET['problem_id']."'>";
          //echo "<input type=submit>";
          */
        }
        echo "<input type=submit class='form-control' value='$MSG_SEARCH'></form>";
        ?>
      </form>
    </div>
    <br>

    <div class="table-responsive" id=center>
      <table id=result-tab class="table table-bordered content-box-header text-center" >
        <thead class="">
          <tr class='toprow table-primary'>
            <td class="text-center">
              <?php echo $MSG_RUNID?>
            </td>
            <td class="text-center">
              <?php echo $MSG_USER?>
            </td>
            <td class="text-center">
              <?php echo $MSG_PROBLEM_ID?>
            </td>
            <td class="text-center">
              <?php echo $MSG_RESULT?>
            </td>
            <td class="text-center">
              <?php echo $MSG_MEMORY?>
            </td>
            <td class="text-center">
              <?php echo $MSG_TIME?>
            </td>
            <td class="text-center"> 
              <?php echo $MSG_LANG?>
            </td>
            <td class="text-center">
              <?php echo $MSG_CODE_LENGTH?>
            </td>
            <td class="text-center">
              <?php echo $MSG_SUBMIT_TIME?>
            </td>
            <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])) {
              echo "<td class='text-center'>";
                echo $MSG_JUDGER;
              echo "</td>";
            } ?>
          </tr>
        </thead>
        <tbody>
        <?php
          $cnt = 0;
          foreach ($view_status as $row) {
            if ($cnt)
              echo "<tr class='oddrow'>";
            else
              echo "<tr class='evenrow'>";
          
            $i = 0;
            foreach ($row as $table_cell) {
              if ($i==2 || $i==8)
                echo "<td class='text-center'>";
              else if ($i==0 || $i==4 || $i==5 || $i==6 || $i==7)
                echo "<td class='text-right'>";
              else
                echo "<td>";
            
              echo $table_cell;
                echo "</td>";
              $i++;
            }
          
            echo "</tr>\n";
            $cnt = 1-$cnt;
          }
        ?>
        </tbody>
      </table>
    </div>


    <div id="page" class="text-center d-flex justify-content-center">
        <ul class="pagination pagination-sm ">
          <?php
          echo "<li class='page-item'> <a class='page-link' href=status.php?".$str2.">&lt;&lt; Top</a></li>";
          if (isset($_GET['prevtop']))
            echo "<li class='page-item'> <a class='page-link' href=status.php?".$str2."&top=".intval($_GET['prevtop']).">&lt Prev</a></li>";
          else
            echo "<li class='page-item'> <a class='page-link' href=status.php?".$str2."&top=".($top+50).">&lt Prev</a></li>";
          echo "<li class='page-item'> <a class='page-link' href=status.php?".$str2."&top=".$bottom."&prevtop=$top>Next &gt;</a></li>";
          ?>
        </ul>
    </div>




</div>

<!-- /container -->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<?php include("template/$OJ_TEMPLATE/js.php");?>	    

<script>
  var judge_result = [<?php
  foreach ($judge_result as $result) {
		echo "'$result',";
	} ?>
  ''];

	var judge_color = [<?php
	foreach ($judge_color as $result) {
		echo "'$result',";
	} ?>
  ''];
</script>

<script src="template/<?php echo $OJ_TEMPLATE?>/auto_refresh.js" ></script>

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

</div>
<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>