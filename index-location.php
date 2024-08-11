<?php
error_reporting(E_ALL);
include('db.class.php');
include('functions.php');

define('ITEMS', 10000);
define("MAPPING_URL", "http://localhost:9200/emlo-location");
define('INDEX_URL', 'http://localhost:9200/emlo-location/_doc');
define("MAPPING_FILE", "mapping-location.json");

indexLocationItems(ITEMS);