<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("Tag.php"); 
require_once("Deposit.php"); 
require_once("DaSafe.php"); 


function Deposit()
{
    
    return;
}

function Withdraw()
{
    //  Instanciate and call
    $mySafe = new DaSafe($configs);
    $results = $mySafe->fetchTags();

    echo "Hi there big fella, here's that data you were looking for!";

    echo "<br/>";

    //  Ok and we now should have a connection to the database ...
    //  Be good to return json though, wouldn't it?
    foreach($results as $key => $value)
    {
        echo json_encode($value);
    }

    echo "<br/>";

    echo $_GET['action'] . '!';
    
    
    return;
}

function Administer()
{
    
    return;
}

//  This switch effectively maps to the API calls ... 
switch($_GET["action"])
{
    case ("deposit"):
        Deposit();
        break;
    case ("withdraw"):
        Withdraw();
        break;    
    case ("administer"):
        Administer();
        break;     
    default: 
        echo "Bad API Call";
        break;
}
