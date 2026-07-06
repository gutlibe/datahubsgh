<?php

header("HTTP/1.0 404 Not Found");
view('public/404', ['title' => 'Page Not Found', 'layout' => 'guest']);
