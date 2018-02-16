<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("DaSafe.php"); 

/**
 * Description of Login
 *
 * @author PaulDunn
 */
class Logins 
{
    
    public function Authenticate($email, $password)
    {
        if (strlen($email) > 0)
        {    
            $mySafe = new DaSafe();
        
            //  Santize that stuff!
            $saneEmail = $mySafe->escapeString($email);
            $sanePassword = hash('md5', $password);        
            
            $results = $mySafe->fetchLogin($saneEmail);
            
            foreach ($results as $key => $value)
            {                
                if ($value['PASSWORD'] == $sanePassword && $value['IS_ACTIVE'] == 1)
                {
                    //  OK ... let's just swap out the password and swap in the session token ....
                    $value['PASSWORD'] = 'OBFUSCATED';
                    $value['SESSION'] = hash('sha256', $saneEmail . $sanePassword);        
                    
                    //  Record the new login
                    $mySafe->updateToken($value['EMAIL'], $value['SESSION']);
                    
                    //  Return all the goods!
                    echo json_encode($value);
                    return;
                }
            }                        
        }
                        
        echo 0;
        return;
    }
    
    public function ReAuthenticate($token)
    {
        if (strlen($token) > 0)
        {    
            $mySafe = new DaSafe();
            
            $results = $mySafe->fetchToken($token);
            
            foreach ($results as $key => $value)
            {                
                $value['PASSWORD'] = 'OBFUSCATED';
                //  Return all the goods!
                echo json_encode($value);
                return;
            }                        
        }
                        
        echo 0;
        return;
    }    
    
    
    
}
