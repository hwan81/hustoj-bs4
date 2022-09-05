<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
<?php
//$view_news 수정
$sql = "select * "
    . "FROM `news` "
    . "WHERE `defunct`!='Y' AND `title`!='faqs.$OJ_LANG'"
    . "ORDER BY `importance` ASC,`time` DESC "
    . "LIMIT 5";
$news_rows = mysql_query_cache( $sql );

//대회 목록
$sql = "SELECT *  FROM contest WHERE contest.defunct='N' ORDER BY contest_id DESC limit 0,5";
$contest_rows = mysql_query_cache($sql);
//print_r($contest_rows);


//$monday->subDays(date('w'));
$today = date('w');
$monday = date('Y-m-d',strtotime('-'.($today - 1).'days'));
$s =  $monday;
$cnt = 10;
$sql="SELECT users.`user_id`,`nick`,s.`solved`,t.`submit` FROM `users`
                                        inner join
                                        (select count(distinct problem_id) solved ,user_id from solution 
						where in_date>str_to_date('$s','%Y-%m-%d') and result=4 and user_id NOT IN('admin')
						group by user_id order by solved desc limit 0, $cnt) s 
					on users.user_id=s.user_id
                                        inner join
                                        (select count( problem_id) submit ,user_id from solution 
						where in_date>str_to_date('$s','%Y-%m-%d') 
						group by user_id order by submit desc ) t 
					on users.user_id=t.user_id                      
                                ORDER BY s.`solved` DESC,t.submit,reg_time  LIMIT  0, $cnt
                         ";

$rank_rows = mysql_query_cache($sql);


