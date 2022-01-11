<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

require_once("lib/controller/handle_books.php");

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Bem vindo à API de livros');
        return $response;
    });

    $app->group('/livros', function(Group $group){

        $group->get("", function(Request $request, Response $response){

            $get = $request->getQueryParams();

            $sql = "";


            if(isset($get["titulo"])){

                

            }


        });

        $group->post("", function(Request $request, Response $response){


        });

    });

    
};
