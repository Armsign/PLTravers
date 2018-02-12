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
     *      Fetch all those tags
     */
    public function fetchTags()
    {
        
        return $this->executeSQL("SELECT ID, TITLE, DESCRIPTION FROM TAGS ORDER BY TITLE ASC");
    }
    
    /*
     *      Lowest level code
     */
    private function executeSQL($sql)
    {
        if ($this->mysqli === null)
        {
            $this->mysqli = new mysqli($this->configs["host"], $this->configs["dbUsername"], $this->configs["dbPassword"], "storyBank");                    
        } 
        
        return $this->mysqli->query($sql);        
    }
    
}