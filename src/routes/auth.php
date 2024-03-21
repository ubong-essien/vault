<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;

$app->post('/secure/auth', function (Request $request, Response $response) {
    //die("am hererererere");
    $postData = $request->getParsedBody();
    $password = $postData['pass_word'];
    $csrf_tkn = $postData['crsf_token'];
    $logged_regno = $_SESSION['RegNo'];
    $access_token = $_SESSION['AC_TKN'];
    //fetch details based o the acess_key and regno
    if($csrf_tkn != $_SESSION['CSRF_TKN']){
        $rd =["Message"=>["Status"=>"ERROR","text"=>"REQUEST TOKEN ERROR,TRY AGAIN "],"responsedata"=>"404 Error!"];
            return $response->withJson($rd,400);
    }else{
        $db =  new DB();
        $conn = $db->connect();
        try{
        
        $sql = "SELECT * FROM access_tb a,voters_tb v WHERE a.access_key = :access_token AND a.active = 1 AND a.regno = :regno AND a.regno = v.regno";
        
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':access_token', $access_token);
            $stmt->bindParam(':regno', $logged_regno);
            $stmt->execute();
            $access =$stmt->fetch(PDO::FETCH_OBJ); 
            $db=null;//ale
            // die(json_encode($access));
            if($access){

                $pass = $access->password;
                $access_count = $access->access_count;
                // $regno = $access->regno;
                $name = $access->name;
                $is_ok = password_verify($password,$pass);
                // var_dump($is_ok);die;
                
                if($is_ok == True){
                    //create a referal token to ensure the reqest is comiing from the right source
                    $rf_token = generateRandomString($length = 20);
                    $_SESSION['RF_TKN'] = $rf_token;
                    // $svurl = ['Status'=>'SUCCESS','Vkey' => $_SESSION['RF_TKN']."_".$_SESSION['CSRF_TKN']];
                    $_SESSION['stud_name'] = $name;

                    $rd = ["Message"=>["Status"=>"SUCCESS","text"=>"###"],"responsedata"=>["user_name"=>$name,"rf_token"=>$rf_token]];
                    $rd = json_encode($rd);
                    
               return $response->withJson($rd,200);

                }else{
                    $rd =["Message"=>["Status"=>"ERROR","text"=>"@@@"],"responsedata"=>"404 Error!"];
                    $rd1 = json_encode($rd);
                    die($rd1);
                    // return $response->withJson($rd1,400);
                    // return $response->withRedirect($redirectUrl);
                }

            
            }else{
                $rd =["Message"=>["Status"=>"ERROR","text"=>"ACCESS DENIED - INVALID ACCESS TOKEN OR EXPIRED ACCESS TOKEN. PLEASE USE A VALID ACCESS TOKEN"],"responsedata"=>"404 Error!"];
                $rd = json_encode($rd);
                return $response->withJson($rd,400);
                // $response_data = array("");

            }
            echo json_encode($access);
        }catch(PDOException $e){
    return $response->withJson(["error"=>["text" => $e->getMessage()]],500) ;
        }
    } 
});

