<?php 
require_once("../include/db_info.inc.php");
require_once ("../include/my_func.inc.php");
?>
<!doctype html>
<html lang="ko">
    <head>
        <title><?=$OJ_NAME?> adminitrator page</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--   <link rel=stylesheet href='../include/hoj.css' type='text/css'>-->
        <link rel="stylesheet" href="admin.css">
        <?php
        require_once("../template/$OJ_TEMPLATE/css.php");
        ?>
    </head>
    <body>

    <?php
    if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])||isset($_SESSION[$OJ_NAME.'_'.'password_setter']))){
        echo "<a href='../loginpage.php'>Please Login First!</a>";
        exit(1);
    }
    if(file_exists("../lang/$OJ_LANG.php")) require_once("../lang/$OJ_LANG.php");
    $MSG_SETMESSAGE = "한줄메시지";
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js" integrity="sha512-XKa9Hemdy1Ui3KSGgJdgMyYlUg1gM+QhL6cnlyTe2qzMCYm4nAZ1PsVerQzTTXzonUR+dmswHqgJPuwCq1MaAg==" crossorigin="anonymous"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js" integrity="sha512-wV7Yj1alIZDqZFCUQJy85VN+qvEIly93fIQAN7iqDFCPEucLCeNFz4r35FCo9s6WrpdDQPi80xbljXB8Bjtvcg==" crossorigin="anonymous"></script>
    <script src="admin.js" ></script>

    <nav class="navbar navbar-expand-lg navbar-dark text-light bg-danger fixed-top">
        <a class="navbar-brand" href="."><?=$OJ_NAME?> admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menu1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $MSG_SYSTEM ?>관리
                        </a>
                        <div class="dropdown-menu " aria-labelledby="menu1">
                            <a class="dropdown-item" href="setdb.php">db_config.php 파일 관리</a>
                            <a class="dropdown-item" href="setlang.php">lang/ko.php 파일 관리</a>
                            <a class='dropdown-item' href="rejudge.php"  title="<?php echo $MSG_HELP_REJUDGE?>"><?php echo $MSG_SYSTEM."-".$MSG_REJUDGE?></a>
                            <a class='dropdown-item' href="source_give.php"  title="<?php echo $MSG_HELP_GIVESOURCE?>"><?php echo $MSG_SYSTEM."-".$MSG_GIVESOURCE?></a>
                            <a class='dropdown-item' href="../online.php" ><?php echo $MSG_SYSTEM."-".$MSG_HELP_ONLINE?></a>
                            <a class='dropdown-item' href="update_db.php"  title="<?php echo $MSG_HELP_UPDATE_DATABASE?>"><?php echo $MSG_SYSTEM."-".$MSG_UPDATE_DATABASE?></a>
                        </div>
                    </li>
                <?php }?>
                <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notice-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $MSG_NEWS."-".$MSG_ADMIN ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="notice-menu">
                            <a class="dropdown-item" href="setmsg.php"  title="<?php echo $MSG_HELP_SETMESSAGE?>"><?php echo $MSG_NEWS."-".$MSG_SETMESSAGE?></a>
                            <a class="dropdown-item" href="news_list.php"  title="<?php echo $MSG_HELP_NEWS_LIST?>"><?php echo $MSG_NEWS."-".$MSG_LIST?></a>
                            <a class="dropdown-item" href="news_add_page.php"  title="<?php echo $MSG_HELP_ADD_NEWS?>"><?php echo $MSG_NEWS."-".$MSG_ADD?></a>
                        </div>
                    </li>
                 <?php }?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="user-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $MSG_USER."-".$MSG_ADMIN ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="user-menu">

                            <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset( $_SESSION[$OJ_NAME.'_'.'password_setter'])){?>
                                <a class="dropdown-item"  href="user_list.php"  title="<?php echo $MSG_HELP_USER_LIST?>"><?php echo $MSG_USER."-".$MSG_LIST?></a>
                            <?php }?>
                            <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
                                <a class="dropdown-item"  href="user_add.php"  title="<?php echo $MSG_HELP_USER_ADD?>"><?php echo $MSG_USER."-".$MSG_ADD?></a>
                            <?php }?>
                            <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset( $_SESSION[$OJ_NAME.'_'.'password_setter'])){?>
                                <a class="dropdown-item"  href="changepass.php"  title="<?php echo $MSG_HELP_SETPASSWORD?>"><?php echo $MSG_USER."-".$MSG_SETPASSWORD?></a>
                            <?php }?>
                            <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
                                <a class="dropdown-item"  href="privilege_list.php"  title="<?php echo $MSG_HELP_PRIVILEGE_LIST?>"><?php echo $MSG_USER."-".$MSG_PRIVILEGE."-".$MSG_LIST?></a>
                                <a class="dropdown-item"  href="privilege_add.php"  title="<?php echo $MSG_HELP_ADD_PRIVILEGE?>"><?php echo $MSG_USER."-".$MSG_PRIVILEGE."-".$MSG_ADD?></a>
                            <?php }?>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menu1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $MSG_PROBLEM."-".$MSG_ADMIN ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="menu1">
                            <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])) {?>
                                <a class='dropdown-item' href="problem_list.php"  title="<?php echo $MSG_HELP_PROBLEM_LIST?>"><?php echo $MSG_PROBLEM."-".$MSG_LIST?></a>
                            <?php }
                            if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])) {?>
                                <a class='dropdown-item' href="problem_add_page.php"  title="<?php echo html_entity_decode($MSG_HELP_ADD_PROBLEM)?>"><?php echo $MSG_PROBLEM."-".$MSG_ADD?></a>
                                <a class='dropdown-item' href="problem_import.php"  title="<?php echo $MSG_HELP_IMPORT_PROBLEM?>"><?php echo $MSG_PROBLEM."-".$MSG_IMPORT?></a>
                                <a class='dropdown-item' href="problem_export.php"  title="<?php echo $MSG_HELP_EXPORT_PROBLEM?>"><?php echo $MSG_PROBLEM."-".$MSG_EXPORT?></a>
                            <?php }?>
                            <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])&&!$OJ_SAE){?>
                                <a class='dropdown-item' href="problem_copy.php"  title="Create your own data">문제복사</a>
                                <a class='dropdown-item' href="problem_changeid.php"  title="Danger,Use it on your own risk">문제번호수정</a>
                            <?php }?>
                        </div>
                    </li>
                <?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])){?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menu1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $MSG_CONTEST."-".$MSG_ADMIN ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="menu1">
                            <a class='dropdown-item' href="contest_list.php"   title="<?php echo $MSG_HELP_CONTEST_LIST?>"><?php echo $MSG_CONTEST."-".$MSG_LIST?></a>
                            <a class='dropdown-item' href="contest_add.php"   title="<?php echo $MSG_HELP_ADD_CONTEST?>"><?php echo $MSG_CONTEST."-".$MSG_ADD?></a>
                            <a class='dropdown-item' href="user_set_ip.php"  title="<?php echo $MSG_SET_LOGIN_IP?>"><?php echo $MSG_CONTEST."-".$MSG_SET_LOGIN_IP?></a>
                            <a class='dropdown-item' href="team_generate.php"  title="<?php echo $MSG_HELP_TEAMGENERATOR?>"><?php echo $MSG_CONTEST."-".$MSG_TEAMGENERATOR?></a>
                            <a class='dropdown-item' href="team_generate2.php"  title="<?php echo $MSG_HELP_TEAMGENERATOR?>"><?php echo $MSG_CONTEST."-".$MSG_TEAMGENERATOR?></a>
                        </div>
                    </li>
                <?php }?>
            </ul>
            <div class="my-2 my-lg-0">
                <a class="btn btn-danger btn-outline-light" href="../" target="main"><?=$OJ_NAME?>바로가기</a>
                <a class="btn btn-danger btn-outline-light" href="../logout.php">Log-out</a>
            </div>
        </div>
    </nav>
    <div class="container-fluid pt-3 mt-5">