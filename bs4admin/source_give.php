<?php require_once("admin-header.php");
if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}?>
<?php if(isset($_POST['do'])){
	require_once("../include/check_post_key.php");
	$from=$_POST['from'];
	$to=$_POST['to'];
	$start=intval($_POST['start']);
	$end=intval($_POST['end']);
	$sql="update `solution` set `user_id`=? where `user_id`=? and problem_id>=? and problem_id<=? and result=4";
	//echo $sql;
	echo pdo_query($sql,$to,$from,$start,$end)." source file given!";
	
}
?>
<div class="container">
    <form action='source_give.php' method=post>
        <div class="card">
            <div class="card-header h3 ">
                채점코드 소유자 변경
            </div>
            <div class="card-body">
                <div class="input-group">
                    <div class="input-group-prepend input-group-text">
                        원본 사용자ID
                    </div>
                    <input type=text class="form-control" name="from" value="" placeholder="원 소유자 ID">
                    <div class="input-group-prepend input-group-text">
                       새로운 사용자 ID
                    </div>
                    <input type=text class="form-control" name="to" value="" placeholder="새로운 소유자 ID">
                </div>
                <div class="input-group mt-3">
                    <div class="input-group-prepend input-group-text">
                        시작 문제ID
                    </div>
                    <input type=text class="form-control" name="start" placeholder="시작 문제ID">
                    <div class="input-group-prepend input-group-text">
                        마지막 문제ID
                    </div>
                    <input type=text class="form-control" name="end" placeholder="마지막 문제ID">
                </div>
                <div class="input-group mt-3">
                    <input type='hidden' name='do' value='do'>

                    <?php require_once("../include/set_post_key.php");?>
                    <input type=submit class="btn btn-primary btn-block" value='변경하기'>
                </div>
            </div>
        </div>
    </form>
</div>
<?php require_once("admin-footer.php");