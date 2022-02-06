<?php

require_once("lib/model/db_handler.php");

class handle_books{

    public function get_books($sql, $params){

        $db_handler = new DBHandler();
    
        $db_conn = $db_handler->get_connection();

        $sql .= (count($params) > 0) ? " WHERE " : "";

        foreach($params as $key => $value){

            $sql .= $value["table"] . "." . $key  . (is_string($value["value"]) ? " like :$key and " : " = :$key and ");

        }

        $sql = (count($params) > 0) ? substr($sql, 0, -4) : $sql;

        $db_sta = $db_conn->prepare($sql);

        foreach($params as $key => $value){

            $db_sta->bindValue(":$key", (is_string($value["value"]) ? "%" . $value["value"] . "%" : $value["value"]));

        }

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        $books_result = $db_sta->fetchAll();

        $books = [];

        foreach($books_result as $row){

            $db_sta = $db_conn->prepare('SELECT authors.name AS "name" FROM books.authors INNER JOIN books.booksauthors ON booksauthors.author_id = authors.id WHERE booksauthors.book_id = :id;');

            $db_sta->bindValue(":id", $row["id"]);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_ASSOC);

            $authors_result = $db_sta->fetchAll();

            $row["authors"] = $authors_result;

            $db_sta = $db_conn->prepare('SELECT categories.category AS "category" FROM books.categories INNER JOIN books.bookscategories ON bookscategories.category_id = categories.id WHERE bookscategories.book_id = :id;');

            $db_sta->bindValue(":id", $row["id"]);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_ASSOC);

            $categories_result = $db_sta->fetchAll();

            $row["categories"] = $categories_result;

            $books[] = $row;

        }

        return $books;

    }


    public function post_book($params){

        $db_handler = new DBHandler();
    
        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare('INSERT INTO books.books (books.ISBN, books.title, books.edition, books.release_year) 
                                    VALUES (:isbn, :title, :edition, :release_year)');

        $db_sta->bindValue(":isbn", $params["isbn"]);

        $db_sta->bindValue(":title", $params["title"]);
        
        $db_sta->bindValue(":edition", $params["edition"]);

        $db_sta->bindValue(":release_year", $params["release_year"]);

        $db_sta->execute();

        $book_id = $db_conn->lastInsertId();

        if(isset($params["authors"])){

            foreach($params["authors"] as $value){

                $db_sta = $db_conn->prepare('INSERT INTO books.booksauthors (booksauthors.book_id, booksauthors.author_id) 
                                        VALUES (:book_id, :author_id)');

                $db_sta->bindValue(":book_id", $book_id);

                $db_sta->bindValue(":author_id", $value);

                $db_sta->execute();

            }

        }

        if(isset($params["categories"])){

            foreach($params["categories"] as $value){

                $db_sta = $db_conn->prepare('INSERT INTO books.bookscategories (bookscategories.book_id, bookscategories.category_id) 
                                        VALUES (:book_id, :category_id)');

                $db_sta->bindValue(":book_id", $book_id);

                $db_sta->bindValue(":category_id", $value);

                $db_sta->execute();
    
            }

        }

    }

}

?>