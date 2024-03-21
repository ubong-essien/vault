<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;
$app->get('/secure/logout/', function (Request $request, Response $response,$args) {

    unset( $_SESSION['evoting_token']);
    unset( $_SESSION['name']);
    unset( $_SESSION['CSRF_TKN']);
    unset( $_SESSION['RF_TKN']);
    unset( $_SESSION['AC_TKN']);
    session_destroy();
     return $response->withHeader('Location', home_base_url().'views/logout.php')->withStatus(302);
    });