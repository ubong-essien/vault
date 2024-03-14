<?php
session_start();
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
// use Slim\Views\PhpRenderer;
// use Slim\Middleware\StaticMiddleware;

require '../vendor/autoload.php';
require '../src/config/db.php';
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);


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
$app->get('/secure/logout/', function (Request $request, Response $response,$args) {

unset( $_SESSION['evoting_token']);
unset( $_SESSION['name']);
unset( $_SESSION['CSRF_TKN']);
unset( $_SESSION['RF_TKN']);
unset( $_SESSION['AC_TKN']);
session_destroy();
 return $response->withHeader('Location', home_base_url().'views/logout.php')->withStatus(302);
});
$app->run();
?>