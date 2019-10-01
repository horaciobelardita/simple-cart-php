<?php
require_once 'app/config.php';


$data = [
  'products' => getProducts()
];


renderView('cart_view', $data);
