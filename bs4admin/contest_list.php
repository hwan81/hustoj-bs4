<?php
require("admin-header.php");
require_once("../include/set_get_key.php");

if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

if(isset($OJ_LANG)){
  require_once("../lang/$OJ_LANG.php");
}
?>


<?php
$sql = "SELECT COUNT('contest_id') AS ids FROM `contest`";
$result = pdo_query($sql);
$row = $result[0];

$ids = intval($row['ids']);

$idsperpage = 10;
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
  $sql = "SELECT `contest_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `contest` WHERE (title LIKE ?) OR (description LIKE ?) ORDER BY `contest_id` DESC";
  $result = pdo_query($sql,$keyword,$keyword);
}else{
  $sql = "SELECT `contest_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `contest` ORDER BY `contest_id` DESC LIMIT $sid, $idsperpage";
  $result = pdo_query($sql);
}
?>


<div class='container'>
    <div class="h3 text-center"><?php echo $MSG_CONTEST."-".$MSG_LIST?></div>
    <div class="row">
        <div class="col-md-3">
            <a href="contest_add.php" class="btn btn-success"><i class=" fas fa-plus"></i>&nbsp;대회등록</a>
        </div>
        <div class="col-md-9">
            <div class="clearfix">
                <form action="contest_list.php" class=" float-right">
                    <div class="input-group ">
                        <input type="text" name="keyword" class="form-control " placeholder="<?php echo $MSG_CONTEST_NAME.', '.$MSG_EXPLANATION?>">
                        <button type="submit" class="btn btn-primary input-group-append input-group-text">검색</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <table class="table table-bordered table-hover mt-3">
<tr class="text-center">
  <td>ID</td>
  <td>대회이름</td>
  <td>공개여부</td>
  <td>현재</td>
  <td>수정</td>
  <td>복사</td>
  <td>EXPORT</td>
  <td>기록</td>
  <td>수상한데</td>
</tr>
<?php
foreach($result as $row){
  echo "<tr class='text-center'>";
  echo "<td class='align-middle'>".$row['contest_id']."</td>";
  echo "<td class='text-left'><a href='../contest.php?cid=".$row['contest_id']."'>".$row['title']."</a><br><span class='small'>{$row['start_time']} ~ {$row['end_time']}</span></td>";
  $cid = $row['contest_id'];
  if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'."m$cid"])){
    echo "<td class='align-middle'><a href=contest_pr_change.php?cid=".$row['contest_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey'].">".($row['private']=="0"?"<span class='text-success'>공개</span>":"<span class='text-danger'>비공개<span>")."</a></td>";
    echo "<td class='align-middle'><a href=contest_df_change.php?cid=".$row['contest_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey'].">".($row['defunct']=="N"?"<span class='text-success'>가능</span>":"<span class='text-danger'>불가</span>")."</a></td>";
    echo "<td class='align-middle'><a href=contest_edit.php?cid=".$row['contest_id']."><i class='fas fa-edit'></i></a></td>";
    echo "<td class='align-middle'><a href=contest_add.php?cid=".$row['contest_id']."><i class='far fa-copy'></i></a></td>";
    if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])){
      echo "<td class='align-middle'><a href=\"problem_export_xml.php?cid=".$row['contest_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey']."\"><i class='fas fa-file-export'></i></a></td>";
    }else{
      echo "<td></td>";
    }
    echo "<td class='align-middle'> <a href=\"../export_contest_code.php?cid=".$row['contest_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey']."\"><i class='fas fa-history'></i></a></td>";
  }else{
    echo "<td colspan=5 align=right><a href=contest_add.php?cid=".$row['contest_id']."><i class='far fa-copy'></i></a><td>";
  }
  echo "<td class='align-middle'> <a href='suspect_list.php?cid=".$row['contest_id']."'><i class='fas fa-user-ninja'></i></a></td>";
  echo "</tr>";
}
?>
</table>
    <?php
if(!(isset($_GET['keyword']) && $_GET['keyword']!=""))
{
echo "<div>";
echo "<ul class='pagination pagination-sm justify-content-center'>";
echo "<li class='page-item'><a class='page-link' href='contest_list.php?page=".(strval(1))."'>&lt;&lt;</a></li>";
echo "<li class='page-item'><a class='page-link' href='contest_list.php?page=".($page==1?strval(1):strval($page-1))."'>&lt;</a></li>";
for($i=$spage; $i<=$epage; $i++){
echo "<li class='".($page==$i?"active ":"")."page-item'><a class='page-link' title='go to page' href='contest_list.php?page=".$i.(isset($_GET['my'])?"&my":"")."'>".$i."</a></li>";
}
echo "<li class='page-item'><a class='page-link' href='contest_list.php?page=".($page==$pages?strval($page):strval($page+1))."'>&gt;</a></li>";
echo "<li class='page-item'><a class='page-link' href='contest_list.php?page=".(strval($pages))."'>&gt;&gt;</a></li>";
echo "</ul>";
echo "</div>";
}
?>
</div>

<?php require("admin-footer.php");?>