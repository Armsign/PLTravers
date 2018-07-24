<?php

/**
 * Description of DaSafe
 *
 * @author PaulDunn
 */
class DaSafe 
{
    private $configs = null;
    private $mysqli = null;
    
    /*
     *      Constructor!
     */
    public function __construct() 
    {
        $this->configs = include('conf.php');
    }
    
    /*
     *      Let's start pulling the relevant data
     */
    
    public function fetchStoryNomDePlume($email)
    {
        return $this->executeSQL("SELECT STORED_AS AS NOMDEPLUME FROM DEPOSITS WHERE STORED_BY = '"  . $email . "' ORDER BY STORED_ON DESC LIMIT 1");        
    }            
    
    public function depositStory($email, $nomDePlume, $story, $hasConsent, $useEmail)
    {
        $sql = "INSERT INTO DEPOSITS ( "
                . "TITLE, STORED_BY, STORED_AS, STORED_AT, "
                . "STORED_ON, AUDIO_TYPE, AUDIO_LENGTH, IS_PLAYABLE, "
                . "IS_TRANSCRIBED, TRANSCRIPTION, HAS_CONSENT, USE_EMAIL "
                . ") VALUES ("
                . "'No Title Supplied', '" . $email . "', '" . $nomDePlume . "', '', "
                . "NOW(), '', 0, 0, "
                . "1, '" . $story . "', " . $hasConsent . ", " . $useEmail . ");";
        
        return $this->transactionalSQL($sql);                
    }       

    /*
     *      Admin stuff
     */
    
    public function fetchMembers($token)
    {
        //  Need to check if this is a user
        $logins = $this->fetchToken($token);
        
        $returnArray = array();
        
        if (sizeof($logins) == 1)
        {
            $returnArray = $this->executeSQL("SELECT ID, EMAIL, IS_ACTIVE, PREFERRED_NAME, SESSION FROM LOGINS ORDER BY EMAIL ASC");                                        
        }
        
        return $returnArray;        
    }
    
    public function fetchNewStories($token)
    {
        //  Need to check if this is a user
        $logins = $this->fetchToken($token);
        
        $returnArray = array();
        
        if (sizeof($logins) == 1)
        {
            $returnArray = $this->executeSQL("SELECT ID, TITLE, STORED_BY, STORED_ON, AUDIO_TYPE, AUDIO_LENGTH, TRANSCRIPTION, RECEIVED_BY, RECEIVED_ON FROM DEPOSITS WHERE RECEIVED_BY = 0 ORDER BY STORED_ON DESC");                                        
        }
        
        return $returnArray;
    }
    
    public function fetchFlaggedStories($token)
    {
        //  Need to check if this is a user
        $logins = $this->fetchToken($token);
        
        $returnArray = array();
        
        if (sizeof($logins) == 1)
        {
            $returnArray = $this->executeSQL(
                    "SELECT D.ID, D.TITLE, D.STORED_BY, D.STORED_ON, D.AUDIO_TYPE, D.AUDIO_LENGTH, D.TRANSCRIPTION, D.RECEIVED_BY, D.RECEIVED_ON "
                    . "FROM DEPOSIT_FLAGS DF, DEPOSITS D "
                    . "WHERE DF.REVIEWED_BY = 0 "
                    . "AND DF.IS_INAPPROPRIATE = 1 "
                    . "AND DF.DEPOSIT = D.ID "
                    . "ORDER BY D.STORED_ON DESC");                                        
        }
        
        return $returnArray;
    }    
    
    /*
     *      Fetch all those tags
     */
    public function fetchTags()
    {
        return $this->executeSQL("SELECT ID, TITLE, DESCRIPTION FROM TAGS ORDER BY TITLE ASC");
    }
    

    public function fetchLogin($email)
    {
        return $this->executeSQL("SELECT ID, EMAIL, IS_ACTIVE, PREFERRED_NAME, PASSWORD, SESSION FROM LOGINS WHERE EMAIL = '"  . $email . "' AND IS_ACTIVE = 1");
    }    
    
    public function fetchToken($token)
    {
        return $this->executeSQL("SELECT ID, EMAIL, IS_ACTIVE, PREFERRED_NAME, PASSWORD, SESSION FROM LOGINS WHERE SESSION = '"  . $token . "' AND IS_ACTIVE = 1");
    }    
        
    
    public function updateToken($email, $token)
    {
        $this->updateSQL("UPDATE LOGINS SET SESSION = '" . $token . "' WHERE EMAIL = '" . $email . "'");        
    }
    
    /*
     *      Lowest level code
     *          'dbDatabase' => 'StoryVault',
     */
    
    public function escapeString($stringToEscape)
    {
        $this->mysqli = new mysqli($this->configs["host"], $this->configs["dbUsername"], $this->configs["dbPassword"], $this->configs["dbDatabase"]);                    
        
        $returnString = mysqli_escape_string($this->mysqli, $stringToEscape);
        
        $this->mysqli->close();
        
        return $returnString;        
    }       

    private function updateSQL($sql)
    {
        //  Since we're now closing it, we need to always re-open it ... boo.
        $this->mysqli = new mysqli($this->configs["host"], $this->configs["dbUsername"], $this->configs["dbPassword"], $this->configs["dbDatabase"]);
        $this->mysqli->query($sql);
        $this->mysqli->close();
        
        //  And hand back a nice little array       
        return;                
    }

    private function transactionalSQL($sql)
    {
  //  Since we're now closing it, we need to always re-open it ... boo.
        $this->mysqli = new mysqli($this->configs["host"], $this->configs["dbUsername"], $this->configs["dbPassword"], $this->configs["dbDatabase"]);
        
        if ($this->mysqli->connect_errno > 0)
        {
            //  Exit early, failure to connect.
            return null;
            
        } else {        
            $this->mysqli->query($sql);            
        }
        
        $this->mysqli->close();
        
        //  And hand back a nice little array       
        return true;           
    }
    
    private function executeSQL($sql)
    {        
        //  Since we're now closing it, we need to always re-open it ... boo.
        $this->mysqli = new mysqli($this->configs["host"], $this->configs["dbUsername"], $this->configs["dbPassword"], $this->configs["dbDatabase"]);
        
        if ($this->mysqli->connect_errno > 0)
        {
            //  Exit early, failure to connect.
            return null;
            
        } else {        
            $results = $this->mysqli->query($sql);            
        }
        
        $rows = array();
        
        //  Now to format this correctly
        if ($results)
        {
            while($row = $results->fetch_array())
            {
                $rows[] = $row;
            }

            //  Tidy everything up since we're stateless now ... boo.
            $results->close();        
        }
        
        $this->mysqli->close();
        
        //  And hand back a nice little array       
        return $rows;        
    }    
    
}