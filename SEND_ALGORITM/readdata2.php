<?php
$open = fopen("amostra.csv", "r");
$filteredData = [];
 
if ($open !== false){

	while (($commado = fgetcsv($open, 0, ';'))!== false){		
		if (strcmp($commado[3], 'SEND_ALGORITHM      ') ==false){
			$filteredData[]=$commado;
		}
	}
	fclose($open);
	
}

foreach ($filteredData as $row)
{
	$column=$row[1];
	echo $column ;
	echo "</br>";
}
$open2 = fopen("writetest.txt", "w");
$fsize=filesize("writetest.txt");
// if ($fsize ==0){
	foreach($filteredData as $row){
		//$substri= substr($row[1],0, 10);
		fwrite($open2, (substr($row[1],0, 4))."; ".(substr($row[1], 5, 2)). "; ".(substr($row[1],8, 2))."; \n");
	// }
}
?>