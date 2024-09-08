<?php

class db
{
    var $con;

    function __construct()
    {
        $this->con = new mysqli("127.0.0.1", "root", "bonzo", "emlo");
    }

    function get_persons($count)
    {
        $results = $this->con->query("SELECT * FROM person LIMIT $count");
        return $this->ass_arr($results);
    }

    function get_locations($count)
    {
        $results = $this->con->query("SELECT `place_id`, `name`, `room`, `building`, `street_parish`, `primary_place_name`, `province`, `country`, `empire`, `synonyms` FROM location LIMIT $count");
        return $this->ass_arr($results);
    }

    function get_letter_location($id) {
        $result = $this->ass_arr($this->con->query("SELECT `primary_place_name`, `province`, `country` FROM `location` WHERE place_id = $id"));
        return $result[0];
    }

    function get_letters($count)
    {
        $results = $this->ass_arr($this->con->query("SELECT `emlo_letter_id`, `year`, `standard_gregorian_date`, `author`, `recipient`, `origin_name`, `destination_name`, `origin_emlo_id`, `destination_emlo_id` FROM `work` LIMIT $count"));
        return $results;
    }



    private function ass_arr($results)
    {
        $retArray = array();
        while ($row = $results->fetch_assoc()) {
            $retArray[] = $row;
        }
        return $retArray;
    }
}