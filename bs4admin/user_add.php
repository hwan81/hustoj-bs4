<?php require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

if(isset($OJ_LANG)){
  require_once("../lang/$OJ_LANG.php");
}
?>

<?php
if(isset($_POST['do'])){
  //echo $_POST['user_id'];
  require_once("../include/check_post_key.php");
  //echo $_POST['passwd'];
  require_once("../include/my_func.inc.php");

  $pieces = explode("\n", trim($_POST['ulist']));
  $pieces = preg_replace("/[^\x20-\x7e]/", " ", $pieces);  //!!important

  $ulist = "";
  if(count($pieces)>0 && strlen($pieces[0])>0){
    for($i=0; $i<count($pieces); $i++){
      $id_pw = explode(" ", trim($pieces[$i]));
      if(count($id_pw) != 2){
        echo "&nbsp;&nbsp;&nbsp;&nbsp;".$id_pw[0]." ... Error : Line format error!<br>";
        for($j=0; $j<count($id_pw); $j++)
        {
          $ulist = $ulist.$id_pw[$j]." ";
        }
        $ulist = trim($ulist)."\n";
      } else {
        $sql = "SELECT `user_id` FROM `users` WHERE `users`.`user_id` = ?";
        $result = pdo_query($sql, $id_pw[0]);
        $rows_cnt = count($result);

        if($rows_cnt == 1){
          echo "&nbsp;&nbsp;&nbsp;&nbsp;".$id_pw[0]." ... Error : User already exist!<br>";
          $ulist = $ulist.$id_pw[0]." ".$id_pw[1]."\n";
        } else {
          $passwd = pwGen($id_pw[1]);
          $sql = "INSERT INTO `users` (`user_id`, `password`, `reg_time`, `nick`) VALUES (?, ?, NOW(), ?);";
          pdo_query($sql, $id_pw[0], $passwd, $id_pw[0]);
          echo $id_pw[0]." is added!<br>";

          $ip = ($_SERVER['REMOTE_ADDR']);
          $sql="INSERT INTO `loginlog` VALUES(?,?,?,NOW())";
          pdo_query($sql, $id_pw[0], "user added", $ip);
        }
      }
    }
    echo "<br>Remained lines have error!<hr>";
  }
}
?>

<div class='container '>
    <div class="h3 text-center"><?php echo $MSG_USER."-".$MSG_ADD?></div>
    <form action=user_add.php method=post class="form-horizontal">
        <?php require_once("../include/set_post_key.php");?>
        <div class="card border-primary">
            <div class="card-header bg-primary text-light h4">
                <?php echo $MSG_USER_ID?> <?php echo $MSG_PASSWORD?>
            </div>
            <div class="card-body">
                 <textarea name='ulist' class="form-control" style="height:200px;" placeholder='userid1 password1<?php echo "\n"?>userid2 password2<?php echo "\n"?>userid3 password3<?php echo "\n"?>
        <?php echo $MSG_PRIVATE_USERS_ADD?><?php echo "\n"?>'><?php if(isset($ulist)){ echo $ulist;}?></textarea>
            </div>
        </div>

        <div class="mt-3 row">
            <div class="col-md-6"><button name="submit" type="reset" class="btn btn-outline-dark"><?php echo $MSG_RESET?></button></div>
            <div class="text-right col-md-6"> <button name="do" type="hidden" value="do" class="btn btn-primary" ><?php echo $MSG_SAVE?></button></div>
        </div>
    </form>
</div>


<?php require("admin-footer.php"); ?>