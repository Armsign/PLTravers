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
    public function CreateStory()
    {
        
        return 'Unavailable';
    }
    
    public function ConfirmConditions()
    {
        
        return 'Unavailable';        
    }
        
}
