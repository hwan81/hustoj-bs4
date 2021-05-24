<?php
require_once("admin-header.php");
if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
?>

<?php
if(isset($_GET['cid'])){
  $cid = intval($_GET['cid']);
  $sql = "SELECT * FROM news WHERE `news_id`=?";
  $result = pdo_query($sql,$cid);
  $row = $result[0];
  $title = $row['title'];
  $content = $row['content'];
  $defunct = $row['defunct'];
}
?>

<div class="container">
    <div class="text-center h3">
        공지사항 등록
    </div>
  <form method=POST action=news_add.php>
      <div class="form-group ">
          <label class="h5"><?=$MSG_TITLE?></label>
          <input class="form-control" type=text name=title placeholder="<?=$MSG_TITLE?>" value='<?php echo isset($title)?$title."-Copy":""?>'>
      </div>
      <div class="form-group">
          <textarea  name=content>
            <?php echo isset($content)?$content:""?>
          </textarea>
      </div>
      <input type='submit' class="btn-block btn btn-primary" value='<?php echo $MSG_SAVE?>' name=submit>
      </center>
    </p>
    <?php require_once("../include/set_post_key.php");?>
  </form>
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
</div>

<?php require("admin-footer.php"); ?>