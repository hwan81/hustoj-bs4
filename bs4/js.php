<?php
if(file_exists("./admin/msg.txt"))
$view_marquee_msg=file_get_contents($OJ_SAE?"saestor://web/msg.txt":"./admin/msg.txt");
if(file_exists("/{$OJ_TEMPLATE}admin/msg.txt")){
    echo "{$OJ_TEMPLATE}admin/msg.txt";
}

?>

<script>
$(document).ready(function(){
  
  $("form").append("<div id='csrf' />");
  $("#csrf").load("<?php echo $path_fix?>csrf.php");

  <?php 
	if(isset($_SESSION[$OJ_NAME."_administrator"])) echo "admin_mod();";
  ?>
});


  //console.log("If you want to change the appearance of the web pages, make a copy of bs3 under template directory.\nRename it to whatever you like, and change the $OJ_TEMPLATE value in db_info.inc.php\nAfter that modify files under your own directory .\n");
  //console.log("To enable mathjax in hustoj, check line 15 in /home/judge/src/web/template/bs3/js.php");
function admin_mod(){
	$("div[fd=source]").each(function(){
		let pid=$(this).attr('pid');	
		$(this).append("<span><span class='badge badge-success' pid='"+pid+"' onclick='problem_add_source(this,"+pid+");' style='cursor: pointer'>+</span></span>");

	});
	$("span[fd=time_limit]").each(function(){
		let sp=$(this);
		let pid=$(this).attr('pid');	
		$(this).dblclick(function(){
			let time=sp.text();
			console.log("pid:"+pid+"  time_limit:"+time);	
			sp.html("<form onsubmit='return false;'><input type=hidden name='m' value='problem_update_time'><input type='hidden' name='pid' value='"+pid+"'><input type='text' name='t' value='"+time+"' selected='true' class='input-mini' size=2 ></form>");
			let ipt=sp.find("input[name=t]");
			ipt.focus();
			ipt[0].select();
			sp.find("input").change(function(){
				let newtime=sp.find("input[name=t]").val();
				$.post("admin/ajax.php",sp.find("form").serialize()).done(function(){
					console.log("new time_limit:"+time);
					sp.html(newtime);
				});
			
			});
		});

	});
}
function problem_add_source(sp,pid){
	console.log("pid:"+pid);
	let p=$(sp).parent();
	p.html("<form onsubmit='return false;'><input type='hidden' name='m' value='problem_add_source'><input type='hidden' name='pid' value='"+pid+"'><input type='text' class='form-control form-control-sm' size=10 name='ns'></form>");
	p.find("input").focus();
	p.find("input").change(function(){
		console.log(p.find("form").serialize());
		let ns=p.find("input[name=ns]").val();
		console.log("new source:"+ns);
		$.post("admin/ajax.php",p.find("form").serialize());
		p.parent().append("<span class='label label-success'>"+ns+"</span>");
		p.html("<span class='label label-success' pid='"+pid+"' onclick='problem_add_source(this,"+pid+");'>+</span>");
	});
}
</script>

