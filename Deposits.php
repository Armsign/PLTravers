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
                
                $returnValue = json_encode($daSafe->updateStory($staffId, $id, $promptId, 0, $email, $nomDePlume, $title, $story, $charDesign, $hasConsent, $useEmail, $isPlayable));
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
    
    public function sendEmails($id, $visitorID, $email) //  Which deposit, visitor and to whom is it going?
    {
        require_once "Mail.php";
        
        //  Let's get this sort right now ...
        $to      = "paul@armsign.com.au";
        $subject = "Maryborough Story Bank";
        $message = "You're too hot. Like, if anything, you're too hot to actually date.";
        $headers = 'From: storybank@maryborough.com.au' . "\r\n" .
                    'Reply-To: storybank@maryborough.com.au' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();   

        if (mail($to, $subject, $message, $headers))
        {
            echo "Sent";            
        } else {
            echo "Fail";        
        }
        
    }
    
    public function createStory($promptID, $email, $visitorID, $nomDePlume, $story, $charDesign, $hasConsent = 0, $useEmail = 0)
    {
        $returnValue = '';
        
        if (strlen($email) > 0 && strlen($nomDePlume) > 0 && strlen($story) > 0)
        {
            $mySafe = new DaSafe();
            
            $returnValue = json_encode($mySafe->updateStory(0, 0, $promptID, $email, $visitorID, $nomDePlume, 'Anon', $story, $charDesign, $hasConsent, $useEmail, 0));
            unset($mySafe);
        }
        
        return $returnValue;
    }    
    
    public function loveStory($deposit, $visitorID)
    {
        $returnValue = '';
        
        if ($deposit > 0 && strlen($visitorID) > 0)
        {   
            $mySafe = new DaSafe();                    
            
            //  Have I already made a deposit?            
            if ($mySafe->IsValidVisitorID($visitorID) &&  $mySafe->IsValidDeposit($deposit))
            {

                if (!$mySafe->IsLovedDeposit($deposit, $visitorID))
                {                
                    //  I havent even liked it once but I have made a deposit
                    $returnValue = json_encode($mySafe->updateMetrics($deposit, $visitorID));                                        
                }
                
            } else {
                
                $returnValue = "Invalid Visitor ID: " . $visitorID . " - " . $deposit;                                        
                
            }
            
            unset($mySafe);
        }
     
        return $returnValue;
    }    
    
    public function depositComments($id)
    {
        $returnValue = '';
        
        if ($deposit > 0)
        {   
            $mySafe = new DaSafe();                    
            
            $returnValue = json_encode($mySafe->updateMetrics($deposit, $visitorID));                                        
            
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

    public function fetchWithdrawalStory($tag, $orderBy)
    {
        $returnArray = '';
        
        if (strlen($tag) > 0 && strlen($orderBy) > 0)
        {
            //  Need to check if this is a user
            $daSafe = new DaSafe();       
            $returnArray = json_encode($daSafe->fetchWithdrawalStory($tag, $orderBy));
            unset($daSafe);
        
        }

        return $returnArray;                
    }        
    
    public function fetchWithdrawalStoryId($id)
    {
        $returnArray = '';
        
        if ($id > 0)
        {
            //  Need to check if this is a user
            $daSafe = new DaSafe();       
            $returnArray = json_encode($daSafe->fetchWithdrawalStoryId($id));
            unset($daSafe);
        
        }

        return $returnArray;                
    }    
    
    
}
