<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
    <div class="container">
        <h3><?php echo $MSG_SERVER_TIME?> <span id=nowdate></span></h3>
        <div class="table-responsive">
            <table class='table table-bordered table-hover'>
                <thead>
                    <tr class="table-danger text-center">
                        <td><?php echo $MSG_CONTEST_ID?></td>
                        <td><?php echo $MSG_CONTEST_NAME?></td>
<!--                        <td>--><?php //echo $MSG_CONTEST_STATUS?><!--</td>-->
                        <td><?php echo $MSG_CONTEST_OPEN?></td>
                        <td><?php echo $MSG_CONTEST_CREATOR?></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach($view_contest as $row){
                    $i=0;
                    ?>
                        <tr class="text-center ">
                            <td class="align-middle"><?=$row[0]?></td>
                            <td class="text-left">
                                <?=$row[1]?><br>
                                <span class="badge badge-info">상태</span>
                                <span class="font-weight-light">
                                    <?=$row[2]?>
                                </span>

                            </td>

                            <td class="align-middle">
                                <?=$row[4]?><?=$row[5]?></td>
                            <td class="align-middle"><?=$row[6]?></td>
                        </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-4">
                <table class="" >
                    <tr>
                        <td>
                            <form class=form-inline method=post action=contest.php>
                                <div class="input-group">
                                    <input class="form-control" name=keyword value="<?php if(isset($_POST['keyword'])) echo htmlentities($_POST['keyword'],ENT_QUOTES,"UTF-8")?>" placeholder="<?php echo $MSG_CONTEST_NAME?>">
                                    <button class="btn btn-primary input-group-btn" type=submit><?php echo $MSG_SEARCH?></button>
                                </div>

                            </form>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-8 text-end ">
                <div class="d-flex justify-content-md-end">
                    <ul class="pagination pagination-sm">
                        <li class="page-item"><a class="page-link" href="contest.php?page=1">&lt;&lt;</a></li>
                        <?php
                        if(!isset($page)) $page=1;
                        $page=intval($page);
                        $section=8;
                        $start=$page>$section?$page-$section:1;
                        $end=$page+$section>$view_total_page?$view_total_page:$page+$section;
                        for ($i=$start;$i<=$end;$i++){
                            echo "<li class='".($page==$i?" active ":"")."page-item'><a class='page-link' title='go to page' href='contest.php?page=".$i.(isset($_GET['my'])?"&my":"")."'>".$i."</a></li>";
                        }
                        ?>
                        <li class="page-item"><a class="page-link" href="contest.php?page=<?php echo $view_total_page?>">&gt;&gt;</a></li>
                    </ul>
                </div>

            </div>
        </div>










    </div>
  </div> <!-- /container -->

  <script>
  var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();

  function clock()
  {
    var x,h,m,s,n,xingqi,y,mon,d;
    var x = new Date(new Date().getTime()+diff);
    y = x.getYear()+1900;
    if (y>3000) y-=1900;
    mon = x.getMonth()+1;
    d = x.getDate();
    xingqi = x.getDay();
    h=x.getHours();
    m=x.getMinutes();
    s=x.getSeconds();
    n=y+"-"+(mon>=10?mon:"0"+mon)+"-"+(d>=10?d:"0"+d)+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
    //alert(n);
    document.getElementById('nowdate').innerHTML=n;
    setTimeout("clock()",1000);
  }

  clock();
  </script>
<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>