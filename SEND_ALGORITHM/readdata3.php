<?php
$open = fopen("..\logIPRP.csv", "r");
$filteredData = [];
 
if ($open !== false){

	while (($commado = fgetcsv($open, 0, ';'))!== false){		
				if (strcmp($commado[3], 'SEND_ALGORITHM      ') ==false && (strcmp($commado[5], 'service done![1]')>=0)){

			$filteredData[]=$commado;
		}
	}
	fclose($open);
	
}

foreach ($filteredData as $row)
{
	$column=$row[4];
	echo $column ;
	echo "</br>";
}
// $open2 = fopen("filtroUsers.csv", "w");
// fclose($open2);
$open2 = fopen("filtroUsers.csv", "w");

$fsize=filesize("filtroUsers.csv");
// if ($fsize ==0){
	foreach($filteredData as $row){
		$posi= strpos($row[4], "@");
		$ficheiro = file_get_contents("filtroUsers.csv");
		$substri= substr($row[4],0, $posi);
		//fwrite($open2, $row[4]."\n");
		if (strpos($ficheiro, $substri) == false){
			fwrite($open2, $substri." \n");
		}
		
	// }
}
	fclose($open2);
?>
<?php

?> 