<?php
//  require_once("../include/db_info.inc.php");
//  require_once("../lang/$OJ_LANG.php");
require_once("../include/const.inc.php");
require_once("admin-header.php");

if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator']))){
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
}

if(isset($_POST['startdate'])){
    require_once("../include/check_post_key.php");


    $starttime = $_POST['startdate']." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
  $endtime = $_POST['enddate']." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";
  echo $starttime;
  echo $endtime;
 
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
  $langmask=0;
  foreach($lang as $t){
    $langmask += 1<<$t;
  } 

  $langmask = ((1<<count($language_ext))-1)&(~$langmask);
  echo $langmask;

  $cid=intval($_POST['cid']);

  if(!(isset($_SESSION[$OJ_NAME.'_'."m$cid"])||isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator']))) exit();

  $description = str_replace("<p>", "", $description); 
  $description = str_replace("</p>", "<br />", $description);
  $description = str_replace(",", "&#44;", $description);


  $sql = "UPDATE `contest` SET `title`=?,`description`=?,`start_time`=?,`end_time`=?,`private`=?,`langmask`=?,`password`=? WHERE `contest_id`=?";
  echo $sql;
  pdo_query($sql,$title,$description,$starttime,$endtime,$private,$langmask,$password,$cid);

  $sql = "DELETE FROM `contest_problem` WHERE `contest_id`=?";
  pdo_query($sql,$cid);
  $plist=trim($_POST['cproblem']);
  $pieces = explode(',', $plist);

  if(count($pieces)>0 && strlen($pieces[0])>0){
    $sql_1 = "INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`) VALUES (?,?,?)";
    for($i=0; $i<count($pieces); $i++){
      $pid=intval($pieces[$i]);
      pdo_query($sql_1,$cid,$pid,$i);
      $sql="UPDATE `contest_problem` SET `c_accepted`=(SELECT count(1) FROM `solution` WHERE `problem_id`=? and contest_id=? AND `result`=4) WHERE `problem_id`=? and contest_id=?";
      pdo_query($sql,$pid,$cid,$pid,$cid);
      $sql="UPDATE `contest_problem` SET `c_submit`=(SELECT count(1) FROM `solution` WHERE `problem_id`=? and contest_id=?) WHERE `problem_id`=? and contest_id=?";
      pdo_query($sql,$pid,$cid,$pid,$cid);
    }
  
    pdo_query("update solution set num=-1 where contest_id=?",$cid);
  
    $plist="";
    for($i=0; $i<count($pieces); $i++){
      if($plist) $plist.=",";
      $plist .= $pieces[$i];
      $sql_2 = "update solution set num=? where contest_id=? and problem_id=?;";
      pdo_query($sql_2,$i,$cid,$pieces[$i]);
    }

    $sql = "update `problem` set defunct='N' where `problem_id` in ($plist)";
    pdo_query($sql) ;
  }

  $sql = "DELETE FROM `privilege` WHERE `rightstr`=?";
  pdo_query($sql,"c$cid");
  $pieces = explode("\n", trim($_POST['ulist']));
  
  if(count($pieces)>0 && strlen($pieces[0])>0){
    $sql_1 = "INSERT INTO `privilege`(`user_id`,`rightstr`) VALUES (?,?)";
    for($i=0; $i<count($pieces); $i++){
      pdo_query($sql_1,trim($pieces[$i]),"c$cid") ;
    }
  }

  echo "<script>window.location.href=\"contest_list.php\";</script>";
  exit();
}else{
  $cid = intval($_GET['cid']);
  $sql = "SELECT * FROM `contest` WHERE `contest_id`=?";
  $result = pdo_query($sql,$cid);

  if(count($result)!=1){
    echo "No such Contest!";
    exit(0);
  }

  $row = $result[0];
  $starttime = $row['start_time'];
  $endtime = $row['end_time'];
  $private = $row['private'];
  $password = $row['password'];
  $langmask = $row['langmask'];
  $description = $row['description'];
  $title = htmlentities($row['title'],ENT_QUOTES,"UTF-8");

  $plist = "";
  $sql = "SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=? ORDER BY `num`";
  $result=pdo_query($sql,$cid);

  foreach($result as $row){
    if($plist) $plist .= ",";
    $plist.=$row[0];
  }

  $ulist = "";
  $sql = "SELECT `user_id` FROM `privilege` WHERE `rightstr`=? order by user_id";
  $result = pdo_query($sql,"c$cid");

  foreach($result as $row){
    if($ulist) $ulist .= "\n";
    $ulist .= $row[0];
  } 
}

?>


<!--new-->
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
        대회-수정
    </div>
    <form method=POST>
        <input type=hidden name='cid' value=<?php echo $cid?>>
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
                        <input class="form-control text-center" type=date name='startdate' value='<?php echo substr($starttime,0,10)?>'  >
                        <input class="form-control text-center col-2" type=text name=shour value="<?php echo substr($starttime,11,2)?>">
                        <div class="input-group-middle">
                            <div class="input-group-text">
                                시
                            </div>
                        </div>

                        <input class="form-control text-center col-2" type=text name=sminute value="<?php echo substr($starttime,14,2)?>"  >
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
                        <input class="form-control text-center" type=date name='enddate' value='<?php echo substr($endtime,0,10)?>'  >

                        <input class="form-control text-center col-2" type=text name=ehour value="<?php echo substr($endtime,11,2)?>">
                        <div class="input-group-middle">
                            <div class="input-group-text">
                                시
                            </div>
                        </div>
                        <input class="form-control text-center col-2" type=text name=eminute value="<?php echo substr($endtime,14,2)?>" >
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
                <input class="form-control" type=text name=password  value="<?php echo htmlentities($password,ENT_QUOTES,'utf-8')?>">
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


<!-- old --><!--
<div class="container">
  <form method=POST>

    <input type=hidden name='cid' value=<?php /*echo $cid*/?>>
    <p align=left>
      <?php /*echo "<h3>".$MSG_CONTEST."-".$MSG_TITLE."</h3>"*/?>
      <input class="input input-xxlarge" style="width:100%;" type=text name=title value="<?php /*echo $title*/?>"><br><br>
    </p>
    <p align=left>
      <?php /*echo $MSG_CONTEST.$MSG_Start*/?>:
      <input class=input-large type=date name='startdate' value='<?php /*echo substr($starttime,0,10)*/?>' size=4 >
      Hour: <input class=input-mini type=text name=shour size=2 value='<?php /*echo substr($starttime,11,2)*/?>'>&nbsp;
      Minute: <input class=input-mini type=text name=sminute value='<?php /*echo substr($starttime,14,2)*/?>' size=2 >
    </p>
    <p align=left>
      <?php /*echo $MSG_CONTEST.$MSG_End*/?>:
      <input class=input-large type=date name='enddate' value='<?php /*echo substr($endtime,0,10)*/?>' size=4 >
      Hour: <input class=input-mini type=text name=ehour size=2 value='<?php /*echo substr($endtime,11,2)*/?>'>&nbsp;
      Minute: <input class=input-mini type=text name=eminute value='<?php /*echo substr($endtime,14,2)*/?>' size=2 >
    </p>
    <br>
    <p align=left>
      <?php /*echo $MSG_CONTEST."-".$MSG_PROBLEM_ID*/?>
      <?php /*echo "( Add problemIDs with coma , )"*/?><br>
      <input class=input-xxlarge type=text style="width:100%" name=cproblem value='<?php /*echo $plist*/?>'>
    </p>
    <br>
    <p align=left>
      <?php /*echo "<h4>".$MSG_CONTEST."-".$MSG_Description."</h4>"*/?>
      <textarea class=kindeditor rows=13 name=description cols=80>
        <?php /*echo htmlentities($description,ENT_QUOTES,'UTF-8')*/?>
      </textarea>
      <br>
      <table width="100%">
        <tr>
          <td rowspan=2>
            <p aligh=left>
              <?php /*echo $MSG_CONTEST."-".$MSG_LANG*/?>
              <?php /*echo "( Add PLs with Ctrl+click )"*/?><br>
              <?php /*echo $MSG_PLS_ADD*/?><br>
              <select name="lang[]" multiple="multiple" style="height:220px">
              <?php
/*              $lang_count = count($language_ext);
              $lang = (~((int)$langmask))&((1<<$lang_count)-1);

              if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];
              else $lastlang = 0;

              for($i=0; $i<$lang_count; $i++){
                echo "<option value=$i ".( $lang&(1<<$i)?"selected":"").">".$language_name[$i]."</option>";
              }
              */?>
              </select>
            </p>
          </td>

          <td height="10px">
            <p align=left>
              <?php /*echo $MSG_CONTEST."-".$MSG_Public*/?>:
              <select name=private style="width:150px;">
                <option value=0 <?php /*echo $private=='0'?'selected=selected':''*/?>><?php /*echo $MSG_Public*/?></option>
                <option value=1 <?php /*echo $private=='1'?'selected=selected':''*/?>><?php /*echo $MSG_Private*/?></option>
              </select>
              <?php /*echo $MSG_CONTEST."-".$MSG_PASSWORD*/?>:
              <input type=text name=password style="width:150px;" value='<?php /*echo htmlentities($password,ENT_QUOTES,'utf-8')*/?>'>
            </p>
          </td>
        </tr>
        <tr>
          <td height="*">
            <p align=left>
              <?php /*echo $MSG_CONTEST."-".$MSG_USER*/?>
              <?php /*echo "( Add private contest's userIDs with newline &#47;n )"*/?>
              <br>
              <textarea name='ulist' rows='10' style='width:100%;' placeholder='user1<?php /*echo "\n"*/?>user2<?php /*echo "\n"*/?>user3<?php /*echo "\n"*/?>
              <?php /*echo $MSG_PRIVATE_USERS_ADD*/?><?php /*echo "\n"*/?>'><?php /*if(isset($ulist)){ echo $ulist;}*/?></textarea>
            </p>
          </td>
        </tr>
      </table>

      <div align=center>
        <?php /*require_once("../include/set_post_key.php");*/?>
        <input type=submit value='<?php /*echo $MSG_SAVE*/?>' name=submit> <input type=reset value=Reset name=reset>
      </div>
    </p>
  </form>
</div>
</body>
-->

<?php require ("admin-footer.php") ?>