<?php


/**
 * Description of Deposit
 *
 * @author PaulDunn
 */
class Deposits
{
    
    public $id = 0;
    public $storedBy = '';
    public $storedAt = '';
    public $storedOn = '';
    public $audioType = '';
    public $auidioLength = 0;
    public $tags = array();
    
    //  Originally intended for audio, huh. Well.
    public function CreateStory($email, $nomDePlume, $story, $hasConsent = 0, $useEmail = 0)
    {
        $returnValue = '';
        
        if (strlen($email) > 0 && strlen($nomDePlume) > 0 && strlen($story) > 0)
        {
            $mySafe = new DaSafe();
            $returnValue = json_encode($mySafe->depositStory($email, $nomDePlume, $story, $hasConsent, $useEmail));            
        }
        
        return $returnValue;
    }
    
    public function ConfirmConditions($email)
    { 
        $mySafe = new DaSafe();
        
        $returnValue = json_encode($mySafe->fetchStoryCount($email));            
        
        return $returnValue;      
    }
    
    public function FetchNomDePlume($email)
    {
        $mySafe = new DaSafe();
        
        $returnValue = json_encode($mySafe->fetchStoryNomDePlume($email));            
        
        return $returnValue;      
    }
        
}
