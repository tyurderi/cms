<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$app     = require_once __DIR__ . '/src/Application.php';
$console = new \Symfony\Component\Console\Application('CMS Console Commands', '1.0.0');

$console->addCommands([
    new \CMS\Commands\PluginListCommand(),
    new \CMS\Commands\PluginInstallCommand(),
    new \CMS\Commands\PluginUninstallCommand()
]);

$console->run();