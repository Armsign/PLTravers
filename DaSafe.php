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
    
    public function updateStory($token, $id, $nomDePlume, $isPlayable, $title, $story)
    {
        //  Validate the token to get the user id
        //  Now I don't really like much of this at all
        $logins = $this->fetchToken($token);
        
        $returnArray = array();
        
        if (sizeof($logins) == 1 && strlen($nomDePlume) > 0 && strlen($story) > 0)
        {      
            if ($id > 0)
            {            
           
                //  We also need the login ...
                $sql = "UPDATE DEPOSITS SET "
                    . "STORED_AS = '" . $nomDePlume . "', " 
                    . "TITLE = '" . $title . "', " 
                    . "IS_PLAYABLE = " . $isPlayable . ", "
                    . "REVIEWED_BY = " . $logins[0]["ID"] . ", "  
                    . "REVIEWED_ON = NOW(), "                    
                    . "TRANSCRIPTION = '" . $story . "' "
                    . "WHERE ID = " . $id . ";";
                
            } else {
                
                //  Not really adequate ... does it require a 2-stage auth process
                $sql = "INSERT INTO DEPOSITS ( "
                    . "TITLE, STORED_BY, STORED_AS, STORED_AT, "
                    . "STORED_ON, AUDIO_TYPE, AUDIO_LENGTH, IS_PLAYABLE, "
                    . "IS_TRANSCRIBED, TRANSCRIPTION, HAS_CONSENT, USE_EMAIL "
                    . ") VALUES ("
                    . "'" . $title . "', '" . $email . "', '" . $nomDePlume . "', '', "
                    . "NOW(), '', 0, 0, "
                    . "1, '" . $story . "', 1, 0);";                                
                
            }
            
            $returnArray = $this->transactionalSQL($sql);                                        
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
    
    public function deleteStory($token, $id)
    {
        //  Validate the token to get the user id
        $logins = $this->fetchToken($token);
        
        $returnArray = array();
        
        if (sizeof($logins) == 1)
        {            
            //  We also need the login ...
            $sql = "DELETE FROM DEPOSITS WHERE ID = " . $id . ";";
            
            $returnArray = $this->transactionalSQL($sql);                                        
            
            $sql = "DELETE FROM DEPOSIT_TAGS WHERE DEPOSIT = " . $id . ";";
            
            $returnArray = $this->transactionalSQL($sql);                                                    
        }        

        return $returnArray;
    }  
    
    public function updateTags($token, $id, $title, $description)
    {
        //  Validate the token to get the user id
        $logins = $this->fetchToken($token);
        
        $returnArray = array();
        
        if (sizeof($logins) == 1)
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
        $logins = $this->fetchToken($token);
        
        $returnArray = array();
        
        if (sizeof($logins) == 1)
        {            
            
            $sql = "DELETE FROM TAGS WHERE ID = " . $id . ";";            
            $returnArray = $this->transactionalSQL($sql);                                        
           
            //  We also need the login ...
            $sql = "DELETE FROM DEPOSIT_TAGS WHERE TAG = " . $id . ";";
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
    
    public function updateMember($token, $id, $email, $preferredName, $isActive)
    {
        //  Validate the token to get the user id
        $logins = $this->fetchToken($token);
        
        $returnArray = array();
        
        if (sizeof($logins) == 1 && strlen($email) > 0 && strlen($preferredName) > 0)
        {            
            if ($id > 0)
            {    
                $sql = "UPDATE LOGINS SET "
                    . "EMAIL = '" . $email . "', "                 
                    . "IS_ACTIVE = " . $isActive . ", "
                    . "PREFERRED_NAME = '" . $preferredName . "' "
                    . "WHERE ID = " . $id . ";";
                
            } else {

                $sql = "INSERT INTO LOGINS ( EMAIL, IS_ACTIVE, PREFERRED_NAME, PASSWORD ) "
                        . "VALUES ('" . $email . "', " . $isActive . ", '" . $preferredName . "', '123456')";
                
            }
            
            $returnArray = $this->transactionalSQL($sql);                                        
        }        

        return $returnArray;
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
        $returnArray = $this->executeSQL("SELECT ID, TITLE, STORED_BY, STORED_AS, STORED_AT, STORED_ON, AUDIO_TYPE, AUDIO_LENGTH, IS_PLAYABLE, IS_TRANSCRIBED, TRANSCRIPTION, HAS_CONSENT, USE_EMAIL, REVIEWED_BY, REVIEWED_ON FROM DEPOSITS WHERE REVIEWED_BY = 0 ORDER BY STORED_ON DESC");                                        

        return $returnArray;
    }
    
    public function fetchFlaggedStories()
    {
        $returnArray = $this->executeSQL(
                "SELECT D.ID, D.TITLE, D.STORED_BY, D.STORED_ON, D.AUDIO_TYPE, D.AUDIO_LENGTH, D.TRANSCRIPTION, D.RECEIVED_BY, D.RECEIVED_ON "
                . "FROM DEPOSIT_FLAGS DF, DEPOSITS D "
                . "WHERE DF.REVIEWED_BY = 0 "
                . "AND DF.IS_INAPPROPRIATE = 1 "
                . "AND DF.DEPOSIT = D.ID "
                . "ORDER BY D.STORED_ON DESC");                                        
        
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