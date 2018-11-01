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
     *      Actual Story creation
     */

    public function updateStory($staffId, $id, $promptId = 0, $visitorID = '', $email, $nomDePlume, $title, $story, $charDesign, $hasConsent, $useEmail = 0, $isPlayable = 0)
    {       
        $returnArray = ($id * 1);
        
        //  Validate the token to get the user id
        //  Now I don't really like much of this at all
        if (strlen($nomDePlume) > 0 && strlen($story) > 0)
        {      
            
            $this->mysqli = new mysqli($this->configs["host"], $this->configs["dbUsername"], $this->configs["dbPassword"], $this->configs["dbDatabase"], $this->configs["port"]);

            if ($this->mysqli->connect_errno > 0)
            {
                //  Exit early, failure to connect.
                return "Error: " . $this->mysqli->connect_errno;

            } else {              
                
                if ($id > 0)
                {            
                    $sql = "UPDATE DEPOSITS SET PROMPT_ID = ?, TITLE = ?, STORED_BY = ?, STORED_AS = ?, "
                            . "IS_PLAYABLE = ?, REVIEWED_BY = ?, HAS_CONSENT = ?, USE_EMAIL = ?, "                                                
                            . "REVIEWED_ON = NOW(), TRANSCRIPTION = ?, CHARARCTER_DESIGN = ? WHERE ID = ?;";
                    
                    $stmt = $this->mysqli->prepare($sql);                    
                    $stmt->bind_param('isssiiiissi', $promptId, $title, $email, 
                            $nomDePlume, $isPlayable, $staffId, $hasConsent, 
                            $useEmail, $story, $charDesign, $id);

                } else {
                    
                   $sql = "INSERT INTO DEPOSITS ( PROMPT_ID, "
                        . "TITLE, VISITOR_ID, STORED_BY, STORED_AS, STORED_AT, "
                        . "STORED_ON, AUDIO_TYPE, AUDIO_LENGTH, IS_PLAYABLE, "
                        . "IS_TRANSCRIBED, TRANSCRIPTION, CHARACTER_DESIGN, HAS_CONSENT, USE_EMAIL "
                        . ") VALUES (?, ?, ?, ?, ?, 'N/A', NOW(), 'N/A', 0, ?, "
                        . "1, ?, ?, ?, ?);";                         
                    
                    $stmt = $this->mysqli->prepare($sql);
                    $stmt->bind_param('issssissii', $promptId, $title, $visitorID, $email, $nomDePlume, $isPlayable, $story, $charDesign, $hasConsent, $useEmail);
                    
                    $returnArray = mysqli_insert_id($this->mysqli);
                }

                $stmt->execute();                            
                $stmt->close();                            
            }

            $this->mysqli->close();            
            
            //  We should delete the tags, because they'll be arriving as soon as this is done, yes?
            $sql = "DELETE FROM DEPOSIT_TAGS WHERE DEPOSIT = " . $id . ";";
            $this->transactionalSQL($sql);   
        }        

        return $returnArray;
    }        

    /*
     *  Admin stuff
     */
    
    public function fetchStoryNomDePlume($email)
    {
        return $this->executeSQL("SELECT STORED_AS AS NOMDEPLUME FROM DEPOSITS WHERE STORED_BY = '"  . $email . "' ORDER BY STORED_ON DESC LIMIT 1");        
    }      
    
    public function fetchStoryCount($email)
    {
        return $this->executeSQL("SELECT COUNT(*) AS STORY_COUNT FROM DEPOSITS WHERE STORED_BY = '"  . $email . "'");        
    }      
    
    public function deleteStory($id)
    {
        //  We also need the login ...
        $sql = "DELETE FROM DEPOSITS WHERE ID = " . $id . ";";

        $returnArray = $this->transactionalSQL($sql);                                        

        $sql = "DELETE FROM DEPOSIT_TAGS WHERE DEPOSIT = " . $id . ";";

        $returnArray = $this->transactionalSQL($sql);                                                    

        return $returnArray;
    }  
    
    public function updateTags($token, $id, $title, $description)
    {
        //  Validate the token to get the user id
        $returnArray = array();
        
        if ($this->IsValidToken($token))
        {            
            if ($id > 0)
            {
                
                $sql = "UPDATE TAGS SET "
                    . "TITLE = '" . $title . "', "                 
                    . "DESCRIPTION = '" . $description . "' "
                    . "WHERE ID = " . $id . ";";
                
            } else {

                $sql = "INSERT INTO TAGS ( TITLE, DESCRIPTION ) "
                        . "VALUES ('" . $title . "', '" . $description . "')";
                
            }
            
            $returnArray = $this->transactionalSQL($sql);                                        
        }        

        return $returnArray;
    }      
    
    public function deleteTags($token, $id)
    {
        //  Validate the token to get the user id       
        $returnArray = array();
        
        if ($this->IsValidToken($token))
        {            
            
            $sql = "DELETE FROM TAGS WHERE ID = " . $id . ";";            
            $returnArray = $this->transactionalSQL($sql);                                        
           
            //  We also need the login ...
            $sql = "DELETE FROM DEPOSIT_TAGS WHERE TAG = " . $id . ";";
            $returnArray = $this->transactionalSQL($sql);                                        
            
        }        

        return $returnArray;
    }    
    
    public function createBridge($storyID, $tagID)
    {
        //  Validate the token to get the user id       
        $returnArray = array();
        
        if ($storyID > 0 && $tagID > 0)
        {
            
            $sql = "INSERT INTO DEPOSIT_TAGS (DEPOSIT, TAG) VALUES (" . $storyID . ", " . $tagID . ")";            
            $returnArray = $this->transactionalSQL($sql);                                                  
            
        }        

        return $returnArray;
    }     
    
    public function fetchMembers($token)
    {
        //  Need to check if this is a user
        $logins = $this->fetchToken($token);
        
        $returnArray = array();
        
        if (sizeof($logins) == 1)
        {            
            $returnArray = $this->executeSQL("SELECT ID, EMAIL, IS_ACTIVE, PREFERRED_NAME, SESSION, PASSWORD FROM LOGINS ORDER BY EMAIL ASC");                                        
        }
        
        return $returnArray;        
    }
    
    public function updateMember($id, $email, $preferredName, $isActive)
    {

        if ($id > 0)
        {    
            $sql = "UPDATE LOGINS SET "
                . "EMAIL = '" . $email . "', "                 
                . "IS_ACTIVE = " . $isActive . ", "
                . "PREFERRED_NAME = '" . $preferredName . "' "
                . "WHERE ID = " . $id . ";";

        } else {

            $sql = "INSERT INTO LOGINS ( EMAIL, IS_ACTIVE, PREFERRED_NAME, PASSWORD, SESSION ) "
                    . "VALUES ('" . $email . "', " . $isActive . ", '" . $preferredName . "', '123456', '123456')";

        }

        return $this->transactionalSQL($sql);
    }          
    
    public function updatePassword($token, $id, $password)    
    {
        //  Validate the token to get the user id
        $logins = $this->fetchToken($token);
        
        $returnArray = array();
        
        if (sizeof($logins) == 1)
        {            
            $sanePassword = hash('md5', $password);  
            
            echo "Crypto Password: " . $sanePassword;
            
            $sql = "UPDATE LOGINS SET "
                . "PASSWORD = '" . $sanePassword . "' "
                . "WHERE ID = " . $id . ";";               
            
            echo $sql;
            
            $returnArray = $this->transactionalSQL($sql);                                        
        }        

        return $returnArray;        
    }
    
    public function fetchNewStories()
    {
        $returnArray = $this->executeSQL("SELECT * FROM DEPOSITS WHERE REVIEWED_BY = 0 ORDER BY STORED_ON DESC");                                        

        return $returnArray;
    }
    
    public function fetchOldStories()
    {
        $returnArray = $this->executeSQL("SELECT * FROM DEPOSITS WHERE IS_PLAYABLE = 1 AND REVIEWED_BY > 0 ORDER BY STORED_ON DESC LIMIT 100");                                        

        return $returnArray;
    }    
    
    public function fetchDeadStories()
    {
        $returnArray = $this->executeSQL("SELECT * FROM DEPOSITS WHERE IS_PLAYABLE = 0 AND REVIEWED_BY > 0 ORDER BY STORED_ON DESC LIMIT 100");                                        

        return $returnArray;
    }       
    
    public function fetchFlaggedStories()
    {
        $returnArray = $this->executeSQL(
                "SELECT D.* "
                . "FROM DEPOSIT_FLAGS DF, DEPOSITS D "
                . "WHERE DF.REVIEWED_BY = 0 "
                . "AND DF.IS_INAPPROPRIATE = 1 "
                . "AND DF.DEPOSIT = D.ID "
                . "ORDER BY D.STORED_ON DESC");                                        
        
        return $returnArray;
    }  
    
    public function fetchStoryTags($id)
    {        
        $sql = "SELECT T.* "
                . "FROM DEPOSIT_TAGS DF, TAGS T "
                . "WHERE DF.DEPOSIT = " . $id . " "
                . "AND DF.TAG = T.ID "
                . "ORDER BY T.TITLE ASC  ";
        
        $returnArray = $this->executeSQL($sql);          
        
        return $returnArray;
    }
    
    /*
     *      Fetch all those tags
     */
    public function fetchTags()
    {
        return $this->executeSQL("SELECT ID, TITLE, DESCRIPTION FROM TAGS ORDER BY TITLE ASC");
    }
    
    /*
     *      Member Token Handling
     */
    
    public function IsValidToken($token)
    {       
        if (strlen($token) > 0)
        {        
            $logins =  $this->executeSQL("SELECT ID, EMAIL, IS_ACTIVE, PREFERRED_NAME, PASSWORD, SESSION FROM LOGINS WHERE SESSION = '"  . $token . "' AND IS_ACTIVE = 1");

            if (sizeof($logins) == 1)
            { 
                return true;            
            }
        }        
        
        return false;        
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
     */
    
    public function escapeString($stringToEscape)
    {
        $returnString = '';
        
        $this->mysqli = new mysqli($this->configs["host"], $this->configs["dbUsername"], $this->configs["dbPassword"], $this->configs["dbDatabase"], $this->configs["port"]);                    
        
        if ($this->mysqli->errno > 0)
        {

            return "SQL String Escape Error";
            
        } else {
            
            $returnString = mysqli_escape_string($this->mysqli, $stringToEscape);                
            
        }
        
        $this->mysqli->close();
        
        return $returnString;        
    }       

    private function updateSQL($sql)
    {
        //  Since we're now closing it, we need to always re-open it ... boo.
        $this->mysqli = new mysqli($this->configs["host"], $this->configs["dbUsername"], $this->configs["dbPassword"], $this->configs["dbDatabase"], $this->configs["port"]);
        $this->mysqli->query($sql);
        $this->mysqli->close();
        
        //  And hand back a nice little array       
        return;                
    }

    private function transactionalSQL($sql)
    {
        //  Since we're now closing it, we need to always re-open it ... boo.
        $this->mysqli = new mysqli($this->configs["host"], $this->configs["dbUsername"], $this->configs["dbPassword"], $this->configs["dbDatabase"], $this->configs["port"]);
        
        if ($this->mysqli->connect_errno > 0)
        {
            //  Exit early, failure to connect.
            return "Error: " . $this->mysqli->connect_errno;
            
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
        $this->mysqli = new mysqli($this->configs["host"], $this->configs["dbUsername"], $this->configs["dbPassword"], $this->configs["dbDatabase"], $this->configs["port"]);
        
        if ($this->mysqli->connect_errno > 0)
        {
            
            //  Exit early, failure to connect.
            return "Error: " . $this->mysqli->connect_errno;
            
        } else {        
            $results = $this->mysqli->query($sql);            
        }
        
        $rows = array();
        
        //  Now to format this correctly
        if ($results)
        {
            while($row = $results->fetch_array(MYSQLI_ASSOC))
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