<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("content-type:application/javascript");

if(isset($_SERVER['HTTP_REFERER'])) $dir=basename(dirname($_SERVER['HTTP_REFERER']));
else $dir="";

if($dir=="discuss3") $path_fix="../";
else $path_fix="";

require_once("../../include/db_info.inc.php");

if(isset($_SESSION[$OJ_NAME.'_'.'profile_csrf'])&&$_GET['profile_csrf']!=$_SESSION[$OJ_NAME.'_'.'profile_csrf']){
//    echo "<!--".$_SESSION[$OJ_NAME.'_'.'profile_csrf']."-->";
//  exit();
}else{
  $_SESSION[$OJ_NAME.'_'.'profile_csrf']="";
}
if(isset($OJ_LANG)){
  require_once("../../lang/$OJ_LANG.php");
}else{
  require_once("../../lang/ko.php");
}

function checkmail(){
  global $OJ_NAME;

  $sql="SELECT count(1) FROM `mail` WHERE new_mail=1 AND `to_user`=?";
  $result=pdo_query($sql,$_SESSION[$OJ_NAME.'_'.'user_id']);

  if(!$result) return false;

  $row=$result[0];
  //$retmsg="<span class='text-danger'><i class='far fa-envelope'></i> 메시지(".$row[0].")</span>";
		
  return $row[0];  //msg count return
}

$profile='';

if(isset($_SESSION[$OJ_NAME.'_'.'user_id'])){
  $sid=$_SESSION[$OJ_NAME.'_'.'user_id'];
    $new_sid = $sid;

  $profile.= "<a class='dropdown-item' href=".$path_fix."modifypage.php>$MSG_REG_INFO</a><a class='dropdown-item' href='".$path_fix."userinfo.php?user=$sid'><span id=red>$MSG_USERINFO</span></a>";

  if((isset($OJ_EXAM_CONTEST_ID)&&$OJ_EXAM_CONTEST_ID>0)||
     (isset($OJ_ON_SITE_CONTEST_ID)&&$OJ_ON_SITE_CONTEST_ID>0)||
     (isset($OJ_MAIL)&&!$OJ_MAIL)){
  }
  else{
    $mail=checkmail();
    if($mail) $profile.= "<a class='dropdown-item' class='' href=".$path_fix."mail.php><span class='text-danger'><i class='far fa-envelope'></i>메시지(".$mail.")</span></a>";
    if($mail >0) $new_sid = "<i class='far fa-envelope'></i>&nbsp;".$new_sid;
  }

  $profile.="<a class='dropdown-item' href='".$path_fix."status.php?user_id=$sid'><span id=red>$MSG_MY_SUBMISSIONS</span></a>";
  $profile.="<a class='dropdown-item' href='".$path_fix."contest.php?my'><span id=red>$MSG_MY_CONTESTS</span></a>";
  if(
    (isset($OJ_EXAM_CONTEST_ID)&&$OJ_EXAM_CONTEST_ID>0)||
    (isset($OJ_ON_SITE_CONTEST_ID)&&$OJ_ON_SITE_CONTEST_ID>0)||
    (isset($OJ_SHARE_CODE)&&!$OJ_SHARE_CODE)
  ){}else{
    $profile.= "<a class='dropdown-item' href='./sharecodelist.php'>代码分享</a>";
  }
  $profile.= "<a class='dropdown-item' href=".$path_fix."logout.php>$MSG_LOGOUT</a>";

}else{
  if($OJ_WEIBO_AUTH){
    $profile.= "<a class='dropdown-item' href=".$path_fix."login_weibo.php>$MSG_LOGIN(WEIBO)</a>";
  }
  if($OJ_RR_AUTH){
    $profile.= "<a class='dropdown-item' href=".$path_fix."login_renren.php>$MSG_LOGIN(RENREN)</a>";
  }
  if ($OJ_QQ_AUTH){
    $profile.= "<a class='dropdown-item' href=".$path_fix."login_qq.php>$MSG_LOGIN(QQ)</a>";
  }
    
  $profile.= "<a class='dropdown-item' href=".$path_fix."loginpage.php>$MSG_LOGIN</a>";

  if($OJ_LOGIN_MOD=="hustoj"){
    if(isset($OJ_REGISTER)&&!$OJ_REGISTER){
    }else{
      $profile.= "<a class='dropdown-item' href=".$path_fix."registerpage.php>$MSG_REGISTER</a>";
    }
  }
}

if(isset($_SESSION[$OJ_NAME.'_'.'balloon'])){
  $profile.= "<a class='dropdown-item' href='".$path_fix."balloon.php'>$MSG_BALLOON</a>";
}

if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])||isset($_SESSION[$OJ_NAME.'_'.'password_setter'])){
  $profile.= "<a class='dropdown-item' href=".$OJ_TEMPLATE."admin/>$MSG_ADMIN</a>";
}

//$profile.="</ul></li>";
?>
document.write("<?php echo ( $profile);?>");
document.getElementById("profile").innerHTML="<?php echo  isset($sid)?$new_sid:$MSG_LOGIN  ?>";
