<?php

require_once("lib/model/db_handler.php");

class handle_categories{

    public function get_categories(){

        $db_handler = new DBHandler();
    
        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->query('SELECT categories.id as "id", categories.category as "name" FROM books.categories', PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

}

?>