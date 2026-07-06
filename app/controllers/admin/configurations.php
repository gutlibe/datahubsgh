<?php

checkAdmin();

use App\Classes\Configuration;

$config = new Configuration();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $configs = $_POST['configs'] ?? [];
    foreach ($configs as $name => $value) {
        $config->update($name, $value);
    }
    $_SESSION['success_message'] = 'Configurations updated successfully!';
    redirect(BASE_URL . '/admin/configurations');
}

$allConfigs = $config->getAll();

view('admin/configurations', ['title' => 'Manage Configurations', 'configs' => $allConfigs, 'layout' => 'admin']);
