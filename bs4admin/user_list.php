<?php
require("admin-header.php");
require_once("../include/set_get_key.php");

if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'password_setter']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

if(isset($OJ_LANG)){
  require_once("../lang/$OJ_LANG.php");
}

$sql = "SELECT COUNT('user_id') AS ids FROM `users`";
$result = pdo_query($sql);
$row = $result[0];

$ids = intval($row['ids']);

$idsperpage = 25;
$pages = intval(ceil($ids/$idsperpage));

if(isset($_GET['page'])){ $page = intval($_GET['page']);}
else{ $page = 1;}

$pagesperframe = 5;
$frame = intval(ceil($page/$pagesperframe));

$spage = ($frame-1)*$pagesperframe+1;
$epage = min($spage+$pagesperframe-1, $pages);

$sid = ($page-1)*$idsperpage;

$sql = "";
if(isset($_GET['keyword']) && $_GET['keyword']!=""){
    $keyword = $_GET['keyword'];
    $keyword = "%$keyword%";
    $sql = "SELECT `user_id`,`nick`,`accesstime`,`reg_time`,`ip`,`school`,`defunct` FROM `users` WHERE (user_id LIKE ?) OR (nick LIKE ?) OR (school LIKE ?) ORDER BY `user_id` DESC";
    $result = pdo_query($sql,$keyword,$keyword,$keyword);
}else{
    $sql = "SELECT `user_id`,`nick`,`accesstime`,`reg_time`,`ip`,`school`,`defunct` FROM `users` ORDER BY `reg_time` DESC LIMIT $sid, $idsperpage";
    $result = pdo_query($sql);
}
?>


<div class='container'>
    <div class="h3 text-center">
        <h3><?php echo $MSG_USER."-".$MSG_LIST?></h3>
    </div>
    <div class="clearfix">
        <form action=user_list.php class=" float-right">
            <div class="input-group ">
                <input type="text" name=keyword class="form-control "  placeholder="<?php echo $MSG_USER_ID.', '.$MSG_NICK.', '.$MSG_SCHOOL?>">
                <button type="submit" class="btn btn-primary input-group-append input-group-text">검색</button>
            </div>
        </form>
    </div>
    <div class="mt-3">
        <table class="table table-bordered table-hover" >
            <tr class="text-center table-primary">
                <td>아이디</td>
                <td>닉네임</td>
                <td>학교명</td>
                <td>로그인기록</td>
                <td>등록일</td>
                <td>사용여부</td>
                <td>비밀번호</td>
                <td>권한</td>
            </tr>
            <?php
            foreach($result as $row){
                echo "<tr class='text-center'>";
                echo "<td><a href='../userinfo.php?user=".$row['user_id']."'>".$row['user_id']."</a></td>";
                echo "<td>".$row['nick']."</td>";
                echo "<td>".$row['school']."</td>";
                echo "<td >".$row['accesstime']."</td>";
                echo "<td >".$row['reg_time'].")"."</td>";
                if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
                    echo "<td><a href=user_df_change.php?cid=".$row['user_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey'].">".($row['defunct']=="N"?"<span class=green>Available</span>":"<span class=red>Locked</span>")."</a></td>";
                }
                else {
                    echo "<td>".($row['defunct']=="N"?"<span>Available</span>":"<span>Locked</span>")."</td>";
                }
                echo "<td><a href=changepass.php?uid=".$row['user_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey'].">"."Reset"."</a></td>";
                echo "<td><a href=privilege_add.php?uid=".$row['user_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey'].">"."Add"."</a></td>";
                echo "</tr>";
            } ?>
        </table>
    </div>

<?php
if(!(isset($_GET['keyword']) && $_GET['keyword']!=""))
{
  echo "<div class=''>";
  echo "<ul class='pagination justify-content-center pagination-sm'>";
  echo "<li class='page-item'><a class='page-link' href='user_list.php?page=".(strval(1))."'>&lt;&lt;</a></li>";
  echo "<li class='page-item'><a  class='page-link' href='user_list.php?page=".($page==1?strval(1):strval($page-1))."'>&lt;</a></li>";
  for($i=$spage; $i<=$epage; $i++){
    echo "<li class='".($page==$i?"active ":"")."page-item'><a  class='page-link' title='go to page' href='user_list.php?page=".$i.(isset($_GET['my'])?"&my":"")."'>".$i."</a></li>";
  }
  echo "<li class='page-item'><a  class='page-link' href='user_list.php?page=".($page==$pages?strval($page):strval($page+1))."'>&gt;</a></li>";
  echo "<li class='page-item'><a  class='page-link' href='user_list.php?page=".(strval($pages))."'>&gt;&gt;</a></li>";
  echo "</ul>";

  echo "</div>";
}
?>

</div>
<?php require("admin-footer.php"); ?>