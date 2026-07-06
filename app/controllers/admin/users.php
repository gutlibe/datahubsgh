<?php

checkAdmin();

use App\Classes\User;

$user = new User();

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'email';

$allUsers = $user->getAll($search, $filter);

view('admin/users', ['title' => 'Manage Users', 'users' => $allUsers, 'layout' => 'admin']);
