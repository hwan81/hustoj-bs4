<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
<div class="container">
		<div class="mb-2 ">
            <div class="">
                <form id=simform class=form-inline action="status.php" method="get">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <?php echo $MSG_PROBLEM_ID?>
                            </div>
                        </div>
                        <input class="form-control" size="4" type=text placeholder="<?=$MSG_PROBLEM_ID?>" name=problem_id value='<?php echo  htmlspecialchars($problem_id, ENT_QUOTES) ?>'>&nbsp;&nbsp;
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <?php echo $MSG_USER?>
                            </div>
                        </div>
                        <input class="form-control" type=text size=4 placeholder="<?=$MSG_USER?>" name=user_id value='<?php echo  htmlspecialchars($user_id, ENT_QUOTES) ?>'>&nbsp;&nbsp;
                        <?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <?php echo $MSG_LANG?>
                            </div>
                        </div>
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
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <?php echo $MSG_RESULT?>
                            </div>
                        </div>
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
                    </div>
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
                    }
                    echo "<input type=submit class='btn btn-primary' value='$MSG_SEARCH'>";
                    ?>
                </form>
            </div>
        </div>

		<div class="table-responsive">
			<table id=result-tab class=" table table-hover table-bordered ">
				<thead>
					<tr class='table-info text-center'>
						<th>
							<?php echo $MSG_RUNID?>
						</th>
                        <th>
							<?php echo $MSG_USER?>
						</th>
                        <th>
							<?php echo $MSG_PROBLEM_ID?>
						</th>
                        <th>
							<?php echo $MSG_RESULT?>
						</th>
                        <th>
							<?php echo $MSG_MEMORY?>
						</th>
                        <th>
							<?php echo $MSG_TIME?>
						</th>
                        <th>
							<?php echo $MSG_LANG?>
						</th>
                        <th>
							<?php echo $MSG_CODE_LENGTH?>
						</th>
                        <th>
							<?php echo $MSG_SUBMIT_TIME?>
						</th>
						<?php	if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])) {
							echo "<th>";
								echo $MSG_JUDGER;
							echo "</th>";
						} ?>
					</tr>
				</thead>
				<tbody class="border-info text-center">
				<?php
					foreach ($view_status as $row) {
					    $row[4] = str_replace("<div id=\"center\">","",$row[4]);
					    $row[4] = str_replace("</div>","",$row[4]);

						echo "<tr class='text-center'>";
						$row[3] = str_replace("label","badge",$row[3]);
                        $row[3] = str_replace("badge-","border border-",$row[3]);
						$row[3] = str_replace("title", "data-toggle=\"tooltip\" title" , $row[3])
						?>
                        <td><?=$row[0]?></td>
                        <td><?=$row[1]?></td>
                        <td><?=$row[2]?></td>
                        <td><?=$row[3]?></td>
                        <td class="text-right font-italic">
                            <?=$row[4]?>
                        </td>
                        <td class="text-right font-italic"><?=$row[5]?></td>
                        <td><?=$row[6]?></td>
                        <td class="text-right"><?=$row[7]?></td>
                        <td class="small"><?=$row[8]?></td>
                        <?php	if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])) {
                            echo "<td class='small'>";
                            echo $row[9];
                            echo "</td>";
                        } ?>

					
<!--						$i = 0;-->
<!--						foreach ($row as $table_cell) {-->
<!--							if ($i==4 || $i==5 || $i==7)-->
<!--								echo "<td class='text-right'>";-->
<!--							else-->
<!--								echo "<td>";-->
<!---->
<!--							$table_cell = str_replace( "label","badge", $table_cell);-->
<!---->
<!--							echo $table_cell;-->
<!--							echo "</td>";-->
<!--							$i++;-->
<!--						}-->
                <?php
						echo "</tr>";
					}
				?>
				</tbody>
			</table>
		</div>

        <div align=center id=center>
            <nav id="page" class="">
                <ul class="pagination pagination-sm justify-content-center">
					<?php
					echo "<li class='page-item'> <a class='page-link' href=status.php?".$str2.">처음으로</a></li>";
					if (isset($_GET['prevtop']))
						echo "<li class='page-item'> <a class='page-link' href=status.php?".$str2."&top=".intval($_GET['prevtop']).">이전페이지</a></li>";
					else
						echo "<li class='page-item'> <a class='page-link' href=status.php?".$str2."&top=".($top+50).">이전페이지</a></li>";
				
					echo "<li class='page-item'> <a class='page-link' href=status.php?".$str2."&top=".$bottom."&prevtop=$top>다음페이지</a></li>";
					?>
				</ul>
			</nav>
		</div>

</div>

<script>
	var i = 0;
	var judge_result = [<?php
	foreach ($judge_result as $result) {
		echo "'$result',";
	} ?>
	''];

	var judge_color = [<?php
	foreach ($judge_color as $result) {
	    $result = str_replace("label", "badge", $result);
		echo "'$result',";
	} ?>
	''];
</script>
<script>
    $(document).ready(function (){
        $(".badge").tooltip();

    });
</script>
<script src="template/<?php echo $OJ_TEMPLATE?>/auto_refresh.js?v=0.40"></script>

<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>