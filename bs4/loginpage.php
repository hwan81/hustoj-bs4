<?php include("template/$OJ_TEMPLATE/oj-header.php");?>

      <div class="container mt-5 ">
          <form id="login" action="login.php" method="post" role="form" class="form-horizontal" onSubmit="return jsMd5();"  >
              <div class="offset-md-2 col-md-8 col-sm-12">
                  <div class="card m-3 border-info">
                      <div class="card-header text-center bg-info text-light h4">
                          <?=$OJ_NAME?> Login
                      </div>
                      <div class="card-body">
                          <div class="form-group">
                              <label><?php echo $MSG_USER_ID?></label>
                              <input name="user_id" id="user_id" class="form-control form-control-lg border-info" placeholder="<?php echo $MSG_USER_ID?>" type="text" autofocus=autofocus required >
                          </div>
                          <div class="form-group">
                              <label><?php echo $MSG_PASSWORD?></label>
                              <input name="password" class="form-control form-control-lg border-info" placeholder="<?php echo $MSG_PASSWORD?>" type="password"  autocomplete="off" required >
                          </div>
                          <?php
                          if($OJ_VCODE){
                              ?>
                              <div class="form-group">
                                  <label><?php echo $MSG_VCODE?></label>
                                  <div class="input-group">
                                      <input name="vcode" class="form-control form-control-lg border-info" placeholder="<?php echo $MSG_VCODE?>" type="text"  autocomplete="off" required >
                                      <div class="input-group-append">
                                          <div class="input-group-text bg-light"><img id="vcode-img" alt="click to change" onclick="this.src='vcode.php?'+Math.random()" height="30px"></div>
                                      </div>
                                  </div>
                              </div>
                          <?php
                          }
                          ?>
                      </div>

                      <div class="form-group pl-3 pr-3 text-right">
                          <button name="submit" type="submit" class="form-control btn btn-lg btn-primary " ><?php echo $MSG_LOGIN; ?></button>
                          <a class="text-danger" href="lostpassword.php"><?php echo $MSG_LOST_PASSWORD; ?></a>
                      </div>


                  </div>
              </div>
          </form>
      </div>

	<script src="<?php echo $OJ_CDN_URL?>include/md5-min.js"></script>
	<script>
		function jsMd5(){
			if($("input[name=password]").val()=="") return false;
			$("input[name=password]").val(hex_md5($("input[name=password]").val()));
			return true;
		}
	</script>
    </div> <!-- /container -->
<?php if ($OJ_VCODE) { ?>
    <script>
        $(document).ready(function () {
            $("#vcode-img").attr("src", "vcode.php?" + Math.random());
            $("#input[name=user_id]").focus();
        })
    </script>
<?php } ?>
<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>