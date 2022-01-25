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

    $app->group('/books', function (Group $group) {

        $group->get("", function (Request $request, Response $response) {

            $get = $request->getQueryParams();

            $handle_books = new handle_books();

            $params = [];

            $sql = 'SELECT books.id AS "ID", books.ISBN AS "ISBN", books.title AS "Title", books.edition AS "Edition", books.release_year AS "Launch Year" FROM books.books';

            if (isset($get["title"])) {

                $params["title"] = ["value" => $get["title"], "table" => "books"];

            }

            if (isset($get["release_year"])) {

                $params["release_year"] = ["value" => $get["release_year"], "table" => "books"];

            }

            if(isset($get["author"])){

                $sql .= ' INNER JOIN books.booksauthors ON booksauthors.book_id = books.id INNER JOIN books.authors ON authors.id = booksauthors.author_id';

                $params["name"] = ["value" => $get["author"], "table" => "authors"];

            }

            if (isset($get["category"])) {

                $sql .= ' INNER JOIN books.bookscategories ON bookscategories.book_id = books.id INNER JOIN books.categories ON categories.id = bookscategories.category_id ';

                $params["category"] = ["value" => $get["category"], "table" => "categories"];

            }

            
            $response->getBody()->write(json_encode($handle_books->get_books($sql, $params)));
    
            $response = $response->withHeader('Content-type', 'application/json');
    
            return $response;

            



        });

        $group->post("", function (Request $request, Response $response) {

            $handle_books = new handle_books();

            $handle_books->post_book($_POST);

            $response->getBody()->write(json_encode(["Mensagem"=> "Livro adicionado com sucesso"], JSON_FORCE_OBJECT));
    
            $response = $response->withHeader('Content-type', 'application/json');
    
            return $response;

        });
    });
};
