<?php

use App\Classes\Configuration;

$configs = (new Configuration())->getAll();

view('public/home', [
    'title' => 'Welcome', 
    'layout' => 'guest',
    'configs' => $configs
]);
