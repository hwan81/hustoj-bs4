<?php include("template/$OJ_TEMPLATE/oj-header.php");?>

<div class="container">
    <h3>현재 접속중인 사용자 <span class="text-primary"><?php echo $on->get_num()?>명</span> </h3>
		<?php 
		foreach($users as $u){
				 if(is_array($u)){
				     $login_user = explode("@",$u['ua'])[0];
                     $login_user_agent = explode("@",$u['ua'])[1];
				 ?>
                         <div class="card card-body mt-3">
                             <div>사용자: <?=$login_user?>( <?php echo sprintf("%d분 %d초",($u['lastmove']-$u['firsttime'])/60,($u['lastmove']-$u['firsttime']) % 60)?> 접속중)</div>
                             <div>agent: <?=$login_user_agent?></div>
                             <div>페이지: <?php echo $u['refer']?></div>
                             <div>uri: <?php echo $u->uri?></div>
                             <div>ip정보:
                                 <?php $l = $ip->getlocation($u['ip']);
                                 echo $u->ip."&nbsp;";
                                 if(strlen(trim($l['area']))==0)
                                     echo $l['country'];
                                 else
                                     echo $l['area'].'@'.$l['country'];
                                 ?>
                             </div>
                         </div>
				<?php 
				}
		}
		?>
		<?php
		if(isset($_SESSION[$OJ_NAME.'_'.'administrator'])){
        	?>
      
    <form>IP<input type='text' name='search'><input type='submit' value="검색" ></form>
    <table class="table-hover table table-bordered">
        <tbody class="text-center">
            <tr>
                <td>사용자</td>
                <td>로그인상태</td>
                <td>IP</td>
                <td>접속시간</td>
            </tr>
				<?php 
				$cnt=0;
				foreach($view_online as $row){
					echo "<tr>\t";
					foreach($row as $table_cell){
						echo "<td>";
						echo "\t".$table_cell;
						echo "</td>";
					}
					echo "</tr>";
				}
				?>
        </tbody>
		<?php
		}
		?>
    </table>


<?php include("template/$OJ_TEMPLATE/oj-footer.php");?>