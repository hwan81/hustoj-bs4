<?php
   header("Cache-control:private"); 
?>
<?php
  require_once("../include/const.inc.php");
  require_once("admin-header.php");
  if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator']))){
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
  }
?>

<?php
$description = "";
if(isset($_POST['startdate'])){
  require_once("../include/check_post_key.php");

  $starttime = $_POST['startdate']." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
  $endtime = $_POST['enddate']." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";
  //echo $starttime;
  //echo $endtime;

  $title = $_POST['title'];
  $private = $_POST['private'];
  $password = $_POST['password'];
  $description = $_POST['description'];
  
  if(get_magic_quotes_gpc()){
    $title = stripslashes($title);
    $private = stripslashes($private);
    $password = stripslashes($password);
    $description = stripslashes($description);
  }

  $lang = $_POST['lang'];
  $langmask = 0;
  foreach($lang as $t){
    $langmask += 1<<$t;
  } 

  $langmask = ((1<<count($language_ext))-1)&(~$langmask);
  //echo $langmask; 

  $sql = "INSERT INTO `contest`(`title`,`start_time`,`end_time`,`private`,`langmask`,`description`,`password`,`user_id`)
          VALUES(?,?,?,?,?,?,?,?)";

  $description = str_replace("<p>", "", $description); 
  $description = str_replace("</p>", "<br />", $description);
  $description = str_replace(",", "&#44; ", $description);
  $user_id=$_SESSION[$OJ_NAME.'_'.'user_id'];
  echo $sql.$title.$starttime.$endtime.$private.$langmask.$description.$password,$user_id;
  $cid = pdo_query($sql,$title,$starttime,$endtime,$private,$langmask,$description,$password,$user_id) ;
  echo "Add Contest ".$cid;
  $sql = "DELETE FROM `contest_problem` WHERE `contest_id`=$cid";
  $plist = trim($_POST['cproblem']);
  $pieces = explode(",",$plist );

  if(count($pieces)>0 && intval($pieces[0])>0){
    $sql_1 = "INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`) VALUES (?,?,?)";
    $plist="";
    for($i=0; $i<count($pieces); $i++){
      if($plist)$plist.=",";
      $plist.=$pieces[$i];
      pdo_query($sql_1,$cid,$pieces[$i],$i);
    }
    //echo $sql_1;
    $sql = "UPDATE `problem` SET defunct='N' WHERE `problem_id` IN ($plist)";
    pdo_query($sql) ;
  }

  $sql = "DELETE FROM `privilege` WHERE `rightstr`=?";
  pdo_query($sql,"c$cid");

  $sql = "INSERT INTO `privilege` (`user_id`,`rightstr`) VALUES(?,?)";
  pdo_query($sql,$_SESSION[$OJ_NAME.'_'.'user_id'],"m$cid");

  $_SESSION[$OJ_NAME.'_'."m$cid"] = true;
  $pieces = explode("\n", trim($_POST['ulist']));

  if(count($pieces)>0 && strlen($pieces[0])>0){
    $sql_1 = "INSERT INTO `privilege`(`user_id`,`rightstr`) VALUES (?,?)";
    for($i=0; $i<count($pieces); $i++){
      pdo_query($sql_1,trim($pieces[$i]),"c$cid") ;
    }
  }
  echo "<script>window.location.href=\"contest_list.php\";</script>";
}
else{
  if(isset($_GET['cid'])){
    $cid = intval($_GET['cid']);
    $sql = "SELECT * FROM contest WHERE `contest_id`=?";
    $result = pdo_query($sql,$cid);
    $row = $result[0];
    $title = $row['title']."-Copy";

    $private = $row['private'];
    $langmask = $row['langmask'];
    $description = $row['description'];

    $plist = "";
    $sql = "SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=? ORDER BY `num`";
    $result = pdo_query($sql,$cid);
    foreach($result as $row){
      if($plist) $plist = $plist.',';
      $plist = $plist.$row[0];
    }

    $ulist = "";
    $sql = "SELECT `user_id` FROM `privilege` WHERE `rightstr`=? order by user_id";
    $result = pdo_query($sql,"c$cid");

    foreach($result as $row){
      if($ulist) $ulist .= "\n";
      $ulist .= $row[0];
    }
  }
  else if(isset($_POST['problem2contest'])){
    $plist = "";
    //echo $_POST['pid'];
    sort($_POST['pid']);
    foreach($_POST['pid'] as $i){       
      if($plist)
      $plist.=','.intval($i);
      else
        $plist=$i;
    }
  }else if(isset($_GET['spid'])){
    //require_once("../include/check_get_key.php");
    $spid = intval($_GET['spid']);

    $plist = "";
    $sql = "SELECT `problem_id` FROM `problem` WHERE `problem_id`>=? ";
    $result = pdo_query($sql,$spid);
    foreach($result as $row){
      if($plist) $plist.=',';
      $plist.=$row[0];
    }
  }

?>
    <script>
        $(document).ready(function(){
            var res = new Array()
            $("#view_problem").on("click",function(){
                if($("#cproblem").val() != ""){
                    res = $("#cproblem").val().split(",");
                }
                $("#problem_list_all").modal();
                $("#select-result").val(res);
            });

            $("#select_ok").on("click",function (){
                $("input[name=cproblem]").val(res);
                $("#problem_list_all").modal('hide');
            });

            window.addEventListener( 'message', function( e ) {
                var d = e.data.childData.split("-");
                if(d[0] == "add" && res.indexOf(d[1]) == -1){
                    res.push(d[1]);
                    res.sort();
                }else{
                    var p = res.indexOf(d[1]);
                    if( p > 0){
                        res.splice(p, 1);
                    }
                }
                $("#select-result").val(res);
            });

            $("input[name=lang_check]").on("click",function(){
                var c = eval($("input[name=lang_check]:checked").val());
                $("input[name='lang[]'").prop("checked", c);
            });
        });
    </script>
    <div class="container">
        <div class="h3 text-center">
            <?php echo $MSG_CONTEST."-".$MSG_ADD ?>
        </div>
        <form method=POST>
            <div class="form-group h4">
                <label>
                    <?php echo $MSG_CONTEST."-".$MSG_TITLE ?>
                </label>
                <input class="form-control" type=text name=title value="<?php echo isset($title)?$title:""?>">
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <?=$MSG_Start?>
                                </div>
                            </div>
                            <input class="form-control text-center" type=date name='startdate' value='<?php echo date('Y').'-'. date('m').'-'.date('d')?>'  >
                            <input class="form-control text-center col-2" type=text name=shour value=<?php echo date('H')?>>
                            <div class="input-group-middle">
                                <div class="input-group-text">
                                    시
                                </div>
                            </div>

                            <input class="form-control text-center col-2" type=text name=sminute value=00  >
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    분
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <?=$MSG_End?>
                                </div>
                            </div>
                            <input class="form-control text-center" type=date name='enddate' value='<?php echo date('Y').'-'. date('m').'-'.date('d')?>'  >

                            <input class="form-control text-center col-2" type=text name=ehour value=<?php echo date('H')?>>
                            <div class="input-group-middle">
                                <div class="input-group-text">
                                    시
                                </div>
                            </div>
                            <input class="form-control text-center col-2" type=text name=eminute value=00  >
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    분
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group h4 mt-3">
                <label>
                    대회문제
                </label>
                <a class="btn btn-primary" href="#" id="view_problem">문제찾기를 이용해 보세요</a>
                <input class="form-control" id="cproblem" placeholder="문제검색찾기버튼을 활용하셔도 되고, 직접 1001,1002 형식으로 입력하셔도 됩니다." type=text  name=cproblem value="<?php echo isset($plist)?$plist:""?>">
            </div>
            <div class="form-group  mt-3">
                <label class="h4">대회 사용언어</label>
                <label>
                    <input type="radio" name="lang_check" value=1>모두체크
                </label>
                <label>
                    <input type="radio" name="lang_check" value=0>모두체크해제
                </label>
                <div>
                    <?php
                    $lang_count = count($language_ext);
                    $lang = (~((int)$langmask))&((1<<$lang_count)-1);

                    if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];
                    else $lastlang = 0;

                    for($i=0; $i<$lang_count; $i++){
                        echo "<label class='btn btn-sm btn-outline-info ml-2 mr-2'>";
                        echo "<input type='checkbox' name='lang[]' value=$i ".( $lang&(1<<$i)?"checked":"").">&nbsp;".$language_name[$i];
                        echo "</label>";
                    }
                    ?>
                </div>
            </div>
            <div class="form-group h4 mt-3">
                <label>대회 사용자</label>
                <div>
                    <textarea class="form-control" name='ulist' rows='10' style placeholder='user1<?php echo "\n"?>user2<?php echo "\n"?>user3<?php echo "\n"?>
              <?php echo $MSG_PRIVATE_USERS_ADD?><?php echo "\n"?>'><?php if(isset($ulist)){ echo $ulist;}?></textarea>
                </div>
            </div>

            <div class="form-group mt-3 h4">
                <label>
                    대회 공개
                </label>
                <div class="input-group">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            대회 공개 여부
                        </div>
                    </div>
                    <select name=private class="form-control ">
                        <option class="text-success" value=0 <?php echo $private=='0'?'selected=selected':''?>><?php echo $MSG_Public?></option>
                        <option class="text-danger" value=1 <?php echo $private=='1'?'selected=selected':''?>><?php echo $MSG_Private?></option>
                    </select>
                    <div class="input-group-middle">
                        <div class="input-group-text">
                            암호
                        </div>
                    </div>
                    <input class="form-control" type=text name=password  value="">
                </div>
            </div>
            <div class="form-group h4 mt-3">
                <label>대회설명</label>
                <textarea class="tiny" rows=13 name=description cols=80><?php echo isset($description)?$description:""?></textarea>
            </div>
            <div class="form-group h4 mt-3">
                <?php require_once("../include/set_post_key.php");?>
                <input class="btn btn-primary btn-block" type=submit value='<?php echo $MSG_SAVE?>' name=submit>
            </div>
        </form>
</div>
    <!-- Modal -->
    <div class="modal fade" id="problem_list_all" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg " >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">문제 선택</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe src="problem_list_all.php" class="form-control" style="height: 500px;"></iframe>
                </div>
                <div class="modal-footer">
                    <textarea id="select-result" class="form-control" readonly rows="3"></textarea>
                    <button id="select_ok" class="btn btn-group-right btn-success">적용</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/tinymce.min.js" integrity="sha512-RnlQJaTEHoOCt5dUTV0Oi0vOBMI9PjCU7m+VHoJ4xmhuUNcwnB5Iox1es+skLril1C3gHTLbeRepHs1RpSCLoQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/plugins/paste/plugin.min.js" integrity="sha512-PZXei/Vp39zScx6qEWBBdPVdDAOWd1A3l47MeZWH5l28LDw8MJWXPsok8GxHKNyULEPvu0UW+IK3ezzIPlVp2A==" crossorigin="anonymous"></script>
    <script>
        tinymce.init({
            selector: '.tiny',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak paste',
            toolbar_mode: 'floating',
            paste_data_images: true
        });
    </script>



<?php }
require_once("admin-footer.php");
