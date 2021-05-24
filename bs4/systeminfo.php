<?php
    $res = array();

    exec("top -b -n1 | grep -Po '[0-9.]+ id' | awk '{print 100-$1}'", $cpuinfo);
    $total_cpu = $cpuinfo[0];

    exec("cat /proc/meminfo | grep Mem", $meminfo);
    $total_mem = intval(explode(":", $meminfo[0])[1]);
    $usable_mem = intval(explode(":", $meminfo[2])[1]);

    array_push($res, array("total_cpu"=>$total_cpu));
    array_push($res, array("total_mem"=> $total_mem, "usable_mem" =>$usable_mem));

    echo json_encode($res);

