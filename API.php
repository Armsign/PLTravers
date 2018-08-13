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
    $deposits = new Deposits();
    
    $email = trim($_GET["email"]);
    
    if (strlen($email) > 0)
    {
        switch ($_GET["method"])
        {
            case ("create"):
                $nomDePlume = trim($_GET["nomDePlume"]);
                $story = trim($_GET["story"]);
                $hasConsent = trim($_GET["hasConsent"]);
                $useEmail = trim($_GET["useEmail"]);                
                
                echo $deposits->CreateStory($email, $nomDePlume, $story, $hasConsent, $useEmail);
                return;            
            case ("nomdeplume"):
                echo $deposits->FetchNomDePlume($email);      
                return;
            case ("history"):
                echo $deposits->ConfirmConditions($email);      
                return;
            case ("instanciate"):   
                echo $deposits->ConfirmConditions($email);
                return;
            default: 
                echo "Deposit Operation Attempted";
                return;
        }
    }
    
    //  Need to confirm the deposit qualifications ...
    echo 'Deposit Operation Attempted, no matching method';
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
        case ("delete"):
            $mySafe = new DaSafe();

            $token = trim($_GET["token"]);              
            $id = trim($_GET["id"]);              
            
            echo json_encode($mySafe->deleteStory($token, $id));
            break;
        case ("update"):
            $mySafe = new DaSafe();

            $token = trim($_GET["token"]);              
            $id = trim($_GET["id"]);              
            $nomDePlume = trim($_GET["nomDePlume"]);            
            $isPlayable = trim($_GET["isPlayable"]);
            $title = trim($_GET["title"]);
            $story = trim($_GET["story"]);
            
            echo json_encode($mySafe->updateStory($token, $id, $nomDePlume, $isPlayable, $title, $story));
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
        default:
            echo 'Administration Attempted';
            break;       
    }
    
    return;
}

function Members()
{    

    switch ($_GET["method"])
    {
        case ("password"):
            $mySafe = new DaSafe();

            $token = trim($_GET["token"]);              
            $id = trim($_GET["id"]);              
            $password = trim($_GET["password"]);   
            
            echo json_encode($mySafe->updatePassword($token, $id, $password));
            break;
        case ("update"):
            $mySafe = new DaSafe();

            $token = trim($_GET["token"]);              
            $id = trim($_GET["id"]);              
            $nomDePlume = trim($_GET["nomDePlume"]);            
            $isPlayable = trim($_GET["isPlayable"]);
            $title = trim($_GET["title"]);
            $story = trim($_GET["story"]);
            
            echo json_encode($mySafe->updateStory($token, $id, $nomDePlume, $isPlayable, $title, $story));
            break;        
        case ("fetch"):
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
        case ("delete"):
            $mySafe = new DaSafe();

            $token = trim($_GET["token"]);              
            $id = trim($_GET["id"]);              
            
            echo json_encode($mySafe->deleteTags($token, $id));
            break;
        case ("update"):
            $mySafe = new DaSafe();

            $token = trim($_GET["token"]);              
            $id = trim($_GET["id"]);              
            $title = trim($_GET["title"]);
            $description = trim($_GET["description"]);
            
            echo json_encode($mySafe->updateTags($token, $id, $title, $description));
            break;        
        case ("fetch"):
            $mySafe = new DaSafe();
            echo json_encode($mySafe->fetchTags());            
            break;        
        default:
            echo 'Tags Attempted';
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
    case ("members"):
        Members();
        break;     
    case ("tags"):
        Tags();
        break;
    default: 
        echo "Bad API Call";
        break;
}