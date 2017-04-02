<?php

error_reporting(E_ALL);
ini_set("display_errors", "on");

require_once __DIR__ . '/src/Application.php';

$app = new \CMS\Application();
$app->run();