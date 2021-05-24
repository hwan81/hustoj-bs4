<?php require("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}?>
<?php if(isset($_POST['do'])){
	require_once("../include/check_post_key.php");
	if (isset($_POST['rjpid'])){
		$rjpid=intval($_POST['rjpid']);
		if($rjpid == 0) {
		    echo "Rejudge Problem ID should not equal to 0";
		    exit(1);
		}
		$sql="UPDATE `solution` SET `result`=1 WHERE `problem_id`=? and problem_id>0";
		pdo_query($sql,$rjpid) ;
		$sql="delete from `sim` WHERE `s_id` in (select solution_id from solution where `problem_id`=?)";
		pdo_query($sql,$rjpid) ;
		$url="../status.php?problem_id=".$rjpid;
		echo "Rejudged Problem ".$rjpid;
		echo "<script>location.href='$url';</script>";
	}else if (isset($_POST['rjsid'])){
		$rjsid=intval($_POST['rjsid']);
		$sql="delete from `sim` WHERE `s_id`=?";
		pdo_query($sql,$rjsid) ;
		$sql="UPDATE `solution` SET `result`=1 WHERE `solution_id`=? and problem_id>0" ;
		pdo_query($sql,$rjsid) ;
		$sql="select contest_id from `solution` WHERE `solution_id`=? " ;
		$data=pdo_query($sql,$rjsid);
		$row=$data[0];
		$cid=intval($row[0]);
		if ($cid>0)
			$url="../status.php?cid=".$cid."&top=".($rjsid+1);
		else
			$url="../status.php?top=".($rjsid+1);
		echo "Rejudged Runid ".$rjsid;
		echo "<script>location.href='$url';</script>";
	}else if (isset($_POST['result'])){
		$result=intval($_POST['result']);
		$sql="UPDATE `solution` SET `result`=1 WHERE `result`=? and problem_id>0" ;
		pdo_query($sql,$result) ;
		$url="../status.php?jresult=1";
		echo "<script>location.href='$url';</script>";
	}else if (isset($_POST['rjcid'])){
		$rjcid=intval($_POST['rjcid']);
		$sql="UPDATE `solution` SET `result`=1 WHERE `contest_id`=? and problem_id>0";
		pdo_query($sql,$rjcid) ;
		$url="../status.php?cid=".($rjcid);
		echo "Rejudged Contest id :".$rjcid;
		echo "<script>location.href='$url';</script>";
	}
	echo str_repeat(" ",4096);
	flush();
	if($OJ_REDIS){
           $redis = new Redis();
           $redis->connect($OJ_REDISSERVER, $OJ_REDISPORT);
	   if(isset($OJ_REDISAUTH)) $redis->auth($OJ_REDISAUTH);
                $sql="select solution_id from solution where result=1 and problem_id>0";
                 $result=pdo_query($sql);
                 foreach($result as $row){
                        echo $row['solution_id']."\n";
                        $redis->lpush($OJ_REDISQNAME,$row['solution_id']);
                }
           $redis->close();     
        }

}
?>
<div class="container">
    <div class="col-md-6 offset-md-3">
        <div class="card border border-primary ">
            <div class="card-header h3 bg-primary text-light">
                재채점하기
            </div>
            <div class="card-body">
                <form action='rejudge.php' method=post>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                문제번호기준
                            </div>
                        </div>
                        <input type="text"  class="form-control" name='rjpid' placeholder="1001 등 하나의 문제번호">
                        <input type='hidden' name='do' value='do'>
                        <div class="input-group-append">
                            <div class="input-group-btn">
                                <input type=submit class="btn btn-primary" value="채점하기">
                            </div>
                        </div>
                        <?php require_once("../include/set_post_key.php");?>
                    </div>
                </form>

                <form action='rejudge.php' method=post class="mt-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                제출번호기준
                            </div>
                        </div>
                        <input type="text"  class="form-control" name='rjsid' placeholder="1001 등 하나의 번호">
                        <input type='hidden' name='do' value='do'>
                        <input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME.'_'.'postkey']?>">
                        <div class="input-group-append">
                            <div class="input-group-btn">
                                <input type=submit class="btn btn-primary" value="채점하기">
                            </div>
                        </div>
                        <?php require_once("../include/set_post_key.php");?>
                    </div>
                </form>

                <form action='rejudge.php' method=post class="mt-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                실행프로세스
                            </div>
                        </div>
                        <input type="text"  class="form-control" name='result' placeholder="1,2,3,4 등 하나의 번호">
                        <input type='hidden' name='do' value='do'>
                        <input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME.'_'.'postkey']?>">
                        <div class="input-group-append">
                            <div class="input-group-btn">
                                <input type=submit class="btn btn-primary" value="채점하기">
                            </div>
                        </div>
                        <?php require_once("../include/set_post_key.php");?>
                    </div>
                </form>

                <form action='rejudge.php' method=post class="mt-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                대회번호기준
                            </div>
                        </div>
                        <input type="text"  class="form-control" name='rjcid' placeholder="1001 등 하나의 번호">
                        <input type='hidden' name='do' value='do'>
                        <input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME.'_'.'postkey']?>">
                        <div class="input-group-append">
                            <div class="input-group-btn">
                                <input type=submit class="btn btn-primary" value="채점하기">
                            </div>
                        </div>
                        <?php require_once("../include/set_post_key.php");?>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
