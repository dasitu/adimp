<?php
for($i=0;$i<10;$i++){
	if($i==8)
		$data['a']['c'][] = $i;
	else
		$data['a']['b'][] = $i;	
}
print_r($data['a']['b']);
?>