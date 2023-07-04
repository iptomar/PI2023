<?php
$open = fopen("..\logIPRP.csv", "r");
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
		$substri= substr($row[1],0, 10);
		fwrite($open2, $substri."; \n");
	// }
}
	fclose($open2);
?>
<?php

?> 