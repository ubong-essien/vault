<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->get('/secure/validate/{access_token}', function (Request $request, Response $response,$args) {
    $max_accesscount = 2;
//    die();
    // $postData = $request->getParsedBody();
    // $queryParams = $request->getQueryParams();
    $access_token = $args['access_token'];

        $db =  new DB();
        $conn = $db->connect();
        
    try{
    $sql = "SELECT * FROM access_tb WHERE access_key = :access_token AND active = 1 AND access_count <= :max_accesscount";
       
        // select a particular user by id
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':access_token', $access_token);
        $stmt->bindParam(':max_accesscount', $max_accesscount);
        $stmt->execute();
        $access =$stmt->fetch(PDO::FETCH_OBJ); 
        $db=null;//ale
        // die(json_encode($access));
        if($access){

            $pass = $access->password;
            $access_count = $access->access_count;
            $regno = $access->regno;
            $csrf_token = generateRandomString($length = 30);
            $_SESSION['RegNo'] = $regno;
            $_SESSION['AC_TKN'] = $access_token;
            $_SESSION['CSRF_TKN'] = $csrf_token;
            $vurl = ['Status'=>'SUCCESS','Vkey' => $_SESSION['AC_TKN']."_".$_SESSION['CSRF_TKN']];
            //CHECK COUNT just incase
            if($access_count >= 2){
                $rd = ["Message"=>["Status"=>"ERROR","text"=>"ERROR...YOU CAN NO LONGER ACCESS THIS LINK"],"responsedata"=>"404 Error!"];
                return $response->withJson($rd,400);
            }

            // $rd = ["Message"=>["Status"=>"SUCCESS","text"=>"Authentication Successfull"],"responsedata"=>["Regno"=>$regno,"access_count"=>$access_count,"crsf_token"=>$csrf_token]];
            $rd = ["Message"=>["Status"=>"SUCCESS","text"=>"Authentication Successfull"],"responsedata"=>[]];

            $queryString = http_build_query($vurl);
            // Redirect to welcome.php with data in the query string
            return $response->withHeader('Location', home_base_url().'views/welcome.php?' . $queryString)->withStatus(302);
           
        }else{
            $rd =["Message"=>["Status"=>"ERROR","text"=>"ACCESS DENIED - INVALID ACCESS TOKEN OR EXPIRED ACCESS TOKEN. PLEASE USE A VALID ACCESS TOKEN"],"responsedata"=>"404 Error!"];
            return $response->withJson($rd,400);
            // $response_data = array("");

        }
        echo json_encode($access);
       

    }catch(PDOException $e){
return $response->withJson(["error"=>["text" => $e->getMessage()]],500) ;
    }
});
