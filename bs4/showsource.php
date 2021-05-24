<?php include("template/$OJ_TEMPLATE/oj-header.php");?>
<div class="container">
	
<link href='highlight/styles/shCore.css' rel='stylesheet' type='text/css'/>
<link href='highlight/styles/shThemeDefault.css' rel='stylesheet' type='text/css'/>
<script src='highlight/scripts/shCore.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushCpp.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushCss.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushJava.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushDelphi.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushRuby.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushBash.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushPython.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushPhp.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushPerl.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushCSharp.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushVb.js' type='text/javascript'></script>
<script src='highlight/scripts/shBrushSql.js' type='text/javascript'></script>
<script language='javascript'>
SyntaxHighlighter.all();
</script>
<?php
if ($ok==true){
if($view_user_id!=$_SESSION[$OJ_NAME.'_'.'user_id'])
echo "<a href='mail.php?to_user=".htmlentities($view_user_id,ENT_QUOTES,"UTF-8")."&title=$MSG_SUBMIT $id'>Mail the author</a>";
$brush=strtolower($language_name[$slanguage]);
if ($brush=='pascal') $brush='delphi';
if ($brush=='obj-c') $brush='c';
if ($brush=='freebasic') $brush='vb';
if ($brush=='fortran') $brush='vb';
if ($brush=='swift') $brush='csharp';
echo "<pre class=\"brush:".$brush.";\">";
ob_start();
echo "/**************************************************************\n";
echo "\tProblem: $sproblem_id\n\tUser: $suser_id\n";
echo "\tLanguage: ".$language_name[$slanguage]."\n\tResult: ".$judge_result[$sresult]."\n";
if ($sresult==4){
echo "\tTime:".$stime." ms\n";
echo "\tMemory:".$smemory." kb\n";
}
echo "****************************************************************/\n\n";
$auth=ob_get_contents();
ob_end_clean();
echo htmlentities(str_replace("\n\r","\n",$view_source),ENT_QUOTES,"utf-8")."\n".$auth."</pre>";
}else{
echo $MSG_WARNING_ACCESS_DENIED ;
}
?>
</div> <!-- /container -->

<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>
