<?php
session_start();
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;
use Slim\Middleware\StaticMiddleware;

$app = new \Slim\App;

$container = $app->getContainer();

// Register provider on container
$container['renderer'] = new PhpRenderer(__DIR__ . '/views');

function generateRandomString($length = 10) {
    return substr(bin2hex(random_bytes($length)), 0, $length);
}

 function update_access($access_token,$current_count){
        // $max_accesscount = 2;
        $access_count = $current_count + 1;
        if($access_count >= 2){
            $sql = "UPDATE access_tb SET access_count = $access_count,active = 0 WHERE access_token = '$access_token' " ;

        }else{
            $sql = "UPDATE access_tb SET access_count = $access_count WHERE access_token = '$access_token'" ;

        }
        $db =  new DB();
        $db = $db->connect();
        $updt = $db->query($sql);
        $db=null;
        if($updt == True){
            return True;
        }else{
            return False;
        }
    }

$app->get('/secure/validate/{access_token}', function (Request $request, Response $response,$args) {
    $max_accesscount = 2;
   
    // $postData = $request->getParsedBody();
    // $queryParams = $request->getQueryParams();
    $access_token = $args['access_token'];
    // $max_accesscount = 2;
    //validate that the url is valid 
    // connect
        $db =  new DB();
        $conn = $db->connect();
    try{
    $sql = "SELECT * FROM access_tb WHERE access_key = :access_token AND active = 1 AND access_count <= $max_accesscount";
       
        // select a particular user by id
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':access_token', $access_token);
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
            //CHECK COUNT just incase
            if($access_count >= 2){
                $rd = ["Message"=>["Status"=>"ERROR","text"=>"ERROR...YOU CAN NO LONGER ACCESS THIS LINK"],"responsedata"=>"404 Error!"];
                return $response->withJson($rd,400);
            }

            $rd = ["Message"=>["Status"=>"SUCCESS","text"=>"Authentication Successfull"],"responsedata"=>["Regno"=>$regno,"access_count"=>$access_count,"crsf_token"=>$csrf_token]];
           return $response->withJson($rd,200);
           
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


$app->post('/secure/auth', function (Request $request, Response $response) {
    $postData = $request->getParsedBody();
    // $password = $postData['password'];
    // $csrf_tkn = $postData['csrf_token'];
    $password = 'myschool';
    $csrf_tkn = '990f886d1132ef9cfdcb4e09c7a2a2';

    $logged_regno = $_SESSION['RegNo'];
    $access_token = $_SESSION['AC_TKN'];
    //fetch details based o the acess_key and regno
    if($csrf_tkn != $_SESSION['CSRF_TKN']){
        $rd =["Message"=>["Status"=>"ERROR","text"=>"REQUEST TOKEN ERROR,TRY AGAIN "],"responsedata"=>"404 Error!"];
            return $response->withJson($rd,400);
    }
    $db =  new DB();
    $conn = $db->connect();
    try{
    
    $sql = "SELECT * FROM access_tb a,voters_tb v WHERE a.access_key = :access_token AND a.active = 1 AND a.regno = :regno AND a.regno = v.regno";
       
        // select a particular user by id
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':access_token', $access_token);
        $stmt->bindParam(':regno', $logged_regno);
        $stmt->execute();
        $access =$stmt->fetch(PDO::FETCH_OBJ); 
        $db=null;//ale
        // die(json_encode($access));
        if($access){

            // $pass = $access->password;
            // $access_count = $access->access_count;
            // $regno = $access->regno;
            $name = $access->name;
            // $is_ok = password_verify($password,$pass);
            $is_ok = True;
            //CHECK COUNT just incase
            if($is_ok == True){
                // $redirectUrl = '/secure/show_token';
                //create a referal token to ensure the reqest is comiing from the right source
                $rf_token = generateRandomString($length = 20);
                $_SESSION['RF_TKN'] = $rf_token;
                $rd = ["Message"=>["Status"=>"SUCCESS","text"=>"###"],"responsedata"=>["user_name"=>$name,"rf_token"=>$rf_token]];
                return $response->withJson($rd,200);
                // return $response->withRedirect($redirectUrl);

            }else{
                $rd =["Message"=>["Status"=>"ERROR","text"=>"@@@"],"responsedata"=>"404 Error!"];
                return $response->withJson($rd,400);
                // return $response->withRedirect($redirectUrl);
            }

           
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


$app->get('/secure/show_token/{rf_code}', function (Request $request, Response $response,$args) {

    $rf_access_token = $args['rf_token'];
    $regno = $_SESSION['RegNo'];
    $access_token = $_SESSION['AC_TKN'];
    //fetch details based o the acess_key and regno
    if($rf_access_token != $_SESSION['RF_TKN']){
        $rd =["Message"=>["Status"=>"ERROR","text"=>"REFERER TOKEN ERROR,TRY AGAIN "],"responsedata"=>"404 Error!"];
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
            // die(json_encode($access));
            if($access){
                $e_voting_token = $access->e_voting_token;
                $name = $access->name;
                if(isset($e_voting_token)){
                    $rd = ["Message"=>["Status"=>"SUCCESS","text"=>"###"],"responsedata"=>["user_name"=>$name,"evoting_token"=>$e_voting_token]];
                    return $response->withJson($rd,200);
                }else{
                    $rd =["Message"=>["Status"=>"ERROR","text"=>"No Token Available,Contact Admin"],"responsedata"=>"404 Error!"];
                    return $response->withJson($rd,400);
                }
            
            }else{
                $rd =["Message"=>["Status"=>"ERROR","text"=>"ACCESS DENIED - INVALID ACCESS TOKEN OR EXPIRED ACCESS TOKEN. PLEASE USE A VALID ACCESS TOKEN"],"responsedata"=>"404 Error!"];
                return $response->withJson($rd,400);

            }
            echo json_encode($access);
        }catch(PDOException $e){
            return $response->withJson(["error"=>["text" => $e->getMessage()]],500) ;
        }
    }
    
});

// Serve static assets
$app->add(new StaticMiddleware([
    'path' => __DIR__ . '/assets',
    'prefix' => '/assets'
]));

?>