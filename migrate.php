<?php

require_once __DIR__ . '/config/bootstrap.php';

use App\Classes\Migrator;

$migrator = new Migrator();
$migrator->migrate();
