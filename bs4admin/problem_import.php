<?php
require_once("admin-header.php");
if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'problem_editor']))) {
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
}

function writable($path){
	$ret=false;
	$fp=fopen($path."/testifwritable.tst","w");
	$ret=!($fp===false);
	fclose($fp);
	unlink($path."/testifwritable.tst");
	return $ret;
}

   $maxfile=min(ini_get("upload_max_filesize"),ini_get("post_max_size"));

?>
<div class="container">
    <div class="h3 text-center">문제데이터 가져오기</div>
    <div class="alert alert-warning">
        FPS 데이터를 가져 오려면 파일이 <span class="text-danger"><?php echo $maxfile?></span>보다 작은 지 확인하십시오.<br>
        또는 php.ini에서 upload_max_filesize 및 post_max_size을 설정하여 큰 파일 가져 오기 [10M +]에 실패하면 php.ini에서 [memory_limit] 설정을 확대 해보십시오.<br>
        파일을 찾으려면 find / etc -name php.ini를 사용하십시오.
    </div>
<?php 
    $show_form=true;
   if(!isset($OJ_SAE)||!$OJ_SAE){
	   if(!writable($OJ_DATA)){
		   echo " You need to add  $OJ_DATA into your open_basedir setting of php.ini,<br>
					or you need to execute:<br>
					   <b>chmod 775 -R $OJ_DATA && chgrp -R www-data $OJ_DATA</b><br>
					you can't use import function at this time.<br>"; 
		   if($OJ_LANG=="cn") echo "权限异常，请先去执行sudo chmod 775 -R $OJ_DATA <br> 和 sudo chgrp -R www-data $OJ_DATA <br>";
		   
		   $show_form=false;
	   }
	   if(!file_exists("../upload"))mkdir("../upload");
	   if(!writable("../upload")){
	   	 
		   echo "../upload is not writable, <b>chmod 770</b> to it.<br>";
		   $show_form=false;
	   }
	}	
	if($show_form){
?>

<form action='problem_import_xml.php' method=post enctype="multipart/form-data">
    <div class="card">
        <div class="card-header">
            문제파일 가져오기
        </div>
        <div class="card-body">
            <input type=file class="form-control-file" name=fps >
            <?php require_once("../include/set_post_key.php");?>
            <input type=submit class="form-control btn btn-primary" value='파일추가하기'>
        </div>
    </div>
</form>
<?php
   	}
?>

</div>


<?php require("admin-footer.php"); ?>