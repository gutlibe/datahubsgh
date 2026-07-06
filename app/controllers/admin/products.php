<?php

require_once __DIR__ . '/../../../config/bootstrap.php';

use App\Classes\Product;

$productInstance = new Product();
$products = $productInstance->getAllProductsForAdmin();

view("admin/products", ["products" => $products, "layout" => "admin"]);
