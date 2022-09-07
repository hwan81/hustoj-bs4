<?php
    require("template/".$OJ_TEMPLATE."/oj-header.php");
    ?>
<div class="container">
    <div class="h3">
        공지사항
    </div>
    <div class="accordion" id="accordionExample">
        <?php
        foreach ($res as $lt){
            $hid = "news-head-".$lt['news_id'];
            $bid = "news-body-".$lt['news_id'];
            ?>
            <div class="card ">
                <div class="card-header bg-white" id="<?=$hid?>">
                    <h2 class="mb-0">
                        <button class="btn btn-block text-left" type="button" data-toggle="collapse" data-target="#<?=$bid?>" aria-expanded="true" aria-controls="<?=$bid?>">
                            <?=$lt['news_id']?>.<?=$lt['title']?>
                            <span class="font-italic small"><?=$lt['time']?></span>
                        </button>
                    </h2>
                </div>
                <div id="<?=$bid?>" class="collapse" aria-labelledby="<?=$hid?>" data-parent="#accordionExample">
                    <div class="card-body bg-light">
                        <?=$lt['content']?>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div id="page" class="">
                <ul class="pagination pagination-sm">
                    <?php
                    $start_page = floor(($page-1) /10) * 10 + 1;
                    $last_page = $total_page <= $start_page + 9 ? $total_page : $start_page + 9;

                    if($start_page > 10){
                        $pre_page = $start_page - 10;
                        ?>
                        <li class='page-item'><a class='page-link' href="<?=$_SEVER['PHP_SELF']?>?page=<?=$pre_page?>&search_type=<?=$search_type?>&search_val=<?=$search_val?>"><i class="far fa-caret-square-left"></i></a></li>
                        <?php

                    }
                    for($i = $start_page ; $i <= $last_page ; $i++){
                        $active = $i == $page?"active":"";
                        ?>
                        <li class="page-item  <?=$active?>"><a href="<?=$_SEVER['PHP_SELF']?>?page=<?=$i?>&search_type=<?=$search_type?>&search_val=<?=$search_val?>" class="page-link"> <?=$i?></a></li>
                        <?php
                    }
                    if($total_page > $last_page){
                        $post_page = $start_page + 10;
                        ?>
                        <li class='page-item'><a class='page-link' href="<?=$_SEVER['PHP_SELF']?>?page=<?=$post_page?>&search_type=<?=$search_type?>&search_val=<?=$search_val?>"><i class="far fa-caret-square-right"></i></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 mr-auto">
            <div>
                <form method="get" action="<?=$_SEVER['PHP_SELF']?>" >
                    <div class="input-group">
                        <select name="search_type" class="col-4 form-control form-control-sm input-group-prepend">
                            <option value="news_id">공지번호</option>
                            <option value="title">제목</option>
                            <option value="content">내용</option>
                        </select>
                        <input class="form-control form-control-sm" type="text" name="search_val">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-sm" type="submit">검색</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    <?php
    if(isset(($_GET['search_val']))){
    ?>
        $("option[value=<?=$_GET['search_type']?>]").prop("selected",true);
        $("input[name=search_val]").val("<?=$_GET['search_val']?>");
    <?php
    }
    ?>
</script>



<?php require_once ("oj-footer.php"); ?>
