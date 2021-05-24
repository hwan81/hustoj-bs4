<?php include("template/$OJ_TEMPLATE/oj-header.php");?>

<div class="container">
        <div class='card '>
          <div class='card-header h3'>
            <h4>
              <?php echo $MSG_SOURCE?>
            </h4>
          </div>
          <div class='card-body content'>
            <?php
                $view_category = str_replace("label-","btn-sm btn-",$view_category);
                $view_category = str_replace("label","btn",$view_category);
                echo $view_category;
            ?>
          </div>
        </div>
      </div>

    </div> <!-- /container -->


<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>
