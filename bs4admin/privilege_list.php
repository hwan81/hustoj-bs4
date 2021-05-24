<?php
require("admin-header.php");
require_once("../include/set_get_key.php");

if(!isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

if(isset($OJ_LANG)){
  require_once("../lang/$OJ_LANG.php");
}
?>


<?php
$sql = "SELECT COUNT(*) AS ids FROM privilege WHERE rightstr IN ('administrator','source_browser','contest_creator','http_judge','problem_editor','password_setter','printer','balloon') ORDER BY user_id, rightstr";
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
    $sql = "SELECT * FROM privilege WHERE (user_id LIKE ?) OR (rightstr LIKE ?) ORDER BY user_id, rightstr";
    $result = pdo_query($sql,$keyword,$keyword);
}else{
    $sql = "SELECT * FROM privilege WHERE rightstr IN ('administrator','source_browser','contest_creator','http_judge','problem_editor','password_setter','printer','balloon') ORDER BY user_id, rightstr LIMIT $sid, $idsperpage";
    $result = pdo_query($sql);
}
?>


<div class='container '>
    <div class="h3 text-center"><?php echo $MSG_USER."-".$MSG_PRIVILEGE."-".$MSG_LIST?></div>
    <form action=privilege_list.php class="">
        <div class="offset-md-7 col-md-5 input-group">
            <input type="text" name=keyword class="form-control" placeholder="<?php echo $MSG_USER_ID.', '.$MSG_PRIVILEGE?>">
            <div class="input-group-append">
                <button type="submit" class="btn input-group-btn btn-primary"><?php echo $MSG_SEARCH?></button>
            </div>

        </div>
    </form>
    <div>
        <table class="table table-bordered table-sm table-hover mt-3">
            <tr class="text-center table-primary">
                <td>ID</td>
                <td>PRIVILEGE</td>
                <td>REMOVE</td>
            </tr>
            <?php
            foreach($result as $row){
                echo "<tr class='text-center'>";
                echo "<td>".$row['user_id']."</td>";
                echo "<td>".$row['rightstr']."</td>";
                echo "<td><a href='privilege_delete.php?uid=".htmlentities($row['user_id'],ENT_QUOTES,"UTF-8")."&rightstr={$row['rightstr']}&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey']."'>Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

<?php
if(!(isset($_GET['keyword']) && $_GET['keyword']!=""))
{
  echo "<div style=''>";
  echo "<ul class='justify-content-center pagination pagination-sm'>";
  echo "<li class='page-item'><a class='page-link' href='privilege_list.php?page=".(strval(1))."'>&lt;&lt;</a></li>";
  echo "<li class='page-item'><a class='page-link' href='privilege_list.php?page=".($page==1?strval(1):strval($page-1))."'>&lt;</a></li>";
  for($i=$spage; $i<=$epage; $i++){
    echo "<li class='".($page==$i?"active ":"")."page-item'><a class='page-link' title='go to page' href='privilege_list.php?page=".$i.(isset($_GET['my'])?"&my":"")."'>".$i."</a></li>";
  }
  echo "<li class='page-item'><a class='page-link' href='privilege_list.php?page=".($page==$pages?strval($page):strval($page+1))."'>&gt;</a></li>";
  echo "<li class='page-item'><a class='page-link' href='privilege_list.php?page=".(strval($pages))."'>&gt;&gt;</a></li>";
  echo "</ul>";
  echo "</div>";
}
?>

</div>
<?php require("admin-footer.php"); ?>