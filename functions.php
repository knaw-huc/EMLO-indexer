<?php

$db = new db();


function formDate($d, $m, $y)
{
    if ($d < 1 || $d > 31) {
        $d = "??";
    } else {
        $d = str_pad($d, 2, "0", STR_PAD_LEFT);
    }
    if ($m < 1 || $m > 12) {
        $m = "??";
    } else {
        $m = str_pad($m, 2, "0", STR_PAD_LEFT);
    }
    if ($y < 500) {
        $y = "????";
    }
    return "$d-$m-$y";
}

function put_mapping()
{
    $mapping = file_get_contents(MAPPING_FILE);
    $ch = curl_init();
    $options = array('Content-type: application/json', 'Content-Length: ' . strlen($mapping));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $options);
    curl_setopt($ch, CURLOPT_URL, MAPPING_URL);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $mapping);
    curl_exec($ch);
    echo "Index mapping sent.\n";
}


function publish($passage, $url)
{
    $json_struc = json_encode($passage);
    $options = array('Content-type: application/json', 'Content-Length: ' . strlen($json_struc));
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $options);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_struc);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
    $response = curl_exec($ch);
    echo $response;
    curl_close($ch);
    //echo "$id indexed\n";
}

function indexPersonItems($count)
{
    global $db;

    //put_mapping();
    $items = $db->get_persons($count);
    foreach ($items as $item) {
        $item = build_person_item($item);
        publish($item, INDEX_URL);
    }
}

function indexLocationItems($count)
{
    global $db;

    put_mapping();
    $items = $db->get_locations($count);
    foreach ($items as $item) {
        $item = build_location_item($item);
        publish($item, INDEX_URL);
    }
}

function indexLetterItems($count)
{
    global $db;

    put_mapping();
    $items = $db->get_letters($count);
    foreach ($items as $item) {
        $item = build_letter_item($item);
        publish($item, INDEX_URL);
    }
}


function build_person_item($emlo_item)
{
    global $db;

    $item = array();
    $id = $emlo_item["person_id"];
    $item["person_id"] = $emlo_item["person_id"];
    $item["primary_name"] = $emlo_item["primary_name"];
    $item["synonyms"] = split_field($emlo_item["synonyms"], "synonym", ";");
    $item["roles_titles"] = split_field($emlo_item["roles_titles"], "title", ";");
    $item["gender"] = set_gender($emlo_item["gender"]);
    $item["is_organisation"] = set_entity($emlo_item["is_organisation"]);
    $item["birth_year"] = $emlo_item["birth_year"];
    $item["death_year"] = $emlo_item["death_year"];
    $item["general_notes"] = $emlo_item["general_notes"];
    $item["editor_working_notes"] = $emlo_item["editor_working_notes"];
    $item["related_resource_id"] = $emlo_item["related_resource_id"];
    $item["uuid"] = $emlo_item["uuid"];
    $item["emlo_url"] = $emlo_item["emlo_url"];

    return $item;
}

function build_location_item($emlo_item)
{
    global $db;

    $item = $emlo_item;
    $item["synonyms"] = split_field($emlo_item["synonyms"], "synonym", "\n");
    return $item;
}

function build_letter_item($emlo_item)
{
    global $db;

    $item = $emlo_item;
    if ($item["origin_emlo_id"] !== "" && $item["origin_emlo_id"] !== null) {
        $buffer = $db->get_letter_location(intval($item["origin_emlo_id"]));
        $item["origin_place"] = $buffer["primary_place_name"];
        $item["origin_province"] = $buffer["province"];
        $item["origin_country"] = $buffer["country"];
    }
    if ($item["destination_emlo_id"] !== "" && $item["destination_emlo_id"] !== null) {
        $buffer = $db->get_letter_location(intval($item["destination_emlo_id"]));
        $item["destination_place"] = $buffer["primary_place_name"];
        $item["destination_province"] = $buffer["province"];
        $item["destination_country"] = $buffer["country"];
    }
    return $item;
}

function set_gender($str)
{
    switch ($str) {
        case 'M':
            return 'Male';
        case 'F':
            return 'Female';
        default:
            return "Unknown";
    }
}

function set_entity($str)
{
    switch ($str) {
        case 'Y':
            return 'Organisation';
        case 'N':
            return 'Person';
        default:
            return "Unknown";
    }
}

function split_field($str, $fName, $char)
{
    $retList = array();

    if (trim($str) == "" || is_null($str)) {
        return $retList;
    }
    $list = explode($char, $str);
    foreach ($list as $item) {
        $retList[] = array($fName => $item);
    }
    return $retList;
}





