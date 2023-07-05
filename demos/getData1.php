<?php
    $data = new stdClass();

    $data->title = 'How Much Pizza I Ate Last Night';
    $data->columns = array();
    

    $c1 = new stdClass();
    $c1->type = 'string';
    $c1->name = 'Topping';

    $c2 = new stdClass();
    $c2->type = 'number';
    $c2->name = 'Slices';

    array_push($data->columns, $c1);
    array_push($data->columns, $c2);

    $data->lines = array();

    $l1 = array();
    array_push($l1,'Mushrooms');
    array_push($l1,3);

    $l2 = array();
    array_push($l2,'Onions');
    array_push($l2,1);

    $l3 = array();
    array_push($l3,'Olives');
    array_push($l3,1);

    $l4 = array();
    array_push($l4,'Zucchini');
    array_push($l4,1);

    $l5 = array();
    array_push($l5,'Pepperoni');
    array_push($l5,2);

    array_push($data->lines, $l1);
    array_push($data->lines, $l2);
    array_push($data->lines, $l3);
    array_push($data->lines, $l4);
    array_push($data->lines, $l5);

    echo json_encode($data);


?>