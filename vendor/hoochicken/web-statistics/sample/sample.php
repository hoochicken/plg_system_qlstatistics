<?php

use Hoochicken\ParameterBag\ParameterBag;
use Hoochicken\WebStatistics\WebStatistics;

require_once '../../../autoload.php';

$host = 'localhost';
$database = 'users';
$user = 'root';
$password = 'root';

$statisticsTable = 'webstats3';

$webstatistics = new WebStatistics();
$webstatistics->initDb($host, $database, $user, $password);
$webstatistics->setServer(new ParameterBag($_SERVER));
$webstatistics->setTable($statisticsTable);
if (!$webstatistics->tableExists()) {
    $webstatistics->createTable();
}
$webstatistics->setSessionId(session_id());
$webstatistics->addEntry();

print_r($webstatistics->getData());

