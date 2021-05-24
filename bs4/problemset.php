<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
	<div class="container">
		<!-- Main component for a primary marketing message or call to action -->
        <div class="row">
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
                                <option value="problem_id">문제번호</option>
                                <option value="title">제목</option>
                                <option value="source">출처</option>
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
        <div class="text-right">
             <span>
                        <?=$page?> / <?=$total_page?> pages (Total : <?=$total_count?>건)
                    </span>
        </div>


        <div class="table-responsive-lg">
            <table id='problemset' class='table table-bordered table-hover border-success'>
                <thead>
                    <tr class='table-success text-center'>
                        <th>C</th>
                        <th>
                            <span class="d-none d-sm-inline"><?php echo $MSG_PROBLEM_ID?></span>
                            <span class="d-inline d-sm-none">PID</span>
                        </th>
                        <th>
                            <?php echo $MSG_TITLE?>
                        </th>
                        <th class="d-md-table-cell d-none">
                            <?php echo $MSG_SOURCE?>
                        </th>
                        <th class="d-sm-table-cell d-none">
                            <?php echo $MSG_SOVLED?>
                        </th>
                        <th class="d-sm-table-cell d-none">
                            <?php echo $MSG_SUBMIT?>
                        </th>
                    </tr>
                </thead>
                <tbody class="">
                    <?php
                    foreach ( $view_problemset as $row ) {
                        if($row[0] == "4" ) $row[0] = "<i class='fas fa-check-circle text-success'></i>";
                        else if($row[0] == "6") $row[0] = "<i class='fas fa-times-circle text-danger'></i>";
                        else $row[0] = "";

                        //출처처리
                        $cate = explode(" ",$row[3]);
                        $c = 1;
                        $cateList = "";
                        for($i = 0 ; $i < sizeof($cate); $i++){
                            $cateList = $cateList."<a class='p-1 badge border border-{$color_theme[$c++]}' href=\"{$_SERVER['PHP_SELF']}?search_type=source&search_val={$cate[$i]}\">#{$cate[$i]}</a>&nbsp;";
                            $c %= sizeof($color_theme);
                        }
                        
                        echo "<tr class='text-center'>";
                            echo "<td >$row[0]</td>";
                            echo "<td>$row[1]</td>";
                            echo "<td class='text-left'>
                                        <a href='problem.php?id={$row[1]}'>$row[2]</a>
                                        <span class='d-md-none d-block text-left'>$cateList</span>
                                        </td>";
                            echo "<td class='d-none d-md-table-cell text-center'>$cateList  </td>";
                            echo "<td class=\"d-sm-table-cell d-none\"><a href='status.php?problem_id={$row[1]}&jresult=4'>$row[4]</a></td>";
                            echo "<td class=\"d-sm-table-cell d-none\"><a href='status.php?problem_id={$row[1]}'>$row[5]</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
		</div>
    </div>
	</div>
	<!-- /container -->
	<script type="text/javascript">
		$( document ).ready( function () {
		    $( "#problemset" ).after( $( "#page" ).prop( "outerHTML" ) );
		} );
        <?php
            if(isset(($_GET['search_val']))){
                ?>
            $("option[value=<?=$_GET['search_type']?>]").prop("selected",true);
            $("input[name=search_val]").val("<?=$_GET['search_val']?>");
        <?php
        }
        ?>
	</script>
<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>