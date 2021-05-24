<?php include("template/$OJ_TEMPLATE/oj-header.php");?>

<div class="container">
    <form action="register.php" onsubmit="return check();" method="post" role="form" class="form-horizontal">
        <div class="offset-md-3 col-md-6">
            <div class="card border-info">
                <div class="card-header h3 bg-info text-light text-center">
                    <?=$OJ_NAME?> Register
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class=" control-label"><?php echo $MSG_USER_ID?></label>
                        <div class=""><input id="user_id" name="user_id" class="form-control" placeholder="<?php echo $MSG_USER_ID?>*" type="text" required ></div>
                    </div>
                    <div class="form-group">
                        <label class=" control-label"><?php echo $MSG_NICK?></label>
                        <div class=""><input id="nick" name="nick" class="form-control" placeholder="<?php echo $MSG_NICK?>*" type="text" required ></div>
                    </div>
                    <div class="form-group">
                        <label class=" control-label"><?php echo $MSG_PASSWORD?></label>
                        <div class=""><input id="password" name="password" class="form-control" placeholder="<?php echo $MSG_PASSWORD?>*" type="password"  autocomplete="off" required ></div>
                    </div>
                    <div class="form-group">
                        <label class=" control-label"><?php echo $MSG_REPEAT_PASSWORD?></label>
                        <div class=""><input id="rptpassword" name="rptpassword" class="form-control" placeholder="<?php echo $MSG_REPEAT_PASSWORD?>*" type="password"  autocomplete="off" required ></div>
                    </div>
                    <div class="form-group">
                        <label class=" control-label"><?php echo $MSG_SCHOOL?></label>
                        <div class=""><input id="school" name="school" class="form-control" placeholder="<?php echo $MSG_SCHOOL?>" type="text"></div>
                    </div>
                    <div class="form-group">
                        <label class=" control-label"><?php echo $MSG_EMAIL?></label>
                        <div class=""><input id="email" name="email" class="form-control" placeholder="<?php echo $MSG_EMAIL?>" type="text"  required ></div>
                    </div>

                    <?php if($OJ_VCODE){?>
                        <div class="form-group">
                            <label class=" control-label"><?php echo $MSG_VCODE?></label>
                            <div class="col-sm-3">
                                <input name="vcode" class="form-control" placeholder="<?php echo $MSG_VCODE?>*" type="text">
                            </div>
                            <div class="">
                                <img alt="click to change" src="vcode.php" onclick="this.src='vcode.php?'+Math.random()" height="30px">*
                            </div>
                        </div>
                    <?php }?>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <button name="submit" type="reset" class="form-control btn btn-lg btn-outline-danger"><?php echo $MSG_RESET; ?></button>
                        </div>
                        <div class="col-md-8">
                            <button name="submit" type="submit" class="form-control btn btn-lg btn-primary"><?php echo $MSG_REGISTER; ?></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>



        
      </form>
     <script>
	function check(){
		if($("#user_id").val().length<3) {
			alert("<?php echo $MSG_WARNING_USER_ID_SHORT?>");
			$("#user_id").focus();
			return false;
		}
		if($("#password").val().length<6) {
			alert("<?php echo $MSG_WARNING_PASSWORD_SHORT?>");
			$("#password").focus();
			return false;
		}
		
		if($("#password").val()!=$("#rptpassword").val()) {
			alert("<?php echo $MSG_WARNING_REPEAT_PASSWORD_DIFF?>");
			$("#rptpassword").focus();
			return false;
		}
	}

     </script> 
    </div>
  </div> <!-- /container -->



   <script>
         $("input").attr("class","form-control");
   </script>
<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>