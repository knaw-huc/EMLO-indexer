<?php
error_reporting(E_ALL);
include('db.class.php');
include('functions.php');

define('ITEMS', 50000);
define("MAPPING_URL", "http://localhost:9200/emlo-letter");
define('INDEX_URL', 'http://localhost:9200/emlo-letter/_doc');
define("MAPPING_FILE", "mapping-letter.json");

indexLetterItems(ITEMS);
