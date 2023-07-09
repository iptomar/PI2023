<?php
$open = fopen("..\logIPRP.csv", "r");
$filteredData = [];
 
if ($open !== false){

	while (($commado = fgetcsv($open, 0, ';'))!== false){		
		if (strcmp(trim($commado[3]), 'SEND_ALGORITHM') ==false && (strcmp($commado[5], 'service done![1]')>=0)){
			$filteredData[]=$commado;
		}
	}
	fclose($open);
	
}

foreach ($filteredData as $row)
{
	$column= substr($row[1],10, 3);
	echo $column ;
	echo "</br>";
}
	$open2 = fopen("filtrodayhour.csv", "w");
// if ($fsize ==0){
	foreach($filteredData as $row){
		$substri= substr($row[1],0,13);
	
		
			if (!(strpos($row[4], "aluno") !== 0)){
			fwrite($open2, $substri.";\n");
			}
		//fwrite($open2, $substri."\n");
	}
//}
	fclose($open2);
?>