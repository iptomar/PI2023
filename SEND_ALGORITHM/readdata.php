<?php
$myfile = fopen("amostra.csv", "r"); 
$data = array();
while(!feof($myfile)) {
    $line = fgets($myfile); 
    $fields = explode(";", $line);
    array_push($data, $fields);
	
}
fclose($myfile);

print_r($data);
?>