<?php
error_reporting(E_ALL);
include('db.class.php');
include('functions.php');

define('ITEMS', 10000);
define("MAPPING_URL", "http://localhost:9200/emlo-person");
define('INDEX_URL', 'http://localhost:9200/emlo-person/_doc');
define("MAPPING_FILE", "mapping-person.json");

indexPersonItems(ITEMS);