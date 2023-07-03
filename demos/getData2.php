<?php
    $data = new stdClass();

    $data->title = 'Population of Largest U.S. Cities';
    $data->data = array();


    $l1 = array();
    array_push($l1,'City');
    array_push($l1,'2010 Population');
    array_push($l1,'2000 Population');

    $l2 = array();
    array_push($l2,'New York City, NY');
    array_push($l2, 8175000);
    array_push($l2, 8008000);

    $l3 = array();
    array_push($l3, 'Los Angeles, CA');
    array_push($l3, 3792000);
    array_push($l3, 3694000);

    $l4 = array();
    array_push($l4,'Chicago, IL');
    array_push($l4, 2695000);
    array_push($l4, 2896000);

    $l5 = array();
    array_push($l5, 'Houston, TX');
    array_push($l5, 2099000);
    array_push($l5, 1953000);

    $l5 = array();
    array_push($l5, 'Philadelphia, PA');
    array_push($l5, 1526000);
    array_push($l5, 1517000);

    array_push($data->data, $l1);
    array_push($data->data, $l2);
    array_push($data->data, $l3);
    array_push($data->data, $l4);
    array_push($data->data, $l5);

    echo json_encode($data);


?>