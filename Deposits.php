<?php


/**
 * Description of Deposit
 *
 * @author PaulDunn
 */
class Deposits
{
    
    /*
    public $id = 0;
    public $storedBy = '';
    public $storedAt = '';
    public $storedOn = '';
    public $audioType = '';
    public $auidioLength = 0;
    public $tags = array();
    */
    
    //  Originally intended for audio, huh. Well.
    
    public function createFullStory($id, $email, $nomDePlume, $story, $hasConsent, $useEmail)
    {
        $returnValue = '';
        
        if (strlen($email) > 0 && strlen($nomDePlume) > 0 && strlen($story) > 0)
        {
            $mySafe = new DaSafe();
            $returnValue = json_encode($mySafe->depositStory($email, $nomDePlume, $story, $hasConsent, $useEmail));            
        }
        
        return $returnValue;
    }    
    
    private function updateStory($email, $nomDePlume, $story, $hasConsent = 0, $useEmail = 0)
    {
        $returnValue = '';
        
        if (strlen($email) > 0 && strlen($nomDePlume) > 0 && strlen($story) > 0)
        {
            $mySafe = new DaSafe();
            $returnValue = json_encode($mySafe->depositStory($email, $nomDePlume, $story, $hasConsent, $useEmail));            
        }
        
        return $returnValue;
    }    
    
    
    
    
    
    
    
    public function createStory($email, $nomDePlume, $story, $hasConsent = 0, $useEmail = 0)
    {
        $returnValue = '';
        
        if (strlen($email) > 0 && strlen($nomDePlume) > 0 && strlen($story) > 0)
        {
            $mySafe = new DaSafe();
            $returnValue = json_encode($mySafe->depositStory($email, $nomDePlume, $story, $hasConsent, $useEmail));            
            unset($mySafe);
        }
        
        return $returnValue;
    }    
    
    public function confirmConditions($email)
    { 
        
        $returnValue = '';
        
        if (strlen($email) > 0)
        {        
            $mySafe = new DaSafe();
        
            $returnValue = json_encode($mySafe->fetchStoryCount($email));            
            
            unset($mySafe);
        }
        
        
        return $returnValue;      
    }
    
    public function fetchNomDePlume($email)
    {
        $returnValue = '';
        
        if (strlen($email) > 0)
        {        
            $mySafe = new DaSafe();
        
            $returnValue = json_encode($mySafe->fetchStoryNomDePlume($email));            
            
            unset($mySafe);
        }

        return $returnValue;      
    }
    
   public function fetchNewStories($token)
    {
        //  Need to check if this is a user
        $daSafe = new DaSafe();
       
        if ($daSafe->IsValidToken())
        {
            $returnArray = json_encode($daSafe->fetchNewStories());
        }
        
        unset($daSafe);

        return $returnArray;
    }
    
    public function fetchFlaggedStories($token)
    {
        //  Need to check if this is a user
        $daSafe = new DaSafe();
       
        if ($daSafe->IsValidToken())
        {
            $returnArray = json_encode($daSafe->fetchFlaggedStories());            
        }
        
        unset($daSafe);
        
        return $returnArray;
    }        
        
}
