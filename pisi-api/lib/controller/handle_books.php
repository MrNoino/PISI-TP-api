<?php

require_once("lib/model/db_handler.php");

class handle_books{


    public function get_books(){

        $db_handler = new DBHandler();
    
        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->query('SELECT * FROM livros.livros', PDO::FETCH_ASSOC);

        $result = $db_sta->fetchAll();

        return $result;

    }

}

?>