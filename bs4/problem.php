<?php include("template/$OJ_TEMPLATE/oj-header.php");?>

<?php if (isset($OJ_MATHJAX)&&$OJ_MATHJAX){?>
<script>
  MathJax = {
    tex: {inlineMath: [['$', '$'], ['\\(', '\\)']]}
  };
</script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-chtml.js"></script>
<?php } ?>
<div class="container p-0 mt-3">
    <div class="container-fluid p-0">
        <div class="card border-0 mb-3 ">
            <div class="card-header border-0  text-center">
                <?php
				if ( $pr_flag ) {
					echo " <h2><span class='bg-primary rounded text-light pb-1 pl-2 pr-2'>문제 {$id}</span> " . $row[ 'title' ] . "</h2>";
					echo "<div align=right><sub>[$MSG_Creator : <span id='creator'></span>]</sub></div>";
				} else {
					//$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
					$id = $row[ 'problem_id' ];
					echo "<h2> <span class='bg-danger rounded text-light pb-1 pl-2 pr-2'>문제 {$PID[ $pid ]}</span> {$row[ 'title' ] }</h2>";
					echo "<div align=right><sub>[$MSG_Creator : <span id='creator'></span>]</sub></div>";
				}
				?>
            </div>
        </div>
        <div class="float-top bg-light rounded pt-1 pb-1">
            <div class="container">
                <div class="alert alert-success mt-2 font-weight-bold ">
                    <div class="row">
                        <div class="col-7 small text-left p-0">
                            <span class="btn btn-sm">
                                <i class="fas fa-clock"></i>&nbsp;<span class="d-none d-md-inline">시간제한 : </span><span class="bg-danger  text-light" fd='time_limit' pid='<?=$row['problem_id']?>'> &nbsp;<?=$row[ 'time_limit' ]?> sec &nbsp;</span> &nbsp;&nbsp;&nbsp;
                            </span>
                                <span class="btn btn-sm">
                                <i class="fas fa-database"></i>&nbsp;<span class="d-none d-md-inline">메모리제한 : </span><span class="bg-danger  text-light">&nbsp; <?=$row[ 'memory_limit' ]?> MB &nbsp;</span>
                            </span>
                        </div>
                        <div class="col-5 text-right mr-auto p-0">
                            <?php
                            echo "<div class='btn-group' role='group'>";
                            if (isset($OJ_OI_MODE)&&$OJ_OI_MODE) {
                                //???
                            } else {

                                echo "<a class='btn btn-success btn-sm' role='button' href=status.php?problem_id=".$row['problem_id']."&jresult=4>
                                    <span class=\"d-none d-md-inline\">$MSG_SOVLED </span>
                                    <span class=\"d-inline d-sm-none small\">P</span>
                                    ".$row['accepted']."</a>";
                                echo "<a class='btn btn-info btn-sm' role='button' href=status.php?problem_id=".$row['problem_id'].">
                                    <span class=\"d-none d-md-inline\">$MSG_SUBMIT_NUM </span>
                                    <span class=\"d-inline d-sm-none small\">S</span>".$row['submit']."</a>";
                                echo "<a class='btn btn-warning btn-sm' role='button' href=problemstatus.php?id=".$row['problem_id'].">
                                    <span class=\"d-none d-md-inline\">$MSG_STATISTICS </span>
                                    <span class=\"d-inline d-sm-none small \">R</span></a>";
                            }

                            if (isset($_SESSION[$OJ_NAME.'_'.'administrator']) || isset($_SESSION[$OJ_NAME.'_'.'contest_creator']) || isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])) {
                                require_once("include/set_get_key.php");
                                echo "<a class='btn btn-success btn-sm' role='button' href={$OJ_ADMIN}/problem_edit.php?id=$id&getkey=".$_SESSION[$OJ_NAME.'_'.'getkey'].">EDIT</a>";
                                echo "<a class='btn btn-success btn-sm' role='button' href=javascript:phpfm(".$row['problem_id'].")>TESTDATA</a>";
                            }
                            echo "</div>";
                            ?>
                        </div>
                    </div>
                </div>
                <div>
                    <?php
                    if ( $row[ 'spj' ] )echo "&nbsp;&nbsp;<span class=red>Special Judge</span>";
                    if($pr_flag){
                        echo "<a class='btn btn-outline-primary btn-lg btn-block mb-2 large' href='submitpage.php?id=$id' role='button'><i class=' far fa-edit'></i> 정답을 제출합니다</a>";
                    }else{
                        echo "<a class='btn btn-outline-danger btn-lg btn-block mb-2 ' href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask' role='button'><i class='far fa-edit'></i> 정답을 제출합니다</a>";
                        echo "<a class='btn btn-outline-primary btn-block btn-sm' role='button' href='contest.php?cid=$cid'>$MSG_PROBLEM$MSG_LIST</a>";
                    }
                    echo "<!--StartMarkForVirtualJudge-->";
                    ?>
                </div>
            </div>
        </div>

        <div class="card mt-3 border-info float-top-next">
            <div class="card-header h4 bg-info text-light">  <?php echo $MSG_Description?></div>
            <div class="card-body">
                <?php echo $row['description']?>
            </div>
        </div>

        <div class="card mt-3 border-success">
            <div class="card-header h4 bg-success text-light">  <?php echo $MSG_Input?></div>
            <div class="card-body">
                <?php echo $row['input']?>
            </div>
        </div>

        <div class="card mt-3 border-success">
            <div class="card-header h4 bg-success text-light">  <?php echo $MSG_Output?></div>
            <div class="card-body">
                <?php echo $row['output']?>
            </div>
        </div>
        <?php
            $sinput=str_replace("<","&lt;",$row['sample_input']);
            $sinput=str_replace(">","&gt;",$sinput);
            $soutput=str_replace("<","&lt;",$row['sample_output']);
            $soutput=str_replace(">","&gt;",$soutput);

            if(strlen($sinput)){?>

                <div class='card mt-3 border-warning'>
                    <div class='card-header bg-warning h4'>
                        <?php echo $MSG_Sample_Input?>
                        <a class="text-danger" href="javascript:CopyToClipboard($('#sampleinput').text())"><i class="far fa-copy"></i>복사</a>
                    </div>
                    <div class='card-body'><pre class=content><span id="sampleinput" class=sampledata><?php echo $sinput?></span></pre>
                    </div>
                </div>
            <?php }

            if(strlen($soutput)){?>
                <div class='card mt-3 border-warning'>
                    <div class='card-header bg-warning h4'>
                        <?php echo $MSG_Sample_Output?>
                        <a class="text-danger" href="javascript:CopyToClipboard($('#sampleoutput').text())"><i class="far fa-copy"></i>복사</a>
                    </div>
                    <div class='card-body'><pre class=content><span id='sampleoutput' class=sampledata><?php echo $soutput?></span></pre>
                    </div>
                </div>
            <?php }

            if($row['hint']){?>
                <div class='card border-primary mt-3'>
                    <div class='card-header bg-primary h5 text-light'>
                        <?php echo $MSG_HINT?>
                    </div>
                    <div class='card-body'>
                        <?php echo $row['hint']?>
                    </div>
                </div>
            <?php }

            if($pr_flag){?>
                <div class='card border-primary mt-3'>
                    <div class='card-header bg-primary h5 text-light'>
                        <?php echo $MSG_SOURCE?>
                    </div>
                    <div class="card-body content" fd="source" style='word-wrap:break-word;' pid=<?php echo $row['problem_id']?>  >
                        <?php
                        $cats=explode(" ",$row['source']);
                        echo"<h5>";
                        foreach($cats as $cat){
                            $hash_num=hexdec(substr(md5($cat),0,7));
                            $label_theme=$color_theme[$hash_num%count($color_theme)];
                            if($label_theme=="") $label_theme="default";
                            echo "<a class='text-dark p-2 badge border border-$label_theme' style='display: inline-block;' href='problemset.php?search=".urlencode(htmlentities($cat,ENT_QUOTES,'utf-8'))."'>".htmlentities($cat,ENT_QUOTES,'utf-8')."</a>&nbsp;";
                        }
                        echo"</h5>";
                        ?>
                    </div>
                </div>
            <?php }?>

        <div class='mt-3'>
