<?php

require_once("../../include/db_info.inc.php");
require_once("../../include/my_func.inc.php");

$sid = $_POST['sid'];
$query = "select * from runtimeinfo where solution_id='$sid'";
$res = pdo_query($query);
echo json_encode($res);

//print_r($res);
