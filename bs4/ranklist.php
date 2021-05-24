<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <form class="input-group" action=ranklist.php>
                        <div class="input-group-prepend">
                            <div class="input-group-text"><?=$MSG_USER?></div>
                        </div>
                        <input class="form-control" name='prefix' value="<?php echo htmlentities(isset($_GET['prefix'])?$_GET['prefix']:"",ENT_QUOTES,"utf-8") ?>" placeholder="<?php echo $MSG_USER?>">
                        <div class="input-group-append">
                            <button class="form-control btn-primary" type='submit'><?php echo $MSG_SEARCH?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6 ">
                <ul class="pagination float-right" id="pagenation" >
                    <li class="page-item">
                        <a class="page-link page" href=ranklist.php?scope=d>일별</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href=ranklist.php?scope=w>주별</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href=ranklist.php?scope=m>월별</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href=ranklist.php?scope=y>년간</a>
                    </li>
                </ul>
                <?php
                if($_GET['scope'] == "d") $active = 0;
                else if($_GET['scope'] == "w") $active = 1;
                else if($_GET['scope'] == "m") $active = 2;
                else $active = 3;
                ?>
                <script>
                    $("#pagenation > li").eq(<?=$active?>).addClass("active");
                </script>
            </div>
        </div>

      <table class="table table-bordered">
        <thead class="table-info">
          <tr class=''>
            <td class='text-center'><?php echo $MSG_Number?></td>
            <td class='text-center'><?php echo $MSG_USER?></td>
            <td class='text-center'><?php echo $MSG_NICK?></td>
            <td class='text-center'><?php echo $MSG_SOVLED?></td>
            <td class='text-center'><?php echo $MSG_SUBMIT?></td>
            <td class='text-center'><?php echo $MSG_RATIO?></td>
          </tr>
        </thead>
        <tbody>
          <?php
          $cnt=0;
          foreach($view_rank as $row){
            if ($cnt)
              echo "<tr class='oddrow'>";
            else
              echo "<tr class='evenrow'>";

            $i = 0;
            foreach($row as $table_cell){
              echo "<td class='text-center'>";
              echo $table_cell;
              echo "</td>";
              $i++;
            }
            echo "</tr>";
            $cnt=1-$cnt;
          }
          ?>
        </tbody>
      </table>
      <?php

      $qs="";
      if(isset($_GET['prefix'])){
        $qs.="&prefix=".htmlentities($_GET['prefix'],ENT_QUOTES,"utf-8");
      }
      if(isset($scope)){
        $qs.="&scope=".htmlentities($scope,ENT_QUOTES,"utf-8");
      }
      ?>
      <div class="text-center">
                <ul class="pagination justify-content-center">
                    <?php
                    for($i = 0; $i <$view_total ; $i += $page_size) {
                        echo "<li class='page-item '><a class='page-link' href='./ranklist.php?start=" . strval ( $i ).$qs. "'>";
                        echo strval ( $i + 1 );
                        echo "-";
                        echo strval ( $i + $page_size );
                        echo "</a></li>";
                        if ($i % 250 == 200)
                            echo "<br>";
                    }
                    ?>
                </ul>
            </div>
  </div> <!-- /container -->
<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>