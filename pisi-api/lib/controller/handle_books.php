<?php

require_once("lib/model/db_handler.php");

class handle_books{


    public function get_books(){

        $db_handler = new DBHandler();
    
        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->query('SELECT livros.id, livros.ISBN, livros.titulo, livros.edicao, livros.ano_lancamento FROM livros.livros', PDO::FETCH_ASSOC);

        $result = $db_sta->fetchAll();

        $books = [];

        foreach($result as $row){

            $db_sta = $db_conn->prepare('SELECT autores.nome FROM livros.autores INNER JOIN livrosautores ON livrosautores.autor_id = autores.id WHERE livros.id = :id;');



        }

    }

}

?>