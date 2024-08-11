<?php
class db {
    var $con;

    function __construct() {
        $this->con = new mysqli("127.0.0.1", "root", "bonzo", "emlo");
    }

    function get_persons($count) {
        $results = $this->con->query("SELECT * FROM person LIMIT $count");
        return $this->ass_arr($results);
    }

    function get_locations($count) {
        $results = $this->con->query("SELECT `place_id`, `name`, `room`, `building`, `street_parish`, `primary_place_name`, `province`, `country`, `empire`, `synonyms` FROM location LIMIT $count");
        return $this->ass_arr($results);
    }


    private function ass_arr($results) {
        $retArray = array();
        while ($row = $results->fetch_assoc()) {
            $retArray[] = $row;
        }
        return $retArray;
    }
}