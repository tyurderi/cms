<?php

error_reporting(E_ALL);
ini_set("display_errors", "on");

$app = require_once __DIR__ . '/src/Application.php';
$app->run();