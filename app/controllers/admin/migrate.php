<?php

use App\Classes\Migrator;

echo "<h2>Starting System Migration...</h2>";
echo "<pre>";

try {
    $migrator = new Migrator();
    $migrator->migrate();
    echo "\n<b>Migration process completed successfully.</b>";
} catch (Exception $e) {
    echo "\n<b>Migration failed:</b> " . $e->getMessage();
}

echo "</pre>";
echo '<br><a href="/home">Return to Home</a>';


