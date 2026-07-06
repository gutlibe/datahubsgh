<?php

session_destroy();
redirect(rtrim($_ENV['APP_URL'], '/') . '/login');
