<?php require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'password_setter']) )){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}

if(isset($OJ_LANG)){
  require_once("../lang/$OJ_LANG.php");
}
?>




<div class='container'>
    <div class="h3 text-center"><?php echo $MSG_USER."-".$MSG_SETPASSWORD?></div>
<?php
if(isset($_POST['do'])){
	//echo $_POST['user_id'];
	require_once("../include/check_post_key.php");
	//echo $_POST['passwd'];
	require_once("../include/my_func.inc.php");
	
	$user_id = $_POST['user_id'];
  $passwd = $_POST['passwd'];

  if(get_magic_quotes_gpc()){
		$user_id = stripslashes($user_id);
		$passwd = stripslashes($passwd);
	}

	$passwd = pwGen($passwd);
	$sql = "update `users` set `password`=? where `user_id`=?  and user_id not in( select user_id from privilege where rightstr='administrator')";
	
	if(pdo_query($sql,$passwd,$user_id) == 1)
		echo "<center><h4 class='text-danger'>User ".$_POST['user_id']."'s Password Changed!</h4></center>";
  else
  	echo "<center><h4 class='text-danger'>There is No such User ".$_POST['user_id']."! or User ".$_POST['user_id']." is administrator!</h4></center>";
}
?>
<form action=changepass.php method=post class="">

    <div class="offset-md-3 col-md-6 card">
        <div class="card-body">
            <div class="form-group">
                <label class=""><?php echo $MSG_USER_ID?></label>
                <?php if(isset($_GET['uid'])) { ?>
                    <div class=""><input name="user_id" class="form-control" value="<?php echo $_GET['uid']?>" type="text" required ></div>
                <?php } else if(isset($_POST['user_id'])) { ?>
                    <div class=""><input name="user_id" class="form-control" value="<?php echo $_POST['user_id']?>" type="text" required ></div>
                <?php } else { ?>
                    <div class=""><input name="user_id" class="form-control" placeholder="<?php echo $MSG_USER_ID."*"?>" type="text" required ></div>
                <?php } ?>
            </div>

            <div class="form-group">
                <label class=""><?php echo $MSG_PASSWORD?></label>
                <div class=""><input name="passwd" class="form-control" placeholder="<?php echo $MSG_PASSWORD."*"?>" type="password"  autocomplete="off" required ></div>
            </div>
            <div class="mt-5 row">
                <?php require_once("../include/set_post_key.php");?>
                <div class="col-md-6"><button name="submit" type="reset" class="btn btn-outline-dark"><?php echo $MSG_RESET?></button></div>
                <div class="text-right col-md-6"> <button name="do" type="hidden" value="do" class="btn btn-primary" ><?php echo $MSG_SAVE?></button></div>
            </div>
        </div>
    </div>
</form>

</div>



<?php require("admin-footer.php"); ?>