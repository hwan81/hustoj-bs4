<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
<div class="container">
    <div class="mb-1">
        <button type="button" onclick="write_mail();" class="btn btn-sm btn-primary">+ 메시지 쓰기</button>
    </div>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-hover" >
                <tr class="text-center">
                    <td>번호</td>
                    <td>제목</td>
                    <td>작성자</td>
                </tr>
                <tbody>
                <?php
                foreach($view_mail as $row){
                    $mid = str_replace("New","&nbsp;<span class='badge badge-danger'>N</span>",$row[0]);
                    $m_from = explode(":", $row[1])[0];
                    $m_title = explode(":", $row[1])[1];
                    ?>
                    <tr class='text-center mail-read' style="cursor: pointer">
                        <td><?=$mid?></td>
                        <td class="text-left">
                            <?=$m_title?>
                            <span class="small"><br><?=$row[2]?></span>
                        </td>
                        <td><?=$m_from?></td>
                    </tr>
                    <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <div class="card">
            <?php
            if($view_content){
                ?>
                <div class="card-header">
                    <?=htmlentities(str_replace("\n\r","\n",$view_title),ENT_QUOTES,"UTF-8")?><span class="font-italic small font-weight-bold">( <?=$from_user?> → <?=$to_user?> )</span>
                </div>
                <div class="card-body">
                    <pre><?=htmlentities(str_replace("\n\r","\n",$view_content),ENT_QUOTES,"UTF-8")?></pre>
<!--                </div>-->
<!---->
<!--                <table class="table">-->
<!--                    <tr>-->
<!--                        <td class=blue>$from_user:${to_user}[".htmlentities(str_replace("\n\r","\n",$view_title),ENT_QUOTES,"UTF-8")." ]</td>-->
<!--                    </tr>-->
<!--                    <tr><td><pre>". htmlentities(str_replace("\n\r","\n",$view_content),ENT_QUOTES,"UTF-8")."</pre>-->
<!--                        </td></tr>-->
<!--                </table></center>";-->
                <?php
            }
            ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="mail_write" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">메시지 보내기</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method=post action=mail.php>
            <div class="modal-body">
                    <table class="table">
                        <tr>
                            <td class="w-50">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            From<?php  /* echo htmlentities($from_user,ENT_QUOTES,"UTF-8")*/?>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" value="<?=$_SESSION[$OJ_NAME.'_user_id']?>" readonly>
                                </div>
                            </td>
                            <td class="">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            To
                                        </div>
                                    </div>
                                    <input name=to_user class="form-control" value="<?php if ($from_user==$_SESSION[$OJ_NAME.'_user_id']||$from_user=="") echo $to_user ;else echo $from_user;?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                           제 목
                                        </div>
                                    </div>
                                    <input name=title class="form-control" value="<?php echo $title?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <textarea name=content rows=10 class="form-control"></textarea>
                            </td>
                        </tr>

                    </table>

            </div>
            <div class="modal-footer">
                <input type=submit class="btn btn-primary btn-block" value=<?php echo $MSG_SUBMIT?>>
            </div>
            </form>
        </div>
    </div>
</div>



    </div> <!-- /container -->

<script>
    function write_mail(){
        $("#mail_write").modal();
    }
    $(document).ready(function (){
        $(".mail-read").on("click",function (){
            var vid=$(this).children("td").eq(0).text();
            location.href='mail.php?vid='+vid;
        });
        <?php
        if($_GET['to_user']) echo "write_mail();";
        ?>
    });

</script>
<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>