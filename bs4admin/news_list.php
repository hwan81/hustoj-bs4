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
    $sql = "SELECT COUNT('news_id') AS ids FROM `news`";
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
        $sql = "SELECT `news_id`,`user_id`,`title`,`time`,`defunct` FROM `news` WHERE (title LIKE ?) OR (content LIKE ?) ORDER BY `news_id` DESC";
        $result = pdo_query($sql,$keyword,$keyword);
    }else{
        $sql = "SELECT `news_id`,`user_id`,`title`,`time`,`defunct` FROM `news` ORDER BY `news_id` DESC LIMIT $sid, $idsperpage";
        $result = pdo_query($sql);
    }
?>


<div class="container ">
    <div class="text-center h3 ">
        공지사항 리스트
    </div>
    <div class="clearfix">
        <form action=news_list.php class="form-search float-right">
            <div class="input-group ">
                <input type="text" name=keyword class="form-control " placeholder="<?php echo $MSG_TITLE.', '.$MSG_CONTENTS?>">
                <button type="submit" class="btn btn-primary input-group-append input-group-text"><?php echo $MSG_SEARCH?></button>
            </div>
        </form>
    </div>
    <div class="clearfix mt-3">
        <table class="table-bordered table table-hover" >
            <caption class="text-right"><?php echo $MSG_HELP_ADD_FAQS?></caption>
            <tr class="table-primary text-center">
                <td>ID</td>
                <td class="w-50">제목</td>
                <td>작성일</td>
                <td>상태</td>
                <td>복사하기</td>
            </tr>
            <?php
            foreach($result as $row){
                echo "<tr class='text-center'>";
                    echo "<td >".$row['news_id']."</td>";
                    echo "<td class='text-left'><a href='news_edit.php?id=".$row['news_id']."'>".$row['title']."</a>"."</td>";
                    echo "<td>".$row['time']."</td>";
                    echo "<td><a href=news_df_change.php?id=".$row['news_id']."&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey'].">".($row['defunct']=="N"?"<span class=text-success>On</span>":"<span class=text-danger>Off</span>")."</a>"."</td>";
                    echo "<td><a href=news_add_page.php?cid=".$row['news_id'].">Copy</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    <?php
    if(!(isset($_GET['keyword']) && $_GET['keyword']!=""))
    {

        echo "<div class=''>";
        echo "<ul class='pagination pagination-sm justify-content-center'>";
        echo "<li class='page-item'><a class='page-link' href='news_list.php?page=".(strval(1))."'>&lt;&lt;</a></li>";
        echo "<li class='page-item'><a class='page-link' href='news_list.php?page=".($page==1?strval(1):strval($page-1))."'>&lt;</a></li>";
        for($i=$spage; $i<=$epage; $i++){
            echo "<li class='".($page==$i?"active ":"")."page-item'><a class='page-link' title='go to page' href='news_list.php?page=".$i.(isset($_GET['my'])?"&my":"")."'>".$i."</a></li>";
        }
        echo "<li class='page-item'><a class='page-link' href='news_list.php?page=".($page==$pages?strval($page):strval($page+1))."'>&gt;</a></li>";
        echo "<li class='page-item'><a class='page-link' href='news_list.php?page=".(strval($pages))."'>&gt;&gt;</a></li>";
        echo "</ul>";
        echo "</div>";

    }
    ?>
</div>

<?php require("admin-footer.php"); ?>
