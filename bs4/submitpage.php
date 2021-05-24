<?php include("template/$OJ_TEMPLATE/oj-header.php");?>


    <div class="container">
      <div class="card">
        <script src="include/checksource.js"></script>
        <form id=frmSolution action="submit.php" method="post" onsubmit='do_submit()'>
            <div class="card-header h4">
              <?php if (isset($id)){?>
                <?php echo $MSG_PROBLEM_ID." : "?> <span class=blue><?php echo $id?></span>
                  <a class="h6" href="/problem.php?id=<?=$id?>" target="_blank">문제 바로가기&nbsp;<i class="fas fa-external-link-alt"></i></a>
                <input id=problem_id type='hidden' value='<?php echo $id?>' name="id" >
              <?php } else {
              //$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
              //if ($pid>25) $pid=25;
              ?>
                <?php echo $MSG_PROBLEM_ID." : "?> <span class=blue><?php echo chr($pid+ord('A'))?></span>
                <br> of Contest <span class=blue> <?php echo $cid?> </span>
                <br>
                <input id="cid" type='hidden' value='<?php echo $cid?>' name="cid">
                <input id="pid" type='hidden' value='<?php echo $pid?>' name="pid">
              <?php }?>
            </div>
            <div class="card-body mb-1">
              <div class="input-group" id="language_span">
                  <div class="input-group-prepend">
                      <div class="input-group-text"><?php echo $MSG_LANG?></div>
                  </div>
                <select class=" form-control" id="language" name="language" onChange="reloadtemplate($(this).val());" >
                  <?php
                    $lang_count=count($language_ext);
                    if(isset($_GET['langmask']))
                      $langmask=$_GET['langmask'];
                    else
                      $langmask=$OJ_LANGMASK;
                    $lang=(~((int)$langmask))&((1<<($lang_count))-1);
                    if(isset($_COOKIE['lastlang'])) $lastlang=$_COOKIE['lastlang'];
                    else $lastlang=0;

                    for($i=0;$i<$lang_count;$i++){
                      if($lang&(1<<$i))
                        echo"<option value=$i ".( $lastlang==$i?"selected":"").">".$language_name[$i]."</option>";
                    }
                  ?>
                </select>
                  <div class="input-group-append">
                      <div class="input-group-text btn btn-primary" id="notice-btn" >표준입출력안내</div>
                  </div>
              </div>

              <?php if($OJ_ACE_EDITOR){ ?>
                <pre class="ace-editor-form"   id="source"><?php echo htmlentities($view_src,ENT_QUOTES,"UTF-8")?></pre>
                <input type=hidden id="hide_source" name="source" value=""/>
              <?php }else{ ?>
                <textarea class="ace-editor-form" id="source" name="source"> <?php echo htmlentities($view_src,ENT_QUOTES,"UTF-8")?></textarea>
              <?php }?>

              <?php if (isset($OJ_TEST_RUN)&&$OJ_TEST_RUN){?>
                <?php echo $MSG_Input?>:
                <textarea style="width:30%" cols=40 rows=5 id="input_text" name="input_text" ><?php echo $view_sample_input?></textarea>

                <?php echo $MSG_Output?>:
                <textarea style="width:30%" cols=10 rows=5 id="out" name="out" disabled="true" >SHOULD BE:<?php echo $view_sample_output?></textarea>
              <?php } ?>

              <?php if($OJ_VCODE){?>
                <?php echo $MSG_VCODE?>:
                <input name="vcode" size=4 type=text> <img id="vcode" alt="click to change" onclick="this.src='vcode.php?'+Math.random()">*
              <?php }?>


              <input id="Submit" class="btn-lg col-12 btn btn-info" type=button value="<?php echo $MSG_SUBMIT?>" onclick="do_submit();" >

              <?php if (isset($OJ_ENCODE_SUBMIT)&&$OJ_ENCODE_SUBMIT){?>
                <input class="btn btn-success" title="WAF gives you reset ? try this." type=button value="Encoded <?php echo $MSG_SUBMIT?>"  onclick="encoded_submit();">
                <input type=hidden id="encoded_submit_mark" name="reverse2" value="reverse">
              <?php }?>

              <?php if (isset($OJ_TEST_RUN)&&$OJ_TEST_RUN){?>
                <input id="TestRun" class="btn btn-info" type=button value="<?php echo $MSG_TR?>" onclick=do_test_run();>
                <span class="btn" id=result>状态</span>
              <?php }?>

              <?php if (isset($OJ_BLOCKLY)&&$OJ_BLOCKLY){?>
                <input id="blockly_loader" type=button class="btn" onclick="openBlockly()" value="<?php echo $MSG_BLOCKLY_OPEN?>" style="color:white;background-color:rgb(169,91,128)">
                <input id="transrun" type=button  class="btn" onclick="loadFromBlockly() " value="<?php echo $MSG_BLOCKLY_TEST?>" style="display:none;color:white;background-color:rgb(90,164,139)">
                <div id="blockly" class="center">Blockly</div>
              <?php }?>
            </div>
        </form>

    </div>

  </div> <!-- /container -->
    <div class="modal fade" id="notice" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">표준입출력안내</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mt-3">
                        <div class="card-header h5 ">
                            코드 작성시 데이터 입출력은 어떻게 하나요?
                        </div>
                        <div class="card-body">
                            stdin('표준입력')에서 읽고, stdout('표준출력')으로 출력해야합니다. 예를 들어, C언어에서는 'scanf', C++ 언어에서는 'cin' 을 이용해서 stdin(입력)을 읽어들입니다. 또한, C언어에서는 'printf', C++언어에서는 'cout'을 이용해 stdout(출력)으로 출력할 수 있습니다.<br> 파일 입출력을 사용한 코드를 제출하는 경우에는 "<font color=green>Runtime Error</font>(실행 중 에러)" 메시지를 받게 됩니다.
                        </div>


                        <div class="card-footer p-5">
                            <h5>
                                C++ 입출력 예시
                            </h5>

                            <pre class="card pre-scrollable">
