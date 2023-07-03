<?php
$open = fopen("amostra.csv", "r");
$filteredData = [];
 
if ($open !== false){
	while (($commado = fgetcsv($open))!== false){
		if ($commado[0] >10){
			$filteredData[]=$commado;
		}
	}
	fclose($open);
	
}

foreach ($filteredData as $row)
{
	$column=$row[0];
	echo $column ;
	echo "</br>";
}
?>