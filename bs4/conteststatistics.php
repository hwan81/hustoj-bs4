<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
	<?php
	function formatTimeLength($length) {
		$hour = 0;
		$minute = 0;
		$second = 0;
		$result = '';

		global $MSG_SECONDS, $MSG_MINUTES, $MSG_HOURS, $MSG_DAYS;

		if ($length>=60) {
			$second = $length%60;
			
			if ($second>0 && $second<10) {
				$result = '0'.$second.' '.$MSG_SECONDS;}
			else if ($second>0) {
				$result = $second.' '.$MSG_SECONDS;
			}

			$length = floor($length/60);
			if ($length >= 60) {
				$minute = $length%60;
				
				if ($minute==0) {
					if ($result != '') {
						$result = '00'.' '.$MSG_MINUTES.' '.$result;
					}
				}
				else if ($minute>0 && $minute<10) {
					if ($result != '') {
						$result = '0'.$minute.' '.$MSG_MINUTES.' '.$result;}
					}
					else {
						$result = $minute.' '.$MSG_MINUTES.' '.$result;
					}
					
					$length = floor($length/60);

					if ($length >= 24) {
						$hour = $length%24;

					if ($hour==0) {
						if ($result != '') {
							$result = '00'.' '.$MSG_HOURS.' '.$result;
						}
					}
					else if ($hour>0 && $hour<10) {
						if($result != '') {
							$result = '0'.$hour.' '.$MSG_HOURS.' '.$result;
						}
					}
					else {
						$result = $hour.' '.$MSG_HOURS.' '.$result;
					}

					$length = floor($length / 24);
					$result = $length .$MSG_DAYS.' '.$result;
				}
				else {
					$result = $length.' '.$MSG_HOURS.' '.$result;
				}
			}
			else {
				$result = $length.' '.$MSG_MINUTES.' '.$result;
			}
		}
		else {
			$result = $length.' '.$MSG_SECONDS;
		}
		return $result;
	}
	?>


<body>
	<div class="">
        <?php require_once "template/$OJ_TEMPLATE/contest-tab.php"; ?>
		<!-- Main component for a primary marketing message or call to action -->
		<div class="table-responsive">
				<h4><?php if (isset($locked_msg)) echo $locked_msg;?></h4>
				<table id=cs class="table text-center">
					<thead>
						<tr class=toprow>
							<th><th>AC<th>PE<th>WA<th>TLE<th>MLE<th>OLE<th>RE<th>CE<th><th>TR<th>Total
							<?php 
							$i = 0;
							foreach ($language_name as $lang) {
								if (isset($R[$pid_cnt][$i+11]) )	
									echo "<th class=''>$language_name[$i]</th>";
//								else
//									echo "<th>";
								$i++;
							}
							?>
						</tr>
					</thead>
					<tbody>
						<?php
						for ($i=0;$i<$pid_cnt;$i++){
							if(!isset($PID[$i])) $PID[$i]="";
							if ($i&1)
								echo "<tr align=center class=oddrow><td>";
							else
								echo "<tr align=center class=evenrow><td>";

							if (time() < $end_time) {  //during contest/exam time
								echo "<a href=problem.php?cid=$cid&pid=$i>$PID[$i]</a>";
							}
							else {  //over contest/exam time
								//check the problem will be use remained contest/exam
								$sql = "SELECT `problem_id` FROM `contest_problem` WHERE (`contest_id`=? AND `num`=?)";
								$tresult = pdo_query($sql, $cid, $i);

								$tpid = $tresult[0][0];
								$sql = "SELECT `problem_id` FROM `problem` WHERE `problem_id`=? AND `problem_id` IN (
									SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN (
										SELECT `contest_id` FROM `contest` WHERE (`defunct`='N' AND now()<`end_time`)
									)
								)";
								$tresult = pdo_query($sql, $tpid);

								if (intval($tresult) != 0)   //if the problem will be use remained contes/exam */
									echo "<td>$PID[$i]</td>";
								else
									echo "<td><a href='problem.php?id=".$tpid."'>".$PID[$i]."</a></td>";
							}

							for ($j=0;$j<count($language_name)+11;$j++) {
                                if(!isset($R[$i][$j]) && $j<11) {
                                    $R[$i][$j]="-";
                                }else if(!isset($R[$i][$j])){
                                    continue;
                                }
								echo "<td>".$R[$i][$j];
							}
							echo "</tr>";
						}
						echo "<tr align=center class=evenrow><td>Total";
						for ($j=0;$j<count($language_name)+11;$j++) {
							if(!isset($R[$i][$j]) && $j<11) {
                                $R[$i][$j]="-";
                            }else if(!isset($R[$i][$j])){
                                continue;
                            }
							echo "<td>".$R[$i][$j];
						}
						echo "</tr>";
						?>
					</tbody>
					</table>
        </div>
        <div id=submission style="width:600px;height: 300px;" class="" ></div>

    </div> <!-- /container -->


<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<?php include("template/$OJ_TEMPLATE/js.php");?>	    
	<script type="text/javascript" src="include/jquery.tablesorter.js"></script>
	<script type="text/javascript">
		$(document).ready(function()
		{
			$("#cs").tablesorter();
		}
		);
	</script>

	<script language="javascript" type="text/javascript" src="include/jquery.flot.js"></script>
	<script type="text/javascript">
		$(function () {
			var d1 = [];
			var d2 = [];
			<?php
			foreach($chart_data_all as $k=>$d){
				?>
				d1.push([<?php echo $k?>, <?php echo $d?>]);
			<?php }?>
			<?php
			foreach($chart_data_ac as $k=>$d){
				?>
				d2.push([<?php echo $k?>, <?php echo $d?>]);
			<?php }?>
//var d2 = [[0, 3], [4, 8], [8, 5], [9, 13]];
// a null signifies separate line segments
var d3 = [[0, 12], [7, 12], null, [7, 2.5], [12, 2.5]];
$.plot($("#submission"), [
	{label:"<?php echo $MSG_SUBMIT?>",data:d1,lines: { show: true }},
	{label:"<?php echo $MSG_SOVLED?>",data:d2,bars:{show:true}} ],{
		xaxis: {
			mode: "time"
//, max:(new Date()).getTime()
//,min:(new Date()).getTime()-100*24*3600*1000
},
grid: {
	backgroundColor: { colors: ["#fff", "#333"] }
}
});
});
//alert((new Date()).getTime());
</script>

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

</body>
</html>
