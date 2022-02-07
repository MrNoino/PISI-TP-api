<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

require_once("lib/controller/handle_books.php");

require_once("lib/controller/handle_authors.php");

require_once("lib/controller/handle_categories.php");

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        
        return $response;

    });

    $app->get('/', function (Request $request, Response $response) {

        $response->getBody()->write('Bem vindo à API de livros');
        return $response;
        
    });

    $app->group('/books', function (Group $group) {

        $group->get("/", function (Request $request, Response $response) {

            $get = $request->getQueryParams();

            $handle_books = new handle_books();

            $params = [];

            $sql = 'SELECT books.id AS "id", books.ISBN AS "isbn", books.title AS "title", books.edition AS "edition", books.release_year AS "release_year" FROM books.books';

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

            $books = $handle_books->get_books($sql, $params);

            if(count($books) > 0){

                $response->getBody()->write(json_encode($books));

            }else{

                $response = $response->withStatus(404);

                $response->getBody()->write(json_encode(["message" => "Nenhum livro encontrado"], JSON_FORCE_OBJECT));

            }
    
            $response = $response->withHeader('Content-type', 'application/json');
    
            return $response;

        });

        $group->post("/", function (Request $request, Response $response) {

            $handle_books = new handle_books();

            if(empty($_POST["isbn"]) || empty($_POST["title"]) || empty($_POST["edition"]) || empty($_POST["release_year"]) || empty($_POST["authors"]) || empty($_POST["categories"])){

                $response = $response->withStatus(400);

                $response->getBody()->write(json_encode(["message" => "Pedido mal formado"], JSON_FORCE_OBJECT));
    
                $response = $response->withHeader('Content-type', 'application/json');

                return $response;

            }

            $handle_books->post_book($_POST);

            $response->getBody()->write(json_encode(["message" => "Livro adicionado com sucesso"], JSON_FORCE_OBJECT));
    
            $response = $response->withHeader('Content-type', 'application/json');
    
            return $response;

        });
    });

    $app->get("/authors/", function (Request $request, Response $response) {

        $handle_authors = new handle_authors();

        $authors = $handle_authors->get_authors();

        if(count($authors) > 0){

            $response->getBody()->write(json_encode($authors));

        }else{

            $response = $response->withStatus(404);

            $response->getBody()->write(json_encode(["message" => "Nenhum autor encontrado"], JSON_FORCE_OBJECT));

        }

        $response = $response->withHeader('Content-type', 'application/json');
    
        return $response;
        
    });

    $app->get("/categories/", function (Request $request, Response $response) {

        $handle_categories = new handle_categories();

        $categories = $handle_categories->get_categories();

        if(count($categories) > 0){

            $response->getBody()->write(json_encode($categories));

        }else{

            $response = $response->withStatus(404);

            $response->getBody()->write(json_encode(["message" => "Nenhuma categoria encontrada"], JSON_FORCE_OBJECT));

        }
        
        $response = $response->withHeader('Content-type', 'application/json');
    
        return $response;
        
    });

};
