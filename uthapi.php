<?php


  /* This code generates the json values from the web service on the uthgard herald website. Uthgard strangely 
     does not like the angular / javascript ajax calls, but is fine with the way PHP and Python call their 
     web services, so here we are.
     I also wanted to do some more work with the data - saving it in a mySQL database to use to track players over
     time as they were searched and be able to graph progress. */


  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  $uthUrl = "https://www2.uthgard.net/herald/api/";
  $conn = new mysqli("localhost", "", "", "");
  
  $name = '';
  $lastUpdated = '';
  $xp = '';
  $rp = '';
  $realm = '';
  $class = '';
  $race = '';
  $guildname = '';
  $level = '';
  $realmrank = '';

  if(isset($_GET['player']))
  {
     $name = ucfirst(strtolower($_GET['player']));
     $uthUrl .= 'player/' . $name;

 

  $json = file_get_contents($uthUrl.$jsonurl); 
  
  $pos = strrpos($json, "Warning");
  if($pos === false){
  
      $decoded_json = json_decode($json, true);
       while(list($key, $val) = each($decoded_json)){

         
          if($key === 'Name')
          {
              $name = $val;
          }
          if($key === 'LastUpdated')
          {
              $lastUpdated = $val;
          }
          if($key === 'Xp')
          {
              $xp = $val;
          }
          if($key === 'Rp')
          {
              $rp = $val;
          }
          if($key === 'Guild')
          {
              $guildname = $val;
          }

        if($key === 'Race'){
           $race = $val;
        }
        if($key === 'Class'){
           $class = $val;
        }
         if($key === 'Level'){
           $level = $val;
        }
         if($key === 'RealmRank'){
           $realmrank = $val;
        }
      }
       $result = $conn->query("SELECT distinct name, last_update, experience, realm_points, class, race, guild_name, level, realm_rank FROM player_history WHERE name = '$name' and last_update = '$lastUpdated' order by last_update asc limit 7");
       if(mysqli_num_rows($result)>0){
       }
       else
       {
     
       $query = "
            INSERT INTO `player_history` (`name`, `last_update`, `experience`, `realm_points`, `class`, `race`,`guild_name`,`level`,`realm_rank` ) VALUES ('$name',
            '$lastUpdated', '$xp', '$rp', '$class', '$race', '$guildname','$level','$realmrank');";             
            if($conn->query($query) === TRUE){
             
            }
            else{
                echo "Error: " . $query . "<br> " . $conn->error;
            } 
        } 
         echo($json);        
  }
  else{
      echo("{'Message': 'No User Found'}");   
  }
  
} 

 if(isset($_GET['history']))
  {
     $name = ucfirst(strtolower($_GET['history']));
     $result = $conn->query("SELECT distinct name, last_update, experience, realm_points, class, race, guild_name, level, realm_rank FROM player_history WHERE name = '$name' order by last_update desc limit 7");
     $arr = [];
       while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
         array_push($arr, $rs);
      
      }  
      $rev_arr = array_reverse($arr);
     echo (json_encode($rev_arr));
  } 
  
  if(isset($_GET['top']))
  {
     $type = strtolower($_GET['top']);
     $uthUrl .= 'top/' . $type;

 

     $json = file_get_contents($uthUrl.$jsonurl); 
  
     $pos = strrpos($json, "Warning");
     
     if($pos === false){
  
         $decoded_json = json_decode($json, true);
         
          while(list($key, $val) = each($decoded_json)){;
                if(is_array($val)){
                    while(list($key1, $val1) = each($val)){
                      if($key1 === 'Name')
                      {
                          $name = $val1;
                      }
                      if($key1 === 'LastUpdated')
                      {
                          $lastUpdated = $val1;
                      }
                      if($key1 === 'Xp')
                      {
                          $xp = $val1;
                      }
                      if($key1 === 'Rp')
                      {
                          $rp = $val1;
                      }
                      if($key1 === 'Guild')
                      {
                          $guildname = $val1;
                      }
            
                    if($key1 === 'Race'){
                       $race = $val1;
                    }
                    if($key1 === 'Class'){
                       $class = $val1;
                    }
                     if($key1 === 'Level'){
                       $level = $val1;
                    }
                     if($key1 === 'RealmRank'){
                       $realmrank = $val1;
                    }
              }
              $result = $conn->query("SELECT distinct name, last_update, experience, realm_points, class, race, guild_name, level, realm_rank FROM player_history WHERE name = '$name' and last_update = '$lastUpdated' order by last_update asc limit 7");
               if(mysqli_num_rows($result)>0){
               }
               else
               {
             
               $query = "
                    INSERT INTO `player_history` (`name`, `last_update`, `experience`, `realm_points`, `class`, `race`,`guild_name`,`level`,`realm_rank` ) VALUES ('$name',
                    '$lastUpdated', '$xp', '$rp', '$class', '$race', '$guildname','$level','$realmrank');";             
                    if($conn->query($query) === TRUE){
                     
                    }
                    else{
                        echo "Error: " . $query . "<br> " . $conn->error;
                    } 
                } 
            }
     
      }
       
         
         
         
         
         
         echo($json); 
      }
      else{
          echo("{'Message': 'We had a problem!'}");   
      }
  }
 
?>