<?php

require_once("lib/model/db_handler.php");

class handle_authors{

    public function get_authors(){

        $db_handler = new DBHandler();
    
        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->query('SELECT authors.id as "id", authors.name as "name" FROM books.authors', PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

}

?>