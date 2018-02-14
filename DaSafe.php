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
     *      Fetch the Admin view? Or create something a bit less stupids, maybe?
     *      You know I'm asking for an async data table that pages naturally right ...
     *      Perhaps that's a bit overkillish for this one though ;p
     *      Anyways ... all things considered it's not such a big deal.
     */
    
    
    
    
    
    
    
    
    
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