<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("Logins.php"); 
require_once("Tags.php"); 
require_once("Deposits.php"); 
require_once("DaSafe.php"); 

function Deposit()
{
    echo 'Deposit Attempted';
    return;
}

function Withdraw()
{
    /*
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
    
     * 
     */
    
    echo 'Withdrawal Attempted';
    return;
}

function Administer()
{    
    switch ($_GET["method"])
    {
        case ("update"):
            
            
            break;
        case ("login"):
            $login = new Logins();                    
            return $login->Authenticate($_GET["email"], $_GET["password"]);
        case ("relogin"):
            $login = new Logins();          
            return $login->ReAuthenticate($_GET["token"]);
        case ("newStories"):
            $mySafe = new DaSafe();            
            echo json_encode($mySafe->fetchNewStories($_GET["token"]));
            break;
        case ("flaggedStories"):
            $mySafe = new DaSafe();            
            echo json_encode($mySafe->fetchFlaggedStories($_GET["token"]));            
            break;
        case ("members"):
            $mySafe = new DaSafe();            
            echo json_encode($mySafe->fetchMembers($_GET["token"]));                        
            break;
        default:
            echo 'Administration Attempted';
            break;       
    }
    
    return;
}

function Tags()
{
     switch ($_GET["method"])
    {
        case ("fetch"):
            $mySage = new DaSafe();
            echo json_encode($mySafe->fetchTags());            
            break;
        case ("login"):
            $login = new Logins();                    
            return $login->Authenticate($_GET["email"], $_GET["password"]);
        case ("relogin"):
            $login = new Logins();          
            return $login->ReAuthenticate($_GET["token"]);
        case ("newStories"):
            $mySafe = new DaSafe();            
            echo json_encode($mySafe->fetchNewStories($_GET["token"]));
            break;
        case ("flaggedStories"):
            $mySafe = new DaSafe();            
            echo json_encode($mySafe->fetchFlaggedStories($_GET["token"]));            
            break;
        case ("members"):
            $mySafe = new DaSafe();            
            echo json_encode($mySafe->fetchMembers($_GET["token"]));                        
            break;
        default:
            echo 'Administration Attempted';
            break;       
    }
    
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
    case ("tags"):
        Tags();
        break;
    default: 
        echo "Bad API Call";
        break;
}
