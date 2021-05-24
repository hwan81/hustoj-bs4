<?php
require_once("admin-header.php");
if(!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}


if(isset($_POST['news_id'])){
  require_once("../include/check_post_key.php");

  $title = $_POST['title'];
  $content = $_POST['content'];

  $content = str_replace("<p>", "", $content);
  $content = str_replace("</p>", "<br />", $content);
  $content = str_replace(",", "&#44;", $content);

  $user_id = $_SESSION[$OJ_NAME.'_'.'user_id'];
  $news_id = intval($_POST['news_id']);

  if(get_magic_quotes_gpc()){
    $title = stripslashes($title);
    $content = stripslashes($content);
  }



  $title = RemoveXSS($title);
  $content = RemoveXSS($content);

  $sql = "UPDATE `news` SET `title`=?,`time`=now(),`content`=?,user_id=? WHERE `news_id`=?";
  //echo $sql;
  pdo_query($sql,$title,$content,$user_id,$news_id) ;

  echo "<script>location.href='news_list.php'</script>";
  exit();
}else{
  $news_id = intval($_GET['id']);
  $sql = "SELECT * FROM `news` WHERE `news_id`=?";
  $result = pdo_query($sql,$news_id);
  if(count($result)!=1){
    echo "No such News!";
    exit(0);
  }

  $row = $result[0];

  $title = htmlentities($row['title'],ENT_QUOTES,"UTF-8");
  $content = $row['content'];
}
?>
<div class="container">
    <div class="h3 text-center">
        <?=$MSG_NEWS?> 수정
    </div>
    <form method=POST action=news_edit.php>
        <input type=hidden name='news_id' value=<?php echo $news_id?>>
        <div class="form-group ">
            <label class="h5"><?php echo $MSG_TITLE?></label>
            <input class="form-control" type=text name=title placeholder="제목" value='<?php echo $title?>'>
        </div>
        <div class="form-group ">
            <textarea class='tiny' name=content>
                <?php echo htmlentities($content,ENT_QUOTES,"UTF-8")?>
            </textarea>
        </div>
        <?php require_once("../include/set_post_key.php");?>
        <div class="form-group">
            <input class="btn btn-block btn-primary" type=submit value='<?php echo $MSG_SAVE?>' name=submit>
        </div>
  </form>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/tinymce.min.js" integrity="sha512-RnlQJaTEHoOCt5dUTV0Oi0vOBMI9PjCU7m+VHoJ4xmhuUNcwnB5Iox1es+skLril1C3gHTLbeRepHs1RpSCLoQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/plugins/paste/plugin.min.js" integrity="sha512-PZXei/Vp39zScx6qEWBBdPVdDAOWd1A3l47MeZWH5l28LDw8MJWXPsok8GxHKNyULEPvu0UW+IK3ezzIPlVp2A==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/plugins/autosave/plugin.min.js" integrity="sha512-a8bkwDB4e00PxanMDmdok8MyjH7tGqyKvclbYa4fFASBJO37HKiS9ijyeh3ELRpZq6rHKM9CFXE3TYGQ9HWjMw==" crossorigin="anonymous"></script>
    <script src="ko_KR.js"></script>
    <script>
        tinymce.init({
            selector: '.tiny',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak paste autosave table',
            toolbar_mode: 'floating',
            language:'ko_KR',
            paste_data_images: true,
            height: 400
        });
    </script>
<?php require_once("admin-footer.php"); ?>