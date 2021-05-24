<?php
  require_once("admin-header.php");
  if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator']) || isset($_SESSION[$OJ_NAME.'_'.'problem_editor']))) {
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
  }
?>
<div class="container ">
    <div class="list-tab" id="list-tab">
        <ul class="list-group">
            <li class="list-group-item"><a href="#">(top)</a></li>
        </ul>
    </div>

    <form method=POST action="problem_add.php" data-spy="scroll" data-target="#list-tab">
        <div class="card ">
            <div class="card-header h3">
                <?php echo $MSG_PROBLEM."-".$MSG_ADD ?>
            </div>
            <div class="card-body">
                <?php
                //check description field size
                $query = "SELECT DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_NAME='problem' AND COLUMN_NAME='description'";
                $res = pdo_query($query);
                $type = $res[0]['DATA_TYPE'];
                if($type != "mediumtext"){
                    ?>
                    <div class="alert alert-warning">
                        이미지 복사 붙여넣기를 사용하기 위해서는 problem.decription 필드의 크기가mediumtext 이상이어야 합니다.<br>
                        현재 필드의 크기는 <span class="text-primary font-weight-bold"><?=$type?></span> 입니다.<br>
                        <a class="btn btn-sm btn-primary" href="#" onclick="modifyFieldSize('problem','description')">필드타입 수정하기</a>
                    </div>
                    <?php
                }
                //end of check description field size
                ?>
                <input type=hidden name=problem_id value="New Problem">
                <div class="form-group h4">
                    <label>
                        <?=$MSG_TITLE?>
                    </label>
                    <input class="form-control form-control-lg" type=text name=title>
                </div>
                <div class="form-group h4">
                    <label>
                        제한설정
                    </label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><?php echo $MSG_Time_Limit?></div>
                                </div>
                                <input class="form-control text-right" type=text name=time_limit value=1 placeholder="초단위">
                                <div class="input-group-append">
                                    <div class="input-group-text">sec</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="input-group  ">
                                <div class="input-group-prepend ">
                                    <div class="input-group-text "><?php echo $MSG_Memory_Limit?></div>
                                </div>
                                <input class="form-control text-right" type=text name=memory_limit value=128 placeholder="MB">
                                <div class="input-group-append">
                                    <div class="input-group-text">MB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group h4 mt-3 list-obj" id="MSG_DES">
            <label>
                <?=$MSG_Description?>
            </label>
            <textarea class="tiny" rows=13 name=description cols=80></textarea>
        </div>
        <div class="form-group h4 mt-3 list-obj" id="MSG_IN">
            <label>
                <?=$MSG_Input?>
            </label>
            <textarea class="tiny" rows=13 name=input cols=80></textarea>
        </div>
        <div class="form-group h4 mt-3 list-obj" id="MSG_OUT">
            <label>
                <?=$MSG_Output?>
            </label>
            <textarea class="tiny" rows=13 name=output cols=80></textarea>
        </div>
        <div class="form-group h4 mt-3 list-obj" id="MSG_SAM_IN">
            <label>
                <?=$MSG_Sample_Input?>
            </label>
            <textarea  class="form-control"  rows=13 name=sample_input></textarea>
        </div>
        <div class="form-group h4 mt-3 list-obj" id="MSG_SAM_OUT">
            <label>
                <?=$MSG_Sample_Output?>
            </label>
            <textarea  class="form-control"  rows=13 name=sample_output></textarea>
        </div>
        <div class="form-group h4 mt-3 list-obj" id="MSG_TEST_IN">
            <label>
                <?=$MSG_Test_Input?>
            </label>(<?=$MSG_HELP_MORE_TESTDATA_LATER?>)
            <textarea class=" form-control" rows=13 name=test_input></textarea>
        </div>
        <div class="form-group h4 mt-3 list-obj" id="MSG_TEST_OUT">
            <label>
                <?=$MSG_Test_Output?>
            </label>(<?=$MSG_HELP_MORE_TESTDATA_LATER?>)
            <textarea class=" form-control" rows=13 name=test_output></textarea>
        </div>
        <div class="form-group h4 mt-3 list-obj" id="MSG_HINT">
            <label>
                <?=$MSG_HINT?>
            </label>
            <textarea class=" form-control" rows=13 name=hint></textarea>
        </div>
        <div class="form-group h4 mt-3 list-obj" id="MSG_SPJ">
            <label>
                <?=$MSG_SPJ?>
            </label>
            <input type=radio name=spj value='0' checked>아니오 / <input type=radio name=spj value='1'> 예
        </div>
        <div class="form-group h4 mt-3 list-obj" id="MSG_SRC">
            <label>
                <?=$MSG_SOURCE?>
            </label>
            <textarea name=source class="form-control" rows=1></textarea>
        </div>
        <div class="form-group h4 mt-3 list-obj" id="MSG_CONTEST">
            <label>
                <?=$MSG_CONTEST?>
            </label>
            <select class="form-control" name=contest_id>
                <?php
                $sql="SELECT `contest_id`,`title` FROM `contest` WHERE `start_time`>NOW() order by `contest_id`";
                $result=pdo_query($sql);
                echo "<option value=''>none</option>";
                if (count($result)==0){
                }else{
                    foreach($result as $row){
                        echo "<option value='{$row['contest_id']}'>{$row['contest_id']} {$row['title']}</option>";
                    }
                }?>
            </select>
        </div>
        <div align=center>
            <?php require_once("../include/set_post_key.php");?>
            <input type=submit value='<?php echo $MSG_SAVE?>' name=submit>
        </div>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/tinymce.min.js" integrity="sha512-RnlQJaTEHoOCt5dUTV0Oi0vOBMI9PjCU7m+VHoJ4xmhuUNcwnB5Iox1es+skLril1C3gHTLbeRepHs1RpSCLoQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/plugins/paste/plugin.min.js" integrity="sha512-PZXei/Vp39zScx6qEWBBdPVdDAOWd1A3l47MeZWH5l28LDw8MJWXPsok8GxHKNyULEPvu0UW+IK3ezzIPlVp2A==" crossorigin="anonymous"></script>
<script>
    tinymce.init({
        selector: '.tiny',
        plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak paste',
        toolbar_mode: 'floating',
        paste_data_images: true
    });
</script>
<script>
    makeList("list-obj");
</script>
<?php require_once ("admin-footer.php")?>