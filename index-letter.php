<?php
error_reporting(E_ALL);
include('db.class.php');
include('functions.php');

define('ITEMS', 50000);
#define("MAPPING_URL", "http://localhost:9200/emlo-letter");
define("MAPPING_URL", "https://n-195-169-89-231.diginfra.net:9200/hi_emlo_letter");
define('INDEX_URL', 'https://n-195-169-89-231.diginfra.net:9200/hi_emlo_letter/_doc');
define("MAPPING_FILE", "mapping-letter.json");

indexLetterItems(ITEMS);
