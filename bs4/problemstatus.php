<?php include("template/$OJ_TEMPLATE/oj-header.php");?>

<div class="container">
    <div class="card">
        <div class="card-header font-italic h3">
            P : <?=$id?> Status
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="">
                        <table id='statics' class="table">
                            <?php
                            foreach ( $view_problem as $row ) {
                                ?>
                                <tr>
                                    <td class="chart-title"><?=$row[0]?></td>
                                    <td class="chart-data"><?=$row[1]?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>

                </div>
                <div class="col-md-6">
                    <canvas id="p-info"></canvas>
                </div>
            </div>
<!--            <div>-->
<!--                <table id='problemstatus' class="table">-->
<!--                    <thead>-->
<!--                    <tr class="text-center">-->
<!--                        <th>--><?php //echo $MSG_Number?><!--</th>-->
<!--                        <th>RunID</th>-->
<!--                        <th>--><?php //echo $MSG_USER?><!--</th>-->
<!--                        <th>--><?php //echo $MSG_MEMORY?><!--</th>-->
<!--                        <th>--><?php //echo $MSG_TIME?><!--</th>-->
<!--                        <th>--><?php //echo $MSG_LANG?><!--</th>-->
<!--                        <th>--><?php //echo $MSG_CODE_LENGTH?><!--</th>-->
<!--                        <th>--><?php //echo $MSG_SUBMIT_TIME?><!--</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    --><?php
//                    $cnt = 0;
//                    foreach ( $view_solution as $row ) {
//                        echo "<tr class='text-center'>";
//                        foreach ( $row as $table_cell ) {
//                            echo "<td>";
//                            echo "\t" . $table_cell;
//                            echo "</td>";
//                        }
//                        echo "</tr>";
//                        $cnt = 1 - $cnt;
//                    }
//                    ?>
<!--                </table>-->
<!--            </div>-->
        </div>
        <div class="card-footer text-center">
            <?php
            echo "<a href='problemstatus.php?id=$id'>[TOP]</a>";
            echo "&nbsp;&nbsp;<a href='status.php?problem_id=$id'>[STATUS]</a>";
            if ( $page > $pagemin ) {
                $page--;
                echo "&nbsp;&nbsp;<a href='problemstatus.php?id=$id&page=$page'>[PREV]</a>";
                $page++;
            }
            if ( $page < $pagemax ) {
                $page++;
                echo "&nbsp;&nbsp;<a href='problemstatus.php?id=$id&page=$page'>[NEXT]</a>";
                $page--;
            }
            ?>
        </div>
    </div>

<!--			<h1>Problem --><?php //echo $id ?><!-- Status</h1>-->
<!--			<center>-->
<!--				<<br>-->
<!--							--><?php //if(isset($view_recommand)){?>
<!--							<table id=recommand>-->
<!--								<tr>-->
<!--									<td>-->
<!--										Recommanded Next Problem<br>-->
<!--										--><?php
//										$cnt = 1;
//										foreach ( $view_recommand as $row ) {
//											echo "<a href=problem.php?id=$row[0]>$row[0]</a>&nbsp;";
//											if ( $cnt % 3 == 0 )echo "<br>";
//											$cnt++;
//										}
//										?>
<!--									</td>-->
<!--								</tr>-->
<!--							</table>-->
<!--							--><?php //}?>
<!--							</td>-->
<!--							<td>-->
<!---->
<!---->
<!--				</table>-->

                                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js" integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg==" crossorigin="anonymous"></script>
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
                                            data: chart_data,
                                            backgroundColor: [
                                                '#28a745',
                                                '#d20606',
                                                '#f6ff00',
                                                '#0606FF',
                                                '#fc03e3',
                                                '#03e7fc'

                                            ]
                                        }]
                                    };
                                    var config = {
                                        type: 'bar',
                                        data: data,
                                        height:100,
                                        options: {
                                            response: false,
                                            maintainAspectRatio:false

                                        }
                                    };

                                    var myChart = new Chart(
                                        document.getElementById('p-info'),
                                        config
                                    );

                                </script>

		</div>

	<!-- /container -->


<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>