#include &lt;iostream&gt;
using namespace std;
int main(){
    int a,b;
    while(cin >> a >> b)
        cout << a+b << endl;
    return 0;
}
</pre>
                            <h5>C 입출력 예시:</h5>
                            <pre class="card pre-scrollable">
#include &lt;stdio.h&gt;
int main(){
    int a,b;
    while(scanf("%d %d",&amp;a, &amp;b) != EOF)
        printf("%d\n",a+b);
    return 0;
}
                                </font></pre>

                            <h5>Java 입출력 예시:</h5>
                            <pre class="card pre-scrollable">
import java.util.*;
public class Main{
	public static void main(String args[]){
		Scanner cin = new Scanner(System.in);
		int a, b;
		while (cin.hasNext()){
			a = cin.nextInt(); b = cin.nextInt();
			System.out.println(a + b);
		}
	}
}</font></pre>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

  <script>
    var sid = 0;
    var i = 0;
    var using_blockly = false;
    var judge_result = [<?php
      foreach($judge_result as $result){
        echo "'$result',";
      }?>''];
    
    function print_result(solution_id)
    {
      sid = solution_id;
      $("#out").load("status-ajax.php?tr=1&solution_id="+solution_id);
    }
    
    function fresh_result(solution_id)
    {
      var tb = window.document.getElementById('result');
      if (solution_id==undefined) {
        tb.innerHTML="Vcode Error!";    
        if($("#vcode")!=null) $("#vcode").click();
        
        return ;
      }

      sid=solution_id;
      var xmlhttp;
      if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
      } else {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }

      xmlhttp.onreadystatechange=function()
      {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
          var r=xmlhttp.responseText;
          var ra=r.split(",");
          //alert(r);
          // alert(judge_result[r]);
          var loader="<img width=18 src=image/loader.gif>";
          var tag="span";

          if(ra[0]<4)
            tag="span disabled=true";
          else
          {
            if(ra[0]==11)
              tb.innerHTML="<a href='ceinfo.php?sid="+solution_id+"' class='badge badge-info' target=_blank>"+judge_result[ra[0]]+"</a>";
            else
              tb.innerHTML="<a href='reinfo.php?sid="+solution_id+"' class='badge badge-info' target=_blank>"+judge_result[ra[0]]+"</a>";
          }

          if(ra[0]<4)tb.innerHTML+=loader;
          
          tb.innerHTML+="Memory:"+ra[1]+"KB&nbsp;&nbsp;";
          tb.innerHTML+="Time:"+ra[2]+"ms";

          if(ra[0]<4)
            window.setTimeout("fresh_result("+solution_id+")",2000);
          else {
            window.setTimeout("print_result("+solution_id+")",2000);
            count=1;
          }
        }
      }

      xmlhttp.open("GET","status-ajax.php?solution_id="+solution_id,true);
      xmlhttp.send();
    }

    function getSID(){
      var ofrm1 = document.getElementById("testRun").document;
      var ret="0";
      if (ofrm1==undefined)
      {
        ofrm1 = document.getElementById("testRun").contentWindow.document;
        var ff = ofrm1;
        ret=ff.innerHTML;
      }
      else
      {
        var ie = document.frames["frame1"].document;
        ret=ie.innerText;
      }
      return ret+"";
    }

    var count=0;

    function encoded_submit(){
      var mark="<?php echo isset($id)?'problem_id':'cid';?>";
      var problem_id=document.getElementById(mark);

      if(typeof(editor) != "undefined")
        $("#hide_source").val(editor.getValue());
      if(mark=='problem_id')
        problem_id.value='<?php if(isset($id)) echo $id?>';
      else
        problem_id.value='<?php if(isset($cid))echo $cid?>';

      document.getElementById("frmSolution").target="_self";
      document.getElementById("encoded_submit_mark").name="encoded_submit";
      var source=$("#source").val();

      if(typeof(editor) != "undefined") {
        source=editor.getValue();
        $("#hide_source").val(encode64(utf16to8(source)));
      }else{
        $("#source").val(encode64(utf16to8(source)));
      }
      //      source.value=source.value.split("").reverse().join("");
      //      alert(source.value);
      document.getElementById("frmSolution").submit();
    }

    function do_submit(){
      <?php if($OJ_LONG_LOGIN=true&&isset($_COOKIE[$OJ_NAME."_user"])&&isset($_COOKIE[$OJ_NAME."_check"]))echo"let xhr=new XMLHttpRequest();xhr.open('GET','login.php',true);xhr.send();";?>
      if(using_blockly) 
        translate();
     
      if(typeof(editor) != "undefined"){ 
        $("#hide_source").val(editor.getValue());
      }

      var mark="<?php echo isset($id)?'problem_id':'cid';?>";
      var problem_id=document.getElementById(mark);

      if(mark=='problem_id')
        problem_id.value='<?php if (isset($id))echo $id?>';
      else
        problem_id.value='<?php if (isset($cid))echo $cid?>';

      document.getElementById("frmSolution").target="_self";
      document.getElementById("frmSolution").submit();
    }

    var handler_interval;

    function do_test_run(){
      if( handler_interval) window.clearInterval( handler_interval);

      var loader="<img width=18 src=image/loader.gif>";
      var tb=window.document.getElementById('result');
      var source=$("#source").val();

      if(typeof(editor) != "undefined") {
        source = editor.getValue();
        $("#hide_source").val(source);
      }

      if(source.length<10) return alert("too short!");

      if(tb!=null) tb.innerHTML=loader;

      var mark="<?php echo isset($id)?'problem_id':'cid';?>";
      var problem_id=document.getElementById(mark);
      problem_id.value=-problem_id.value;
      document.getElementById("frmSolution").target="testRun";
      //$("#hide_source").val(editor.getValue());
      //document.getElementById("frmSolution").submit();
      $.post("submit.php?ajax",$("#frmSolution").serialize(),function(data){fresh_result(data);});
      $("#Submit").prop('disabled', true);
      $("#TestRun").prop('disabled', true);
      problem_id.value=-problem_id.value;
      count=20;
      handler_interval= window.setTimeout("resume();",1000);
    }

    function resume(){
      count--;
      var s=$("#Submit")[0];
      var t=$("#TestRun")[0];
      if(count<0){
        s.disabled=false;
        if(t!=null)t.disabled=false;
        
        s.value="<?php echo $MSG_SUBMIT?>";
        
        if(t!=null)t.value="<?php echo $MSG_TR?>";
        
        if( handler_interval) window.clearInterval( handler_interval);
        
        if($("#vcode")!=null) $("#vcode").click();
      }else{
        s.value="<?php echo $MSG_SUBMIT?>("+count+")";
        
        if(t!=null)t.value="<?php echo $MSG_TR?>("+count+")";
        
        window.setTimeout("resume();",1000);
      }
    }

    function switchLang(lang){
      var langnames=new Array("c_cpp","c_cpp","pascal","java","ruby","sh","python","php","perl","csharp","objectivec","vbscript","scheme","c_cpp","c_cpp","lua","javascript","golang");
      editor.getSession().setMode("ace/mode/"+langnames[lang]);
    }

    function reloadtemplate(lang){
      console.log("lang="+lang);
      document.cookie="lastlang="+lang;
      //alert(document.cookie);
      var url=window.location.href;
      var i=url.indexOf("sid=");
      if(i!=-1) url=url.substring(0,i-1);
      
      <?php if (isset($OJ_APPENDCODE)&&$OJ_APPENDCODE){?>
        if(confirm("<?php echo  $MSG_LOAD_TEMPLATE_CONFIRM?>"))
          document.location.href=url;
      <?php }?>
      switchLang(lang);
    }


    function openBlockly(){
      $("#frame_source").hide();
      $("#TestRun").hide();
      $("#language")[0].scrollIntoView();
      $("#language").val(6).hide();
      $("#language_span").hide();
      $("#EditAreaArroundInfos_source").hide();
      $('#blockly').html('<iframe name=\'frmBlockly\' width=90% height=580 src=\'blockly/demos/code/index.html\'></iframe>'); 
      $("#blockly_loader").hide();
      $("#transrun").show();
      $("#Submit").prop('disabled', true);
      using_blockly=true;
    }

    function translate(){
      var blockly=$(window.frames['frmBlockly'].document);
      var tb=blockly.find('td[id=tab_python]');
      var python=blockly.find('pre[id=content_python]');
      tb.click();
      blockly.find('td[id=tab_blocks]').click();
      if(typeof(editor) != "undefined") editor.setValue(python.text());
      else $("#source").val(python.text());
      $("#language").val(6);
    }

    function loadFromBlockly(){
      translate();
      do_test_run();
      $("#frame_source").hide();
     //  $("#Submit").prop('disabled', false);
    }
  </script>

  <script language="Javascript" type="text/javascript" src="include/base64.js"></script>

  <?php if($OJ_ACE_EDITOR){ ?>
<!--      <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.min.js" integrity="sha512-GoORoNnxst42zE3rYPj4bNBm0Q6ZRXKNH2D9nEmNvVF/z24ywVnijAWVi/09iBiVDQVf3UlZHpzhAJIdd9BXqw==" crossorigin="anonymous"></script>-->
    <script src="ace/ace.js"></script>
    <script src="ace/ext-language_tools.js"></script>

    <script>
      ace.require("ace/ext/language_tools");
      var editor = ace.edit("source");
      editor.setTheme("ace/theme/twilight");
      switchLang(<?php echo isset($lastlang)?$lastlang:0 ;  ?>);
      editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true
      });
      document.getElementById('source').style.fontSize='16px';

    </script>
  <?php }?>
    <script>
        $(document).ready(function () {
            $("#notice-btn").on("click",function (){
                $("#notice").modal();
            });

      <?php if ($OJ_VCODE) { ?>
            $("#vcode").attr("src", "vcode.php?" + Math.random());
      <?php } ?>
        })
    </script>
<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>