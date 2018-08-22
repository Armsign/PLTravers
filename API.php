<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("Logins.php"); 
require_once("Tags.php"); 
require_once("Deposits.php"); 

function Deposit()
{
    
    //    action=depost
    //    &method=update
    //    &token=c4fbb242c5ba845c3271e660fefe45d8072814c412d044e52cb530a6fe7e65a1
    //    &id=0
    //    &nomDePlume=Anon
    //    &isPlayable=0
    //    &title=Title
    //    &story=Story
    
    $deposits = new Deposits();

    $token = trim($_GET["token"]);                  
    $id = trim($_GET["id"]);                  
    $email = trim($_GET["email"]);
    $nomDePlume = trim($_GET["nomDePlume"]);                
    $title = trim($_GET["title"]);
    $story = trim($_GET["story"]);      
    $hasConsent = trim($_GET["hasConsent"]);
    $useEmail = trim($_GET["useEmail"]);        
    $isPlayable = trim($_GET["isPlayable"]);
        
    switch ($_GET["method"])
    { 
        
        case ("update"):
            echo $deposits->updateStory($token, $id, $nomDePlume, $isPlayable, $title, $story);
            break;        
        case ("delete"):
            echo $deposits->deleteStory($token, $id);
            break;        
        case ("consent"):
            echo $deposits->hasConsent($email);
            break;        

        case ("create"):
            echo $deposits->createStory($email, $nomDePlume, $story, $hasConsent, $useEmail);
            break;        
        case ("nomdeplume"):
            echo $deposits->fetchNomDePlume($email);
            break;        
        case ("newStories"):
            echo $deposits->fetchNewStories($_GET["token"]);
            break;
        case ("flaggedStories"):
            echo $deposits->fetchFlaggedStories($_GET["token"]);            
            break;          

        
        /*
       
        case ("delete"):
            $mySafe = new DaSafe();

            $token = trim($_GET["token"]);              
            $id = trim($_GET["id"]);              

            echo json_encode($deposits->deleteStory($token, $id));
            break;                                
        case ("nomdeplume"):
            echo $deposits->FetchNomDePlume($email);      
            break;
        case ("history"):
            echo $deposits->ConfirmConditions($email);      
            break;
        case ("instanciate"):   
            echo $deposits->ConfirmConditions($email);
            break;

               
            */
        
        default: 
            echo "Deposit Operation Attempted";
            break;
    }

    unset($deposits);

    return;
}

function Withdraw()
{    
    echo 'Withdrawal Attempted';
    return;
}

function Administer()
{    
    $login = new Logins();                    

    switch ($_GET["method"])
    {       
        case ("login"):
            echo $login->Authenticate($_GET["email"], $_GET["password"]);
            break;
        case ("relogin"):
            echo $login->ReAuthenticate($_GET["token"]);
            break;
        default:
            echo 'Administration Attempted';
            break;       
    }
    
    unset($login);
    
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
            $email = trim($_GET["email"]);            
            $preferredName = trim($_GET["preferredName"]);
            $isActive = trim($_GET["isActive"]);                            
            
            echo json_encode($mySafe->updateMember($token, $id, $email, $preferredName, $isActive));
            break;        
        case ("fetch"):
            $mySafe = new DaSafe();            
            echo json_encode($mySafe->fetchMembers($_GET["token"]));                        
            break;
        default:
            echo 'Members Attempted';
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