<?php require_once("admin-header.php");
if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'problem_editor']))) {
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
?>

<div class="container">
    <div class="text-center h3">
        문제 내보내기
    </div>
    <form action='problem_export_xml.php' method=post>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-2">
                        <label>
                            <input type="radio" class="" name="sel" data-sel="0" checked>
                            연속된 문제
                        </label>

                    </div>
                    <div class="col-10 " id="s0" >
                        <div class="input-group"  >
                            <div class="input-group-prepend">
                                <div class="input-group-text">시작번호(PID)</div>
                            </div>
                            <input class="form-control text-center" type=text name="start" placeholder="시작번호(PID)">
                            <div class="input-group-middle">
                                <div class="input-group-text">마지막번호(PID)</div>
                            </div>
                            <input class="form-control text-center" type=text size=10 name="end" placeholder="마지막번호(PID)">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-2">
                        <label>
                            <input type="radio" class="" name="sel"  data-sel="1">
                            문제 선택
                        </label>

                    </div>
                    <div class="col-10 " id="s1">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <div class="input-group-text">시작번호(PID)</div>
                            </div>
                            <input class="form-control" type=text size=40 name="in" value="" placeholder="문항번호를 쉽표로 구분하여 입력 1001, 1002,">
                        </div>
                    </div>
                </div>
                <?php require_once("../include/set_post_key.php");?>

            </div>
            <div class="card-footer text-center">
                <input type='hidden' name='do' value='do'>
                <input type=submit class="btn btn-primary" name=submit value='Export'>
                <input type=submit class="btn btn-info" value='Download'>
            </div>

        </div>
    </form>
    <div class="alert alert-info mt-3">
        * 둘중 체크된 하나만 동작하게 됩니다.<br>
        * from-to will working if empty IN <br>
        * if using IN,from-to will not working.<br>
        * IN can go with "," seperated problem_ids like [1000,1020]
    </div>
</div>
<script>
    $(document).ready(function (){
        check();
        $("input[name=sel]").on("click",function (){
            check();
        });
    });

    function check(){
        var id = $("input[name=sel]:checked").attr("data-sel");
        var other = 1 -id;
        id = "#s"+id;
        other = "#s"+other;
        $(id).children("div").children("input").prop("disabled", false);
        $(other).children("div").children("input").prop("disabled", true);
    }
</script>

<?php require("admin-footer.php"); ?>