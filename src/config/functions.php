<?php

function generateRandomString($length = 10) {
    return substr(bin2hex(random_bytes($length)), 0, $length);
}

function home_base_url(){   
    $base_url = (isset($_SERVER['HTTPS']) &&
    $_SERVER['HTTPS']!='off') ? 'https://' : 'http://';
    $tmpURL = dirname(__FILE__);
    $tmpURL = str_replace(chr(92),'/',$tmpURL);
    $tmpURL = str_replace($_SERVER['DOCUMENT_ROOT'],'',$tmpURL);
    $tmpURL = ltrim($tmpURL,'/');
    
    $tmpURL = rtrim($tmpURL, '/');
        if (strpos($tmpURL,'/')){
           $tmpURL = explode('/',$tmpURL);
           $tmpURL = $tmpURL[0];
          }
       if ($tmpURL !== $_SERVER['HTTP_HOST'])
          $base_url .= $_SERVER['HTTP_HOST'].'/'.$tmpURL.'/';
        else
          $base_url .= $tmpURL.'/';
    return $base_url; 
        }

 function update_access($access_token,$current_count){
        // $max_accesscount = 2;
        $access_count = $current_count + 1;
        if($access_count >= 2){
            $sql = "UPDATE access_tb SET access_count = :access_count,active = 0 WHERE access_key = :access_token " ;

        }else{
            $sql = "UPDATE access_tb SET access_count = :access_count WHERE access_key = :access_token" ;

        }
        $db =  new DB();
        $conn = $db->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':access_token', $access_token);
        $stmt->bindParam(':access_count', $access_count);
        if($stmt->execute()){
            return True;
        }else{
            return False;
        }
    }