<!--            --><?php
//            if($pr_flag){
//                echo "<a class='btn btn-outline-primary btn-block' href='submitpage.php?id=$id' role='button'>$MSG_SUBMIT</a>";
//            }else{
//                echo "<a class='btn btn-info btn-block' href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask' role='button'>$MSG_SUBMIT</a>";
//            }
            if ($OJ_BBS) echo "<a class='btn btn-warning btn-block' href='bbs.php?pid=".$row['problem_id']."$ucid'>$MSG_BBS</a>";
            ?>
        </div>
    </div>
</div>

<script>
    function phpfm( pid ) {
        //alert(pid);
        $.post( "admin/phpfm.php", {
            'frame': 3,
            'pid': pid,
            'pass': '',
            'csrf': '<?php echo $token?>'
        }, function ( data, status ) {
            if ( status == "success" ) {
                document.location.href = "admin/phpfm.php?frame=3&pid=" + pid;
            }
        } );
    }

    $( document ).ready( function () {
        fixtop();
        $("img").addClass("img-fluid");
        $( "#creator" ).load( "problem-ajax.php?pid=<?php echo $id?>" );

    } );

    function fixtop(){
        var top = $(".float-top").offset().top ;
        var o = parseInt($(".float-top-next").css("margin-top"));
        var m = parseInt($(".float-top").css("margin-top")) + parseInt($(".float-top").css("margin-bottom")) + $(".float-top").height() + o  + 30;
        console.log(top, o, m);
        $(window).scroll(function (){
           if($(window).scrollTop() > top){
               $(".float-top").addClass("float shadow p-2");
               $(".float-top-next").attr("style","margin-top:"+m+"px!important");
           }else{
               $(".float-top").removeClass("float  shadow p-2");
               $(".float-top-next").attr("style","margin-top:"+o+"px");
           }

        });
    }

    function CopyToClipboard (input) {
        var textToClipboard = input;

        var success = true;
        if (window.clipboardData) { // Internet Explorer
            window.clipboardData.setData ("Text", textToClipboard);
        }
        else {
            // create a temporary element for the execCommand method
            var forExecElement = CreateElementForExecCommand (textToClipboard);

            /* Select the contents of the element
            (the execCommand for 'copy' method works on the selection) */
            SelectContent (forExecElement);

            var supported = true;

            // UniversalXPConnect privilege is required for clipboard access in Firefox
            try {
                if (window.netscape && netscape.security) {
                    netscape.security.PrivilegeManager.enablePrivilege ("UniversalXPConnect");
                }

                // Copy the selected content to the clipboard
                // Works in Firefox and in Safari before version 5
                success = document.execCommand ("copy", false, null);
            }
            catch (e) {
                success = false;
            }

            // remove the temporary element
            document.body.removeChild (forExecElement);
        }

        if (success) {
            alert ("텍스트가 복사되었습니다");
        }
        else {
            alert ("브라우저에서 허용되지 않습니다.ㅠㅠ");
        }
    }

    function CreateElementForExecCommand (textToClipboard) {
        var forExecElement = document.createElement ("pre");
        // place outside the visible area
        forExecElement.style.position = "absolute";
        forExecElement.style.left = "-10000px";
        forExecElement.style.top = "-10000px";
        // write the necessary text into the element and append to the document
        forExecElement.textContent = textToClipboard;
        document.body.appendChild (forExecElement);
        // the contentEditable mode is necessary for the  execCommand method in Firefox
        forExecElement.contentEditable = true;

        return forExecElement;
    }

    function SelectContent (element) {
        // first create a range
        var rangeToSelect = document.createRange ();
        rangeToSelect.selectNodeContents (element);

        // select the contents
        var selection = window.getSelection ();
        selection.removeAllRanges ();
        selection.addRange (rangeToSelect);
    }
</script>


<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>
