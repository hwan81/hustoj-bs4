<?php
    $OJ_CACHE_SHARE=false;
    $cache_time=30;
    require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
    require_once("./include/my_func.inc.php");
    require_once('./include/setlang.php');


    $page_cnt = 10;
    $page = !isset($_GET['page'])?1:$_GET['page'];
    $start = ($page - 1) * $page_cnt;

    $search_type = $_GET['search_type'];
    $search_val = $_GET['search_val'];
    if(trim($search_val) != ""){
        if($search_type == "news_id"){
            $search_query = "news_id = ".$search_val;
        }else{
            $search_query = $search_type." like '%".htmlspecialchars($search_val)."%'";
        }
        $search_query = "and {$search_query}";
    }

    $where = "where `defunct` = 'N' {$search_query}";
    $sql = "select count(`news_id`) as cnt from `news` {$where}";


    ////////////////////////////////////////
    //total count
    $total_cnt = pdo_query($sql)[0][0];

    //pagination
    $total_page = ceil($total_cnt / $page_cnt);

    $sql = "select `news_id`, `user_id`, `title`, `content`, `time`, `importance` from `news` {$where} order by `time` desc limit {$start}, {$page_cnt}";
    $res = pdo_query($sql);
    /////////////////////////////////////////


    require("template/".$OJ_TEMPLATE."/notice.php");
