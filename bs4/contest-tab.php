<?php
    if (isset($_GET['cid'])) {
        $cid = intval($_GET['cid']);
        $view_cid = $cid;


        //check contest valid
        $sql = "SELECT * FROM `contest` WHERE `contest_id`=?";
        $result = pdo_query($sql,$cid);

        $rows_cnt = count($result);
        $contest_ok = true;
        $password = "";

        if (isset($_POST['password']))
            $password = $_POST['password'];

        if (get_magic_quotes_gpc()) {
            $password = stripslashes($password);
        }

        if ($rows_cnt==0) {
            $view_title = "종료되었습니다!";
        }
        else{
            $row = $result[0];
            $view_private = $row['private'];

            if ($password!="" && $password==$row['password'])
                $_SESSION[$OJ_NAME.'_'.'c'.$cid] = true;

            if ($row['private'] && !isset($_SESSION[$OJ_NAME.'_'.'c'.$cid]))
                $contest_ok = false;

            if($row['defunct']=='Y')
                $contest_ok = false;

            if (isset($_SESSION[$OJ_NAME.'_'.'administrator']))
                $contest_ok = true;

            $now = time();
            $start_time = strtotime($row['start_time']);
            $end_time = strtotime($row['end_time']);
            $view_description = $row['description'];
            $view_title = $row['title'];
            $view_start_time = $row['start_time'];
            $view_end_time = $row['end_time'];
        }
    }

    if(!function_exists(formatTimeLength)){
        function formatTimeLength($length)
        {
            $hour = 0;
            $minute = 0;
            $second = 0;
            $result = '';

            global $MSG_SECONDS, $MSG_MINUTES, $MSG_HOURS, $MSG_DAYS;

            if ($length >= 60) {
                $second = $length % 60;

                if ($second > 0 && $second < 10) {
                    $result = '0' . $second . ' ' . $MSG_SECONDS;
                } else if ($second > 0) {
                    $result = $second . ' ' . $MSG_SECONDS;
                }

                $length = floor($length / 60);
                if ($length >= 60) {
                    $minute = $length % 60;

                    if ($minute == 0) {
                        if ($result != '') {
                            $result = '00' . ' ' . $MSG_MINUTES . ' ' . $result;
                        }
                    } else if ($minute > 0 && $minute < 10) {
                        if ($result != '') {
                            $result = '0' . $minute . ' ' . $MSG_MINUTES . ' ' . $result;
                        }
                    } else {
                        $result = $minute . ' ' . $MSG_MINUTES . ' ' . $result;
                    }

                    $length = floor($length / 60);

                    if ($length >= 24) {
                        $hour = $length % 24;

                        if ($hour == 0) {
                            if ($result != '') {
                                $result = '00' . ' ' . $MSG_HOURS . ' ' . $result;
                            }
                        } else if ($hour > 0 && $hour < 10) {
                            if ($result != '') {
                                $result = '0' . $hour . ' ' . $MSG_HOURS . ' ' . $result;
                            }
                        } else {
                            $result = $hour . ' ' . $MSG_HOURS . ' ' . $result;
                        }

                        $length = floor($length / 24);
                        $result = $length . $MSG_DAYS . ' ' . $result;
                    } else {
                        $result = $length . ' ' . $MSG_HOURS . ' ' . $result;
                    }
                } else {
                    $result = $length . ' ' . $MSG_MINUTES . ' ' . $result;
                }
            } else {
                $result = $length . ' ' . $MSG_SECONDS;
            }
            return $result;
        }
    }



?>

<div class="container mb-3">
    <div class="h3 text-center font-italic">
        <span class="p-2 pl-3 pr-3 rounded bg-danger text-white">
            <?=$view_title?> (ID : <?php echo $view_cid?>)
        </span>
    </div>


    <div class="tab-content pt-2" id="myTabContent">
        <div class="">
            <div class="table-responsive">
                <table class="nowrap text-nowrap text-center" id="contest_menu">
                    <tr>
                        <th class="">
                            <a href="#" class="btn btn-primary text-white btn-sm" onclick="showContestInfo()">
                                <i class="far fa-question-circle"></i> 대회정보
                            </a>
                        </th>
                        <td class="">
                            <a href="contest.php?cid=<?php echo $cid?>" class="btn btn-outline-info btn-sm"><?php echo $MSG_PROBLEMS?></a>
                        </td>
                        <td class="">
                            <a href="status.php?cid=<?php echo $view_cid?>" class="btn btn-outline-info btn-sm"><?php echo $MSG_SUBMIT?></a>
                        </td>
                        <td class="">
                            <a href="contestrank.php?cid=<?php echo $view_cid?>" class="btn btn-outline-info btn-sm"><?php echo $MSG_STANDING?></a>
                        </td>
                        <td class="">
                            <a href="contestrank-oi.php?cid=<?php echo $view_cid?>" class="btn btn-outline-info btn-sm"><?php echo "OI".$MSG_STANDING?></a>
                        </td>
                        <td class="">
                            <a href="conteststatistics.php?cid=<?php echo $view_cid?>" class="btn btn-outline-info btn-sm"><?php echo $MSG_STATISTICS?></a>
                        </td>
                        <td class="">
                            <a href="suspect_list.php?cid=<?php echo $view_cid?>" class="btn btn-outline-info btn-sm"><?php echo $MSG_IP_VERIFICATION?></a>
                        </td>

                        <?php if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])) {?>
                            <td class="">
                                <a href="user_set_ip.php?cid=<?php echo $view_cid?>" class="btn btn-outline-info btn-sm"><?php echo $MSG_SET_LOGIN_IP?></a>
                            </td>
                            <td class="">
                                <a target="_blank" href="../../<?=$OJ_ADMIN?>/contest_edit.php?cid=<?php echo $view_cid?>" class="btn btn-outline-info btn-sm"><?php echo "EDIT"?></a>
                            </td>

                        <?php } ?>
                    </tr>

                </table>
            </div>

        </div>
    </div>

    <script>
        let list = ['contest.php','status.php','contestrank.php','contestrank-oi.php','conteststatistics.php','suspect_list.php'];
        let pos = list.indexOf("<?=str_replace("/","",$_SERVER['PHP_SELF'])?>");
        $("#contest_menu td").eq(pos).children("a").addClass("active").addClass("bg-info").addClass("text-white");
    </script>
    <div class="modal fade" id="contestInfo" tabindex="-1" aria-labelledby="contestInfo" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <?php echo $view_title?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 table table-responsive ">
                            <div class="card card-body mb-3">
                                <?=$view_description?>
                            </div>
                            <div class="table-responsive">
                                <table class="table  table-bordered border-success" >
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
</div>
