<?php

use Wumvi\Sqlite3Dao\{DbManager};

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/Test.php';

$dbDao = new Test(new DbManager(__DIR__ . '/identifier.sqlite'));
$dbDao->insert();

$info = $dbDao->fetchAll();
var_dump($info);

$info = $dbDao->fetchFirst();
var_dump($info);

