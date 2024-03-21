<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;

$app->get('/secure/show_token/{rf_token}', function (Request $request, Response $response,$args) {

$rf_access_token = $args['rf_token'];
$regno = $_SESSION['RegNo'];
$access_token = $_SESSION['AC_TKN'];
//fetch details based o the acess_key and regno
if($rf_access_token != $_SESSION['RF_TKN']){
    $rd =["Message"=>["Status"=>"ERROR","text"=>"REFERER TOKEN ERROR,TRY AGAIN "],"responsedata"=>"404 Error!"];
    $rd = json_encode($rd);
    return $response->withJson($rd,400);
}else{
    $db =  new DB();
    $conn = $db->connect();
    try{
    
        $sql = "SELECT * FROM access_tb a,voters_tb v WHERE a.access_key = :access_token AND a.active = 1 AND a.regno = :regno AND a.regno = v.regno";
        // select a particular user by id
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':access_token', $access_token);
        $stmt->bindParam(':regno', $regno);
        $stmt->execute();
        $access =$stmt->fetch(PDO::FETCH_OBJ); 
        $db=null;//ale
        $vurl = ['Status'=>'SUCCESS','Vkey' => $_SESSION['AC_TKN']."_".$_SESSION['RF_TKN']];
        // die(json_encode($access));
        if($access){
            $e_voting_token = $access->e_voting_token;
            $name = $access->name;
            $access_count = $access->access_count;

            if(isset($e_voting_token)){
                $_SESSION['evoting_token'] = $e_voting_token;
                $_SESSION['name'] = $name;
                // $rd = ["Message"=>["Status"=>"SUCCESS","text"=>"###"],"responsedata"=>["user_name"=>$name,"evoting_token"=>$e_voting_token]];
                // $rd = json_encode($rd);
                //updte the count
                update_access($access_token,$access_count);

                $queryString = http_build_query($vurl);
        // Redirect to show.php with data in the query string
                return $response->withHeader('Location', home_base_url().'views/show.php?' . $queryString)->withStatus(302);
       
            }else{
                $rd =["Message"=>["Status"=>"ERROR","text"=>"No Token Available,Contact Admin"],"responsedata"=>"404 Error!"];
                $rd = json_encode($rd);
                return $response->withJson($rd,400);
            }
        
        }else{
            $rd =["Message"=>["Status"=>"ERROR","text"=>"ACCESS DENIED - INVALID ACCESS TOKEN OR EXPIRED ACCESS TOKEN. PLEASE USE A VALID ACCESS TOKEN"],"responsedata"=>"404 Error!"];
            $rd = json_encode($rd);
            return $response->withJson($rd,400);

        }
        echo json_encode($access);
    }catch(PDOException $e){
        return $response->withJson(["error"=>["text" => $e->getMessage()]],500) ;
    }
}

});