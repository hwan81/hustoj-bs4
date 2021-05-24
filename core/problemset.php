<?php

$OJ_CACHE_SHARE = false;
$cache_time = 0;

require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
//require_once('./include/cache_start.php');
require_once('./include/memcache.php');
require_once('./include/setlang.php');

/* problemset.php remake. writer : Leejunghwan bigdipper81@nate.com
 * template으로 전달되는 변수
 * $view_probelmset.php , $page,
 * 검색조건 변경  문제아이디, 제목, 출처 중 하나 선택
 * 콘테스트중인 문제는 검색되면 안됨
 * 사용에 대한 책임은 본이에게 있습니다.
 */

$page = isset($_GET['page'])?$_GET['page']:1;
$page_cnt = 20;



$search_type = $_GET['search_type'];
$search_val = $_GET['search_val'];
if($_GET['search']){
    $search_type = source;
    $search_val = $_GET['search'];
}

if(trim($search_val) != ""){
    if($search_type == "problem_id"){
        $search_query = "problem_id = ".$search_val;
    }else{
        $search_query = $search_type." like '%".htmlspecialchars($search_val)."%'";
    }
}

$query_list = "ifnull(result,0), p.problem_id as problem_id , title, source, accepted, submit, solved ";

//속도를 위해 전체 카운트와 페이지검색을 각각 수행
if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])){  //for admin
    // 관리자 검색조건:
    //defunct와 상관없이 보임
    //자신의 문제 풀이데이터 반영



    $where = isset($search_query)? "where {$search_query}" : "";

    //total count
    $total_count_query = "select count(*) as total_count 
from
     (select problem_id, title, source, accepted, submit, solved 
     from `problem` 
     {$where}) as p 
         left join  
         (select problem_id, ifnull(min(result),0) as result  from solution where user_id='{$_SESSION[$OJ_NAME.'_'.'user_id']}' and contest_id = '0' group by problem_id) as u 
             on p.problem_id = u.problem_id
";

    ////////////////////////////////////////
    //total count
    $result_total = mysql_query_cache($total_count_query);
    $total_count = $result_total[0][0];

    //pagination
    $total_page = ceil($total_count / $page_cnt);
    $now = ($page-1) * $page_cnt;

    //출력리스트
    $result = mysql_query_cache($query);
    $view_problemset = $result;
    /////////////////////////////////////////


    //print list
    $query = "select {$query_list}  
from
     (select problem_id, title, source, accepted, submit, solved 
     from `problem` 
     {$where}) as p 
         left join  
         (select problem_id, ifnull(min(result),0) as result  from solution where user_id='{$_SESSION[$OJ_NAME.'_'.'user_id']}' and contest_id = '0' group by problem_id) as u 
             on p.problem_id = u.problem_id

order by p.problem_id asc limit $now, $page_cnt
";
    //출력리스트
    $result = mysql_query_cache($query);
    $view_problemset = $result;

}else if(isset(($_SESSION[$OJ_NAME.'_'.'user_id']))){ //for user
    //로그인 사용자 검색조건
    //사용중인 것만, 대회중인것은 제외
    //자신의 평가 데이터 출력

    $search_query = isset(($search_query))?" and ".$search_query:"";
    $total_count_query = "select count(*) as cnt
from
     (select problem_id, title, source, accepted, submit, solved 
     from problem 
     where defunct = 'N' {$search_query}) as p 
         left join  
         (select problem_id, ifnull(min(result),0) as result  from solution where user_id='{$_SESSION[$OJ_NAME.'_'.'user_id']}' and contest_id = '0' group by problem_id) as u 
             on p.problem_id = u.problem_id
where p.problem_id NOT
    IN
      (select distinct(problem_id) 
      from contest_problem 
      where contest_id IN 
            (select distinct(contest_id) 
            from contest 
            where defunct = 'N') 
      ) 
";

    ////////////////////////////////////////
    //total count
    $result_total = mysql_query_cache($total_count_query);
    $total_count = $result_total[0][0];

    //pagination
    $total_page = ceil($total_count / $page_cnt);
    $now = ($page-1) * $page_cnt;

    /////////////////////////////////////////


    $query = "select {$query_list}
from
     (select problem_id, title, source, accepted, submit, solved 
     from problem 
     where defunct = 'N' {$search_query}) as p 
         left join  
         (select problem_id, ifnull(min(result),0) as result  from solution where user_id='{$_SESSION[$OJ_NAME.'_'.'user_id']}' and contest_id = '0' group by problem_id) as u 
             on p.problem_id = u.problem_id
where p.problem_id NOT
    IN
      (select distinct(problem_id) 
      from contest_problem 
      where contest_id IN 
            (select distinct(contest_id) 
            from contest 
            where defunct = 'N') 
      ) 
order by p.problem_id asc limit $now, $page_cnt
";


    //출력리스트
    $result = mysql_query_cache($query);
    $view_problemset = $result;
}else{
    //비로그인 사용자 검색조건
    //사용중인 것만, 대회중인것은 제외
    $search_query = isset(($search_query))?" and ".$search_query:"";
    $total_count_query = "select count(*) as cnt
from
     (select problem_id, title, source, accepted, submit, solved 
     from problem 
     where defunct = 'N' {$search_query}) as p             
where p.problem_id NOT
    IN
      (select distinct(problem_id) 
      from contest_problem 
      where contest_id IN 
            (select distinct(contest_id) 
            from contest 
            where defunct = 'N') 
      ) 
";

    ////////////////////////////////////////
    //total count
    $result_total = mysql_query_cache($total_count_query);

    $total_count = $result_total[0][0];

    //pagination
    $total_page = ceil($total_count / $page_cnt);
    $now = ($page-1) * $page_cnt;

    /////////////////////////////////////////


    $query = "select 'X', p.problem_id, title, source, accepted, submit, solved 
from
     (select  problem_id, title, source, accepted, submit, solved 
     from problem 
     where defunct = 'N' {$search_query}) as p
where p.problem_id NOT
    IN
      (select distinct(problem_id) 
      from contest_problem 
      where contest_id IN 
            (select distinct(contest_id) 
            from contest 
            where defunct = 'N') 
      ) 
order by p.problem_id asc limit $now, $page_cnt
";


    //출력리스트
    $result = mysql_query_cache($query);
    //print_r($result[0]);
    $view_problemset = $result;
}

//출처는 템플릿에서 처리





require("template/".$OJ_TEMPLATE."/problemset.php");

if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');