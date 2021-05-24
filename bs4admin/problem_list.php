<?php
require("admin-header.php");
require_once("../include/set_get_key.php");

if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'problem_editor']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

if(isset($OJ_LANG)){
  require_once("../lang/$OJ_LANG.php");
}
?>

<?php
$sql = "SELECT COUNT('problem_id') AS ids FROM `problem`";
$result = pdo_query($sql);
$row = $result[0];

$ids = intval($row['ids']);

$idsperpage = 50;
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
    $sql = "SELECT `problem_id`,`title`,`accepted`,`in_date`,`defunct` FROM `problem` WHERE (problem_id LIKE ?) OR (title LIKE ?) OR (description LIKE ?) OR (source LIKE ?)";
    $result = pdo_query($sql,$keyword,$keyword,$keyword,$keyword);
}else{
    $sql = "SELECT `problem_id`,`title`,`accepted`,`in_date`,`defunct` FROM `problem` ORDER BY `problem_id` DESC LIMIT $sid, $idsperpage";
    $result = pdo_query($sql);
}
?>


<div class='container'>
    <div class="text-center h3 "><?php echo $MSG_PROBLEM."-".$MSG_LIST?></div>
    <div class="clearfix">
        <form action="problem_list.php" class="form-search float-right">
            <div class="input-group ">
                <input type="text" name=keyword class="form-control " placeholder="<?php echo $MSG_PROBLEM_ID.', '.$MSG_TITLE.', '.$MSG_Description.', '.$MSG_SOURCE?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary  input-group-btn"><?php echo $MSG_SEARCH?></button>
                </div>

            </div>
        </form>
    </div>
    <div class="mt-3">
        <form method=post action=contest_add.php>
            <table id="tbl" class="table table-bordered table-hover">
                <tr class="text-center">
                    <td><input type=checkbox class="" onchange='$("input[type=checkbox]").prop("checked", this.checked)'></td>
                    <td ><?php echo $MSG_PROBLEM_ID?></td>
                    <td><?php echo $MSG_TITLE?></td>
                    <td><?php echo $MSG_AC?></td>
                    <?php
                    if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) ||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])){
                        if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) ||isset($_SESSION[$OJ_NAME.'_'.'problem_editor']))
                            echo "<td>상태</td><td>삭제</td>";
                        echo "<td>수정</td><td>채점문항</td>";
                    }
                    ?>
                    <td>작성일</td>
                </tr>
                <?php
                foreach($result as $row){
                    echo "<tr class='text-center'>";
                    echo "<td><input type=checkbox name='pid[]' value='".$row['problem_id']."'></td>";
                    echo "<td>".$row['problem_id']." </td>";
                    echo "<td class='text-left'><a href='../problem.php?id=".$row['problem_id']."'>".$row['title']."</a></td>";
                    echo "<td>".$row['accepted']."</td>";
                    if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])){
                        if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])){
                            echo "<td><a href=problem_df_change.php?id=".$row['problem_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey'].">".($row['defunct']=="N"?"<i class=\"fas fa-play-circle text-success\"></i>":"<i class=\"fas fa-stop-circle text-danger\"></i>")."</a><td>";
                            if($OJ_SAE||function_exists("system")){
                                ?>
                                <a href=# onclick='javascript:if(confirm("Delete?")) location.href="problem_del.php?id=<?php echo $row['problem_id']?>&getkey=<?php echo $_SESSION[$OJ_NAME.'_'.'getkey']?>"'><i class="fas fa-minus-circle text-danger"></i></a>
                                <?php
                            }
                        }
                        if(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'."p".$row['problem_id']]) ||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])){
                            echo "<td><a href=problem_edit.php?id=".$row['problem_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey']."><i class=\"fas fa-edit\"></i></a>";
                            echo "<td><a href='javascript:phpfm(".$row['problem_id'].");'><i class=\"fas fa-plus-circle\"></i></a>";
                        }
                    }
                    echo "<td><i class=\"fas fa-info-circle\" data-toggle='tooltip' data-placement='auto' title='{$row['in_date']}'></i></td>";
                    echo "</tr>";
                }
                ?>
                <tr id="checkTo">
                    <td colspan=11>선택된 파일을...
                        <input type=submit name='problem2contest' class="btn btn-sm btn-primary" value='새로운 콘테스트로'>
                        <input type=submit name='enable' value='사용하기' class="btn btn-sm btn-success" onclick='$("form").attr("action","problem_df_change.php")'>
                        <input type=submit name='disable' value='사용중지' class="btn btn-sm btn-danger" onclick='$("form").attr("action","problem_df_change.php")'>
                    </td>
                </tr>

            </table>
        </form>

    </div>

    <?php
    if(!(isset($_GET['keyword']) && $_GET['keyword']!=""))
    {
        echo "<div class=''>";
        echo "<ul class='pagination pagination-sm justify-content-center'>";
        echo "<li class='page-item'><a class='page-link' href='problem_list.php?page=".(strval(1))."'>&lt;&lt;</a></li>";
        echo "<li class='page-item'><a class='page-link' href='problem_list.php?page=".($page==1?strval(1):strval($page-1))."'>&lt;</a></li>";
        for($i=$spage; $i<=$epage; $i++){
            echo "<li class='".($page==$i?"active ":"")."page-item'><a class='page-link' title='go to page' href='problem_list.php?page=".$i.(isset($_GET['my'])?"&my":"")."'>".$i."</a></li>";
        }
        echo "<li class='page-item'><a class='page-link' href='problem_list.php?page=".($page==$pages?strval($page):strval($page+1))."'>&gt;</a></li>";
        echo "<li class='page-item'><a class='page-link' href='problem_list.php?page=".(strval($pages))."'>&gt;&gt;</a></li>";
        echo "</ul>";
        echo "</div>";
    }
    ?>

    <script>
        $(document).ready(function (){
            $('[data-toggle="tooltip"]').tooltip();
            var d = $("#checkTo").html();
            $("#tbl").prepend(d);
        });
        $(function () {

        })
    </script>
    <script>
    function phpfm(pid){
      //alert(pid);
      $.post("phpfm.php",{'frame':3,'pid':pid,'pass':''},function(data,status){
        if(status=="success"){
          document.location.href="phpfm.php?frame=3&pid="+pid;
        }
      });
    }
    </script>
</div>

<?php require("admin-footer.php");?>