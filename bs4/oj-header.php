<?php
$OJ_ADMIN = "bs4admin";
$OJ_MAIN_COLOR = "primary";  //info success danger warning primary
if (file_exists("./{$OJ_ADMIN}/msg.txt"))
    $view_marquee_msg = file_get_contents($OJ_SAE ? "saestor://web/msg.txt" : "./{$OJ_ADMIN}/msg.txt");
if (file_exists("../{$OJ_ADMIN}/msg.txt"))
    $view_marquee_msg = file_get_contents($OJ_SAE ? "saestor://web/msg.txt" : "../{$OJ_ADMIN}/msg.txt");

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?=$OJ_NAME?>">
    <meta name="google-site-verification" content="SsihZnr3h6DuM0S6GvRf_JEzoUYuDxeST0gOKmrPHAY" />
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>        <?php echo $OJ_NAME?>    </title>
    <?php include("template/$OJ_TEMPLATE/css.php");?>
</head>

<body class="">
<!--CDN으로 교체 BS, FA-->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/js/fontawesome.min.js" integrity="sha256-xLAK3iA6CJoaC89O/DhonpICvf5QmdWhcPJyJDOywJM=" crossorigin="anonymous"></script>
<?php include("template/$OJ_TEMPLATE/nav.php");?>
<div class="container content-main mb-5">
    <?php
    $view_marquee_msg = trim($view_marquee_msg);
    $check_view = str_replace(" ","",$view_marquee_msg);

    if( $check_view != ""){
            ?>
            <div class="hide mt-0 mb-2 text-center  border border-danger small" id="news_area">
                <a class="btn btn-sm btn-block btn-danger m-0" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <i class="fas fa-exclamation-circle"></i> 공지사항이 있습니다.(클릭)
                </a>
                <div class="collapse " id="collapseExample">
                    <div class="card card-body  text-left">
                        <?=$view_marquee_msg?>
                    </div>
                </div>
            </div>
    <?php
        }
    ?>


