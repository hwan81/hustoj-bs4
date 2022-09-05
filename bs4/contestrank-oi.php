<?php include("template/$OJ_TEMPLATE/oj-header.php");?>

	<div class="container">

        <?php require_once "template/$OJ_TEMPLATE/contest-tab.php"; ?>
		<!-- Main component for a primary marketing message or call to action -->
		<div class="">



			<br>
			<?php
				$rank = 1;
			?>
			<center>
				<a href="contestrank.xls.php?cid=<?php echo $cid?>" >Download</a>
				<h4><?php if (isset($locked_msg)) echo $locked_msg;?></h4>
				<?php if ($OJ_MEMCACHE) {?>
					<a href="contestrank2.php?cid=<?php echo $cid?>" >Replay</a>
				<?php }?>
			</center>

			<div style="overflow: auto">
				<table id=rank>
					<thead>
						<tr class=toprow align=center>
							<td class="{sorter:'false'}" width=5%><?php echo $MSG_STANDING?></td>
							<td width=10%><?php echo $MSG_USER?></td>
							<td width=10%><?php echo $MSG_NICK?></td>
							<td width=5%><?php echo $MSG_SOVLED?></td>
							<td width=5%><?php echo $MSG_CONTEST_PENALTY?></td>
							<td align="center">Mark</td>
							<?php
							for ($i=0; $i<$pid_cnt; $i++) {
						    if (time() < $end_time) {  //during contest/exam time
									echo "<td><a href=problem.php?cid=$cid&pid=$i>$PID[$i]</a></td>";
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
							}
							?>
						</tr>
					</thead>

					<tbody>
						<?php
						for ($i=0; $i<$user_cnt; $i++) {
							if ($i&1)
								echo "<tr class=oddrow align=center>\n";
							else
								echo "<tr class=evenrow align=center>\n";
							
							echo "<td>";
							$uuid = $U[$i]->user_id;
							$nick = $U[$i]->nick;
							
							if ($nick[0]!="*")
								echo $rank++;
							else
								echo "*";
							
							$usolved = $U[$i]->solved;

							if (isset($_GET['user_id']) && $uuid==$_GET['user_id'])
								echo "<td bgcolor=#ffff77>";
							else
								echo"<td>";
							
							echo "<a name=\"$uuid\" href=userinfo.php?user=$uuid>$uuid</a>";
							echo "<td><a href=userinfo.php?user=$uuid>".htmlentities($U[$i]->nick,ENT_QUOTES,"UTF-8")."</a>";
							echo "<td><a href=status.php?user_id=$uuid&cid=$cid>$usolved</a>";
							echo "<td>".sec2str($U[$i]->time);
							echo "<td>".($U[$i]->total);

							for ($j=0; $j<$pid_cnt; $j++) {
								$bg_color = "eeeeee";
								if (isset($U[$i]->p_ac_sec[$j]) && $U[$i]->p_ac_sec[$j]>0) {
									$aa = 0x33+$U[$i]->p_wa_num[$j]*32;
									$aa = $aa>0xaa?0xaa:$aa;
									$aa = dechex($aa);
									$bg_color = "$aa"."ff"."$aa";
									//$bg_color="aaffaa";
									if ($uuid==$first_blood[$j]) {
										$bg_color = "aaaaff";
									}
								}
								else if(isset($U[$i]->p_wa_num[$j]) && $U[$i]->p_wa_num[$j]>0) {
									$aa = 0xaa-$U[$i]->p_wa_num[$j]*10;
									$aa = $aa>16?$aa:16;
									$aa = dechex($aa);
									$bg_color = "ff$aa$aa";
								}
								echo "<td class=well style='background-color:#$bg_color'>";
								if (isset($U[$i])) {
									if (isset($U[$i]->p_ac_sec[$j]) && $U[$i]->p_ac_sec[$j]>0)
										echo sec2str($U[$i]->p_ac_sec[$j]);
									else if (isset($U[$i]->p_wa_num[$j])&&intval($U[$i]->p_wa_num[$j])>0 && isset($U[$i]->p_pass_rate[$j]))
            									echo "(+".(floatval($U[intval($i)]->p_pass_rate[intval($j)])*100).")";
								}
							}
							echo "</tr>\n";
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
	<script type="text/javascript" src="include/jquery.tablesorter.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			$.tablesorter.addParser({
				//set a unique id
				id: 'punish',
				is: function(s) {
					//return false so this parser is not auto detected
					return false;
				},format: function(s) {
					//format your data for normalization
					var v = s.toLowerCase().replace(/\:/,'').replace(/\:/,'').replace(/\(-/,'.').replace(/\)/,'');
					//alert(v);
					v = parseFloat('0'+v);
					return v>1?v:v+Number.MAX_VALUE-1;
				},
					// set type, either numeric or text
					type: 'numeric'
			});

			$("#rank").tablesorter({
				headers: {
					4: {
						sorter:'punish'
					}
					<?php
					for ($i=0; $i<$pid_cnt; $i++) {
						echo ",".($i+5).": { sorter:'punish'}";
					}
					?>
				}
			});
			
			<?php if($OJ_SHOW_METAL) { ?>
				metal();
			<?php } ?>

			setTimeout(function(){document.location.href='/contestrank-oi.php?cid=<?php echo $cid?>'},60000);
		});
	</script>

	<script>
		function getTotal(rows) {
			var total = 0;
			for (var i=0; i<rows.length && total==0; i++) {
				try {
					total = parseInt(rows[rows.length-i].cells[0].innerHTML);
					if (isNaN(total))
						total = 0;
				}
				catch (e) {
				}
			}
			return total;
		}

		function metal() {
			var tb = window.document.getElementById('rank');
			var rows = tb.rows;
			try {
				<?php 
				//若有队伍从未进行过任何提交，数据库solution表里不会有数据，榜单上该队伍不存在，总rows数量不等于报名参赛队伍数量，奖牌比例的计算会出错
				//解决办法：可以为现场赛采用人为设定有效参赛队伍数$OJ_ON_SITE_TEAM_TOTAL，值为0时则采用榜单计算。详情见db_info.inc.php
				if ($OJ_ON_SITE_TEAM_TOTAL!=0)
					echo "var total=".$OJ_ON_SITE_TEAM_TOTAL.";";
				else
					echo "var total=getTotal(rows);";
				?>

				//alert(total);
				for (var i=1; i<rows.length; i++) {
					var cell = rows[i].cells[0];
					var acc = rows[i].cells[3];
					var ac = parseInt(acc.innerText);
					
					if (isNaN(ac))
						ac = parseInt(acc.textContent);
					
					if (cell.innerHTML!="*" && ac>0) {
						var r = parseInt(cell.innerHTML);
						if (r==1) {
							cell.innerHTML = "Winner";

							//cell.style.cssText="background-color:gold;color:red";
							cell.className = "badge btn-warning";
						}

						if (r>1 && r<=total*.05+1)
							cell.className = "badge btn-warning";

						if (r>total*.05+1 && r<=total*.20+1)
							cell.className = "badge";

						if(r>total*.20+1 && r<=total*.45+1)
							cell.className = "badge btn-danger";

						if(r>total*.45+1 && ac>0)
							cell.className = "badge badge-info";
					}
				}
			}
			catch (e) {
				//alert(e);
			}
		}

		<?php if($OJ_SHOW_METAL) { ?>
			metal();
		<?php } ?>
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

	<style>
		.well {
			background-image:none;
			padding:1px;
		}

		td {
			white-space:nowrap;
		}
	</style>

</body>
</html>
