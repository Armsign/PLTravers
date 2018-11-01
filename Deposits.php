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
    
    public function updateStory($token, $id, $promptId, $email, $nomDePlume, $title, $story, $charDesign, $hasConsent = 0, $useEmail = 0, $isPlayable = 0)
    {
        //  Need to check if this is a user
        $returnValue = '';
        
        $daSafe = new DaSafe();
        if ($daSafe->IsValidToken($token))
        {                
            //  Get the user's id           
            if (strlen($email) > 0 && strlen($nomDePlume) > 0 && strlen($story) > 0)
            {
                $logins = $daSafe->fetchToken($token);            
                $staffId = $logins[0]["ID"];                            
                
                $returnValue = json_encode($daSafe->updateStory($staffId, $id, $promptId, $email, $nomDePlume, $title, $story, $charDesign, $hasConsent, $useEmail, $isPlayable));
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
    
    public function fetchOldStories($token)
    {
        $returnArray = '';
        
        //  Need to check if this is a user
        $daSafe = new DaSafe();       
        if ($daSafe->IsValidToken($token))
        {
            $returnArray = json_encode($daSafe->fetchOldStories());
        }        
        unset($daSafe);

        return $returnArray;
    }    
    
    public function fetchDeadStories($token)
    {
        $returnArray = '';
        
        //  Need to check if this is a user
        $daSafe = new DaSafe();       
        if ($daSafe->IsValidToken($token))
        {
            $returnArray = json_encode($daSafe->fetchDeadStories());
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
    
    public function fetchStoryTags($token, $id)
    {
        $returnArray = '';
        
        //  Need to check if this is a user
        $daSafe = new DaSafe();       
        if ($daSafe->IsValidToken($token))
        {
            $returnArray = json_encode($daSafe->fetchStoryTags($id));
        }        
        unset($daSafe);

        return $returnArray;                
    }    
        
    /*
     *      Kiosk Functions
     */
    
    public function createStory($promptID, $email, $visitorID = '', $nomDePlume, $story, $charDesign, $hasConsent = 0, $useEmail = 0)
    {
        $returnValue = '';
        
        if (strlen($email) > 0 && strlen($nomDePlume) > 0 && strlen($story) > 0)
        {
            $mySafe = new DaSafe();
            
            $returnValue = json_encode($mySafe->updateStory(0, 0, $promptId, $email, $visitorID, $nomDePlume, 'Anon', $story, $charDesign, $hasConsent, $useEmail, 0));
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
