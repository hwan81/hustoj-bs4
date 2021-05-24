<?php require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))) {
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}
?>


<div class="container ">
    <div class="h3 text-center">
        <?php echo $MSG_USER."-".$MSG_PRIVILEGE."-".$MSG_ADD?>
    </div>

<?php
if (isset($_POST['do'])) {
	require_once("../include/check_post_key.php");

	$user_id = $_POST['user_id'];
	$rightstr = $_POST['rightstr'];

	if (isset($_POST['contest']))
		$rightstr = "c$rightstr";

	if (isset($_POST['psv']))
		$rightstr = "s$rightstr";

	$sql = "insert into `privilege` values(?,?,'true','N')";
	$rows = pdo_query($sql,$user_id,$rightstr);
	echo "<center><h4 class='text-danger'>User ".$_POST['user_id']."'s Privilege Added! </h4></center>";
}
?>

    <div class="card mt-3">
        <div class="card-header bg-success text-lg text-light">
            <?php echo $MSG_HELP_ADD_PRIVILEGE?>
        </div>
        <div class="card-body">
            <form method="post" class="">
                <?php require_once("../include/set_post_key.php");?>

                <div class="row">
                    <div class="input-group col-md-6">
                        <div class="input input-group-prepend">
                            <div class="input-group-text">
                                <?php echo $MSG_USER_ID?>
                            </div>
                        </div>
                        <?php if(isset($_GET['uid'])) { ?>
                            <input name="user_id" class="form-control" value="<?php echo $_GET['uid']?>" type="text" required >
                        <?php } else if(isset($_POST['user_id'])) { ?>
                            <input name="user_id" class="form-control" value="<?php echo $_POST['user_id']?>" type="text" required >
                        <?php } else { ?>
                            <input name="user_id" class="form-control" placeholder="<?php echo $MSG_USER_ID."*"?>" type="text" required >
                        <?php } ?>
                    </div>
                    <div class="input-group col-md-6">
                        <div class="input input-group-prepend">
                            <div class="input-group-text">
                                <?php echo $MSG_PRIVILEGE_TYPE?>
                            </div>
                        </div>

                        <select class="form-control" name="rightstr">
                            <?php
                            $rightarray = array("administrator","problem_editor","source_browser","contest_creator","http_judge","password_setter","printer","balloon");
                            while (list($key, $val)=each($rightarray)) {
                                if (isset($rightstr) && ($rightstr == $val)) {
                                    echo '<option value="'.$val.'" selected>'.$val.'</option>';
                                } else {
                                    echo '<option value="'.$val.'">'.$val.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="text-right mt-3">
                        <input type='hidden' name='do' value='do'>
                        <button type="reset" class="btn btn-outline-dark col-md-1"><?php echo $MSG_RESET?></button>
                        <button type="submit" name="do" value="do" class="col-md-2 btn btn-primary" ><?php echo $MSG_SAVE?></button>
                </div>
            </form>
        </div>
    </div>


    <div class="card mt-3">
        <div class="card-header bg-info text-lg text-light">
            <?php echo $MSG_HELP_ADD_CONTEST_USER?>
        </div>
        <div class="card-body">
            <form method="post" class="">
                <?php require_once("../include/set_post_key.php");?>
                <div class="row">
                    <div class="input-group col-md-6">
                        <div class="input input-group-prepend">
                            <div class="input-group-text">
                                <?php echo $MSG_USER_ID?>
                            </div>
                        </div>
                        <?php if(isset($_GET['uid'])) { ?>
                            <input name="user_id" class="form-control" value="<?php echo $_GET['uid']?>" type="text" required >
                        <?php } else if(isset($_POST['user_id'])) { ?>
                            <input name="user_id" class="form-control" value="<?php echo $_POST['user_id']?>" type="text" required >
                        <?php } else { ?>
                            <input name="user_id" class="form-control" placeholder="<?php echo $MSG_USER_ID."*"?>" type="text" required >
                        <?php } ?>
                    </div>
                    <div class="input-group col-md-6">
                        <div class="input input-group-prepend">
                            <div class="input-group-text">
                                <?php echo $MSG_CONTEST_ID?>
                            </div>
                        </div>
                        <input name="rightstr" class="form-control" placeholder="<?php echo $MSG_CONTEST_ID."*"?>" type="text">
                    </div>
                </div>
                <div class="text-right mt-3">
                    <input type='hidden' name='do' value='do'>
                    <input type='hidden' name="postkey" value="<?php echo $_SESSION[$OJ_NAME.'_'.'postkey']?>">
                    <button type="reset" class="btn btn-outline-dark col-md-1"><?php echo $MSG_RESET?></button>
                    <button type="submit" name="contest" value="do" class="col-md-2 btn btn-primary" ><?php echo $MSG_SAVE?></button>
                </div>
            </form>
        </div>

<!--        <div class="form-group">-->
<!--            <label class="col-sm-offset-3 col-sm-3 control-label">--><?php //echo $MSG_CONTEST_ID?><!--</label>-->
<!--            <div class="col-sm-3"><input name="rightstr" class="form-control" placeholder="--><?php //echo $MSG_CONTEST_ID."*"?><!--" type="text"></div>-->
<!--        </div>-->
<!---->
<!--        <div class="form-group">-->
<!--            <div class="col-sm-offset-4 col-sm-2">-->
<!--                <input type='hidden' name='do' value='do'>-->
<!--                <button type="submit" name="contest" value="do" class="btn btn-default btn-block" >--><?php //echo $MSG_SAVE?><!--</button>-->
<!--                <input type=hidden name="postkey" value="--><?php //echo $_SESSION[$OJ_NAME.'_'.'postkey']?><!--">-->
<!--            </div>-->
<!--            <div class="col-sm-2">-->
<!--                <button type="reset" class="btn btn-default btn-block">--><?php //echo $MSG_RESET?><!--</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </form>-->
    </div>
    <div class="card mt-3">
        <div class="card-header bg-primary text-lg text-light">
            <?php echo $MSG_HELP_ADD_SOLUTION_VIEW?>
        </div>
        <div class="card-body">
            <form method="post" class="">
                <?php require_once("../include/set_post_key.php");?>
                <div class="row">
                    <div class="input-group col-md-6">
                        <div class="input input-group-prepend">
                            <div class="input-group-text">
                                <?php echo $MSG_USER_ID?>
                            </div>
                        </div>
                        <?php if(isset($_GET['uid'])) { ?>
                            <input name="user_id" class="form-control" value="<?php echo $_GET['uid']?>" type="text" required >
                        <?php } else if(isset($_POST['user_id'])) { ?>
                            <input name="user_id" class="form-control" value="<?php echo $_POST['user_id']?>" type="text" required >
                        <?php } else { ?>
                            <input name="user_id" class="form-control" placeholder="<?php echo $MSG_USER_ID."*"?>" type="text" required >
                        <?php } ?>
                    </div>
                    <div class="input-group col-md-6">
                        <div class="input input-group-prepend">
                            <div class="input-group-text">
                                <?php echo $MSG_PROBLEM_ID?>
                            </div>
                        </div>
                        <input name="rightstr" class="form-control" placeholder="<?php echo $MSG_PROBLEM_ID."*"?>" type="text">
                    </div>
                </div>
                <div class="text-right mt-3">
                    <input type='hidden' name='do' value='do'>
                    <input type='hidden' name="postkey" value="<?php echo $_SESSION[$OJ_NAME.'_'.'postkey']?>">
                    <button type="reset" class="btn btn-outline-dark col-md-1"><?php echo $MSG_RESET?></button>
                    <button type="submit" name="psv" value="do" class="col-md-2 btn btn-primary" ><?php echo $MSG_SAVE?></button>
                </div>
            </form>
        </div>
    </div>

</div>


<?php require("admin-footer.php"); ?>