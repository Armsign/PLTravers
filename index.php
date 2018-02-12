<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
            include('DaSafe.php');
        
            $myVault = new DaSafe();
            
            
            $results = $myVault->fetchTags();
            
            foreach($results as $key => $value)
            {
                var_dump($value);    
            }
        
        ?>
    </body>
</html>
