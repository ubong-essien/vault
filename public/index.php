<?php
session_start();
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
// use Slim\Views\PhpRenderer;
// use Slim\Middleware\StaticMiddleware;

require '../vendor/autoload.php';
require '../src/config/db.php';
require '../src/config/functions.php';


// $configuration = [
//     'settings' => [
//         'displayErrorDetails' => true,
//     ],
// ];

// $c = new \Slim\Container($configuration); 
// $app = new \Slim\App($c);
$app = new \Slim\App;

$app->get('/home', function (Request $request, Response $response) {
   die("This is the home");
});

require '../src/routes/validate.php';
require '../src/routes/auth.php';
require '../src/routes/show_token.php';
require '../src/routes/logout.php';

$app->run();
?>