?>


	<div class="container">
        <div class="row">
            <div class="col-md-8 ">
                <div class="card shadow border border-info m-2">
                    <div class="card-header h5 bg-info text-light">
                        <a class="text-light" href="notice.php"><i class="fas fa-comment-dots"></i>공지사항</a>
                    </div>
                    <div class="card-body">
                        <div class="accordion list-group list-group-flush" id="news_accordion">

                            <?php
                            foreach ($news_rows as $lt){
                                ?>
                                    <div class="list-group-item p-1 m-0" id="news_title_<?=$lt['news_id']?>">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <a class="text-left" type="button" data-toggle="collapse" data-target="#news_<?=$lt['news_id']?>" aria-expanded="true" aria-controls="news_<?=$lt['news_id']?>">
                                                    <?=$lt['title']?>
                                                </a>
                                                <span class="d-block d-sm-none small"><?=$lt['user_id']?>&nbsp;|&nbsp;<?=$lt['time']?></span>
                                            </div>
                                            <div class="col-md-5 text-right small d-none d-sm-block">
                                                <?=$lt['user_id']?>&nbsp;|&nbsp;<?=$lt['time']?>
                                            </div>
                                        </div>

                                    </div>

                                    <div id="news_<?=$lt['news_id']?>" class="collapse pl-1" aria-labelledby="news_title_<?=$lt['news_id']?>" data-parent="#news_accordion">
                                        <div class="card-body">
                                            <?=$lt['content']?>
                                        </div>
                                    </div>

                            <?php
                            }
                            ?>

                        </div>
                    </div>

                </div>
                <div class="card shadow m-2 border border-success">
                    <div class="card-header h5 bg-success text-light">
                        <a class="text-light" href="contest.php"><i class="fas fa-award"></i> 대회 안내</a>
                    </div>
                    <div class="card-body">
                        <table class="table  border-bottom ">
                            <?php
                            foreach ($contest_rows as $clt){
                                if($clt['private']) $pri = "&nbsp;<i class=\"fas fa-lock\"></i>";
                                else $pri ="";

                                $clt['start_time'] = explode(" ",$clt['start_time'])[0];
                                $clt['end_time'] = explode(" ",$clt['end_time'])[0];
                                ?>
                                <tr>
                                    <td class="text-center"><?=$clt['contest_id']?></td>
                                    <td>
                                        <a href="contest.php?cid=<?=$clt['contest_id']?>"><?=$clt['title']?><?=$pri?></a><br>
                                        <span class="d-block d-sm-none small"><?=$clt['start_time']?>~<?=$clt['end_time']?></span>

                                    </td>
                                    <td class=" text-right d-none d-sm-block">
                                        <span class="d-none d-sm-block small"><?=$clt['start_time']?>~<?=$clt['end_time']?></span>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>

                    </div>
                </div>
                <div class="card shadow m-2 border border-primary">
                    <div class="card-header h5 bg-primary text-light">
                        <a class="text-light" href="status.php"><i class="fas fa-chart-line"></i> 최근 제출 현황</a>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 ">
                <div class="card shadow m-2 border border-secondary">
                    <div class="card-header h5 italic bg-secondary text-light">
                        <a class="text-light" href="ranklist.php?scope=w"><i class="fas fa-crown"></i> 주간랭킹</a>
                    </div>
                    <div class="card-body">
                        <?php
                        $rank = 1;
                        foreach ($rank_rows as $rt){
                            $rate = sprintf("%.2f",($rt['solved'] / $rt['submit']) * 100);
                            switch ($rank) {
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
                                    $bg = "border border-secondary";
                                    break;
                            }
                            ?>
                            <table class="" width="100%">
                                <tr>
                                    <td rowspan="2" class="w-25 pl-0 pr-2 text-left">
                                        <span class="p-3 m-1 badge large <?=$bg?> "><?=$rank++?></span>
                                    </td>
                                    <td class="p-0">
                                        <a href="userinfo.php?user=<?=$rt['user_id']?>"><?=$rt['nick']?></a><br>
                                        <span class="badge badge-primary">P</span><span class="small"> <?=$rt['solved'] ?></span>&nbsp;&nbsp; <span class="badge badge-danger">R</span><span class="small"> <?=$rate?>%</span>
                                    </td>
                                </tr>
                            </table>
                            <?php
                        }
                        ?>

                    </div>
                </div>
                <div class="card shadow m-2 border border-secondary">
                    <div class="card-header h5 bg-secondary text-light">
                        <i class="fas fa-robot"></i> System info
                    </div>
                    <div class="card-body p-4" >
                        <canvas id="cpuChart" >
                            <div class="spinner-border text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Loading...
                        </canvas>
                      <!--  <canvas id="memChart" >
                            <div class="spinner-border text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Loading...
                        </canvas>-->
                    </div>
                </div>

            </div>
        </div>

	</div>
	<!-- /container -->


	<!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js" integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg==" crossorigin="anonymous"></script>
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
        var d_all = <?php echo json_encode($chart_data_all)?>;
        var d_ac = <?php echo json_encode($chart_data_ac)?>;

        var d_date = new Array();
        var d_all_data = new Array();
        var d_ac_data = new Array();

        var ac = 0;
        for(var k = 0 ; k < d_all.length ; k++ ){
            d_date.unshift( timeConverter(d_all[k][0]));
            d_all_data.unshift(d_all[k][1]);
            if(d_all[k][0] == d_ac[ac][0]){
                d_ac_data.unshift(d_ac[ac][1]);
                ac++;
            }else{
                d_ac_data.unshift(0);
            }
        }

        var labels = d_date;
        var data = {
            labels: labels,
            datasets: [{
                label: '제출횟수',
                backgroundColor: 'rgb(115, 2, 23)',
                borderColor: 'rgb(115, 2, 23)',
                data: d_all_data,
                stack:'combined'
            },
            {
                label: '성공',
                backgroundColor: 'rgb( 2, 89, 32)',
                borderColor: 'rgb( 2, 89, 32)',
                data: d_ac_data,

                type:'bar'
            }]
        };
        const config = {
            type: 'line',
            data: data,
            options: {}
        };
        var myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
    <script>
        $(document).ready(function (){
            var url = "<?php echo "/template/" . $OJ_TEMPLATE . "/systeminfo.php" ?>";
            $.ajax({
                url: url,
                type:"POST",
                dataType : "json",
                success:function (d){
                    // console.log(d);
                    var cpu  = d[0].total_cpu;
                    var cpu_free = 100 - cpu;
                    var mem = 100 - (d[1].usable_mem *1 )/ (d[1].total_mem * 1 ) * 100;
                    var mem_free = 100 - mem;
                    var data_cpu = {
                        labels : [''/*,'used'*/],
                        datasets: [{
                            label:'CPU',
                            data: [cpu_free],
                            backgroundColor: [
                                'rgba(4, 119, 191, 1)'
                            ],
                            borderColor: [
                                'rgb(255, 255, 255)'
                            ],
                            borderWidth: 1,

                        },{
                            label:'MEM',
                            data: [mem_free/*, cpu*/],
                            backgroundColor: [
                                'rgba(217, 89, 61, 1)'
                            ],
                            borderColor: [
                                'rgb(255, 255, 255)'
                            ],
                            borderWidth: 1
                        }]
                    };

                    var cpu_config = {
                        type:'bar',
                        data: data_cpu,
                        scales:{
                            x:{
                                min: 0,
                                max: 100
                            }
                        },
                        options: {
                            indexAxis: 'y',
                            title: {
                                display: true,
                                text: 'CPU free'
                            },
                            scales:{
                                x:{
                                    min: 0,
                                    max: 100
                                }
                            }
                        },
                    };

                    var cpuChart = new Chart(
                        document.getElementById('cpuChart'),
                        cpu_config
                    );

                }
            });
        });
    </script>
<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>