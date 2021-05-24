<?php
require_once("admin-header.php");
if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}


if(isset($_POST['do'])){
  require_once("../include/check_post_key.php");

  $fp = fopen($OJ_SAE?"saestor://web/msg.txt":"msg.txt","w");
  $msg = $_POST['msg'];

  $msg = str_replace("<p>", "", $msg);
  $msg = str_replace("</p>", "<br />", $msg);
  $msg = str_replace(",", "&#44;", $msg);

  if(get_magic_quotes_gpc()){
    $title = stripslashes($title);
  }

  $msg = RemoveXSS($msg);
  fputs($fp,$msg);
  fclose($fp);
  echo "<h4 class='text-danger'>메시지가 수정되었습니다. ".date('Y-m-d h:i:s')."</h4>";
}

$msg = file_get_contents($OJ_SAE?"saestor://web/msg.txt":"msg.txt");

?>

<div class="container">
    <div class="h3 text-center"><?php echo $MSG_NEWS."-".$MSG_SETMESSAGE ?></div>
  <form action='setmsg.php' method='post'>
    <textarea id="msg" name='msg' class="form-control" style="height: 200px"><?php echo $msg?></textarea>
    <input type='hidden' name='do' value='do'>
    <input type='submit' class="form-control btn btn-primary mt-3" value='<?php echo $MSG_SAVE?>'>
    <?php require_once("../include/set_post_key.php");?>
  </form>
    <div class="alert alert-danger mt-3">
        메시지는 admin디렉토리에 msg.txt파일을 사용합니다. 메시지가 변경되지 않는다면 해당파일의 퍼미션을 707로 수정해주시기 바랍니다.
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/tinymce.min.js" integrity="sha512-RnlQJaTEHoOCt5dUTV0Oi0vOBMI9PjCU7m+VHoJ4xmhuUNcwnB5Iox1es+skLril1C3gHTLbeRepHs1RpSCLoQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/plugins/paste/plugin.min.js" integrity="sha512-PZXei/Vp39zScx6qEWBBdPVdDAOWd1A3l47MeZWH5l28LDw8MJWXPsok8GxHKNyULEPvu0UW+IK3ezzIPlVp2A==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/plugins/autosave/plugin.min.js" integrity="sha512-a8bkwDB4e00PxanMDmdok8MyjH7tGqyKvclbYa4fFASBJO37HKiS9ijyeh3ELRpZq6rHKM9CFXE3TYGQ9HWjMw==" crossorigin="anonymous"></script>
<script src="ko_KR.js"></script>
<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak paste autosave table',
        toolbar_mode: 'floating',
        language:'ko_KR',
        paste_data_images: true,
        height: 400
    });
</script>
<?php require_once('admin-footer.php'); ?>
