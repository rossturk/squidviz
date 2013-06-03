<?php

header("Content-Type: application/json");

//$name = 'cephdemo-pgdump.json';
//$fp = fopen($name, 'r');
//$file = file_get_contents($name,0,null,null);

$file = shell_exec('ceph pg dump --format=json');

$input_json = json_decode(preg_replace('/^.+\n/', '', $file));


$pgTree = array('name' => 'cluster', 'children' => array());

foreach ( $input_json->pg_stats as $pg )
{
	list ($pool, $group) = explode('.', $pg->pgid);
	
	$poolindex = -1;

       	for ($x = 0; $x < count($pgTree['children']); $x++)
	{
		if ($pgTree['children'][$x]['name'] == $pool) { $poolindex = $x; }
	}

	$pgNode = array(
		"name" => $group,
		"pgid" => $pg->pgid,
		"objects" => $pg->stat_sum->num_objects,
		"state" => $pg->state
	);
	
	if ($poolindex != -1) // existing pool
	{
		$pgTree['children'][$poolindex]['children'][] = $pgNode;
	}
	else
	{
		$pgTree['children'][] = array(
			"name" => $pool,
			"children" => array($pgNode)
		);
   	}
}

$output = json_encode($pgTree);

//var_dump($pgTree);
print $output;

?>
