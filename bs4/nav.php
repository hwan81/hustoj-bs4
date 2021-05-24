<?php 
if(stripos($_SERVER['REQUEST_URI'],"template")!==false)exit();
	$url=explode("?", basename($_SERVER['REQUEST_URI']) )[0];
	$dir=basename(getcwd());
	if($dir=="discuss3") $path_fix="../";
	else $path_fix="";
 	if(isset($OJ_NEED_LOGIN)&&$OJ_NEED_LOGIN&&(
                  $url!='loginpage.php'&&
                  $url!='lostpassword.php'&&
                  $url!='lostpassword2.php'&&
                  $url!='registerpage.php'
                  ) && !isset($_SESSION[$OJ_NAME.'_'.'user_id'])){
 
           header("location:".$path_fix."loginpage.php");
           exit();
        }
	$_SESSION[$OJ_NAME.'_'.'profile_csrf']=rand();
	if($OJ_ONLINE){
		require_once($path_fix.'include/online.php');
		$on = new online();
	}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-<?=$OJ_MAIN_COLOR?> mb-3">
    <a class="navbar-brand" href="/">
        <?php echo $OJ_NAME?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <?php $ACTIVE="active"?>
            <?php if(!isset($OJ_ON_SITE_CONTEST_ID)){?>
                <li class="nav-item <?php if ($url=="faqs.php")  echo $ACTIVE;?>"><a class="nav-link pl-3 pr-3" href="<?php echo $path_fix?>faqs.php">
                        <i class="far fa-question-circle"></i> <?php echo $MSG_FAQ?></a></li>
            <?php }else{?>
            <?php }?>

            <?php if (isset($OJ_PRINTER)&& $OJ_PRINTER){ ?>
                <li <?php if ($url=="printer.php") echo " $ACTIVE";?>"><a class="nav-link pl-3 pr-3" href="<?php echo $path_fix?>printer.php"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> <?php echo $MSG_PRINTER?></a></li>
            <?php }?>
            <?php if(!isset($OJ_ON_SITE_CONTEST_ID)&&!isset($_GET['cid'])){?>
                <?php if (isset($OJ_BBS)&& $OJ_BBS){ ?>
                    <li <?php if ($dir=="discuss3") echo " $ACTIVE";?>><a class="nav-link pl-3 pr-3" href="<?php echo $path_fix?>bbs.php"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><?php echo $MSG_BBS?></a></li>
                <?php }?>
                <li class="nav-item <?php if ($url=="notice.php") echo " $ACTIVE";?>"><a class="nav-link pl-3 pr-3" href="<?php echo $path_fix?>notice.php" ><i class="far fa-comment-dots"></i> 공지사항</a></li>
                <li class="nav-item <?php if ($url=="problemset.php") echo " $ACTIVE";?>"><a class="nav-link pl-3 pr-3" href="<?php echo $path_fix?>problemset.php" ><i class="fas fa-laptop-code"></i> <?php echo $MSG_PROBLEMS?></a></li>
                <li class="nav-item <?php if ($url=="category.php") echo " $ACTIVE";?>"> <a class="nav-link pl-3 pr-3" href="<?php echo $path_fix?>category.php"><i class="fab fa-connectdevelop"></i></span> <?php echo $MSG_SOURCE?></a></li>
                <li class="nav-item <?php if ($url=="status.php") echo " $ACTIVE";?>"><a class="nav-link pl-3 pr-3" href="<?php echo $path_fix?>status.php"><i class="fas fa-robot"></i></span> <?php echo $MSG_STATUS?></a></li>
                <li class="nav-item <?php if ($url=="ranklist.php") echo " $ACTIVE";?>"><a class="nav-link pl-3 pr-3" href="<?php echo $path_fix?>ranklist.php"><i class="fas fa-medal"></i> <?php echo $MSG_RANKLIST?></a></li>
                <li class="nav-item <?php if ($url=="contest.php") echo " $ACTIVE";?>"><a class="nav-link pl-3 pr-3" href="<?php echo $path_fix?>contest.php"><i class="fas fa-award"></i> <?php echo $MSG_CONTEST?></a></li>
            <?php }else{?>
                <li class="nav-item <?php if ($url=="contest.php") echo " $ACTIVE";?>"><a class="nav-link pl-3 pr-3" href="<?php echo $path_fix?>contest.php" ><i class="fas fa-award"></i> <?php echo $MSG_CONTEST?></a></li>
            <?php }?>
            <?php if(isset($_GET['cid'])){ $cid=intval($_GET['cid']); } ?>
        </ul>
        <ul class="nav navbar-nav navbar-right mr-5">
            <li class="dropdown">
                <a href="#" class="nav-link pl-3 pr-3 dropdown-toggle" data-toggle="dropdown"><span id="profile">Login</span><span class="caret"></span></a>
                <div class="dropdown-menu" role="menu">
                    <script src="<?php echo $path_fix."template/$OJ_TEMPLATE/profile.php?profile_csrf=".$_SESSION[$OJ_NAME.'_'.'profile_csrf'];?>" ></script>
                    <!--<li><a href="../navbar-fixed-top/">Fixed top</a></li>-->
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link pl-3 pr-3 dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Language
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="setlang.php?lang=ko">한국어</a>
                    <a class="dropdown-item" href="setlang.php?lang=en">English</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
