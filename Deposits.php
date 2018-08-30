<?php


/**
 * Description of Deposit
 *
 * @author PaulDunn
 */
class Deposits
{
    
    /*
     *      Admin functions
     */
    
    private function updateStory($token, $id, $promptId, $email, $nomDePlume, $title, $story, $hasConsent = 0, $useEmail = 0, $isPlayable = 0)
    {
        //  Need to check if this is a user
        $returnValue = '';
        
        $daSafe = new DaSafe();
        if ($daSafe->IsValidToken($token))
        {                
            //  Get the user's id           
            if (strlen($email) > 0 && strlen($nomDePlume) > 0 && strlen($story) > 0)
            {
                $logins = $daSafe->fetchLogin($email);            
                $staffId = $logins[0]["ID"];                            
                
                $returnValue = json_encode($mySafe->updateStory($staffId, $id, $promptId, $email, $nomDePlume, $title, $story, $hasConsent, $useEmail, $isPlayable));            
            }
        }
        unset($daSafe);
        
        return $returnValue;
    }    
    
    public function deleteStory($token, $id)
    {
        $returnArray = '';
        
        //  Need to check if this is a user
        $daSafe = new DaSafe();       
        if ($daSafe->IsValidToken($token))
        {
            $returnArray = json_encode($daSafe->deleteStory($id));
        }        
        unset($daSafe);

        return $returnArray;                
    }
    
    public function fetchNewStories($token)
    {
        $returnArray = '';
        
        //  Need to check if this is a user
        $daSafe = new DaSafe();       
        if ($daSafe->IsValidToken($token))
        {
            $returnArray = json_encode($daSafe->fetchNewStories());
        }        
        unset($daSafe);

        return $returnArray;
    }
    
    public function fetchFlaggedStories($token)
    {
        $returnArray = '';
        
        //  Need to check if this is a user
        $daSafe = new DaSafe();
        if ($daSafe->IsValidToken($token))
        {
            $returnArray = json_encode($daSafe->fetchFlaggedStories());            
        }
        unset($daSafe);
        
        return $returnArray;
    }  
    
    /*
     *      Kiosk Functions
     */
    
    public function createStory($promptID, $email, $nomDePlume, $story, $hasConsent = 0, $useEmail = 0)
    {
        $returnValue = '';
        
        if ($promptID > 0 && strlen($email) > 0 && strlen($nomDePlume) > 0 && strlen($story) > 0)
        {
            $mySafe = new DaSafe();
            $returnValue = json_encode($mySafe->depositStory($promptID, $email, $nomDePlume, $story, $hasConsent, $useEmail));            
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
    
    public function hasConsent($email)
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
                
}
