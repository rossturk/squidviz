<?php

header("Content-Type: application/json");

$file = shell_exec('ceph pg stat');

$perf = explode(';',$file)[2];

$iops = 0;
preg_match('@(\d+)op/s@i', $perf, $matches);
$iops = $matches[1];

$response = array('bytes_rd' => '', 'bytes_wr' => '', 'ops' => $iops);

$output = json_encode($response);

print $output;

?>
