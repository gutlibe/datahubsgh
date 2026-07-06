<?php

use App\Classes\Product;
use App\Classes\Configuration;

$config = (new Configuration())->getAll();

$product = new Product();
$allProducts = $product->getAll();

$productsByNetwork = [];
foreach ($allProducts as $p) {
    $network = strtolower($p['network']);
    $isEnabled = (bool)($config["enable_{$network}_purchase"] ?? true);
    
    if ($isEnabled) {
        $productsByNetwork[$p['network']][] = $p;
    }
}

view('public/shop', [
    'title' => 'Shop', 
    'productsByNetwork' => $productsByNetwork, 
    'layout' => 'app'
]);
