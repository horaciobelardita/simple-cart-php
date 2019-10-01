<?php

function getProducts()
{
  return require APP . 'products.php';
}

function renderView($view, $data = [])
{
  // validar existe vista
  $fileName = VIEWS . $view . '.php';
  if (!is_file($fileName)) {
    die('No existe la vista ' . $view);
  }

  require_once $fileName;
}

function formatCurrency($number, $symbol = '$')
{
  // check if is valid number
  if (!is_float($number) && !is_integer($number)) {
    return 0;
  }
  return $symbol .  number_format($number, 2, ',', '.');
}

function getProductById($id)
{
  $products = getProducts();
  foreach ($products as $k => $v) {
    if ($v['id'] == $id) {
      return $products[$k];
    }
  }
  return false;
}

function getCart()
{
  // si existe el carrito , recalcular y retornarlo
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart']['cart_totals'] = calculateCartTotal();
    return $_SESSION['cart'];
  }
  // sino existe retorna carrito vacio
  $cart = [
    'products' => [],
    'total_products' => 0,
    'cart_totals' => calculateCartTotal(),
  ];
  $_SESSION['cart'] = $cart;
  return $cart;
}

function calculateCartTotal()
{
  // si no existe ningun producto en el carrito retorna totales en 0
  if (!isset($_SESSION['cart']) || empty($_SESSION['cart']['products'])) {
    $cartTotals = [
      'subtotal' => 0,
      'shipment' => 0,
      'total' => 0
    ];
    return $cartTotals;
  }
  // calcular totales del carrito
  $subtotal = 0;
  $shipment = 200;

  // calcular totales
  foreach ($_SESSION['cart']['products'] as $p) {
    $_total = $p['qty'] * $p['price'];
    $subtotal += $_total;
  }
  $cartTotals = [
    'subtotal' => $subtotal,
    'shipment' => $shipment,
    'total' => $subtotal + $shipment
  ];
  return $cartTotals;
}


function addToCart($id, $qty = 1)
{
  $newProduct = [
    'id' => null,
    'sku' => null,
    'name' => null,
    'qty' => null,
    'price' => null,
    'img' => null
  ];
  // verificar que existe el producto
  $product = getProductById($id);

  if (!$product) {
    return false;
  }
  $newProduct = [
    'id' => $product['id'],
    'sku' => $product['sku'],
    'name' => $product['name'],
    'qty' => $qty,
    'price' => $product['price'],
    'img' => $product['price']
  ];
  // si no hay productos en el carrito lo agrego y retorna el carrito
  if (!isset($_SESSION['cart']) || empty($_SESSION['cart']['products'])) {
    $_SESSION['cart']['products'][] = $newProduct;
    return true;
  }

  // verificar si ya existe el producto en el carrito y aumenta en cantidad
  foreach ($_SESSION['cart']['products'] as $i => $p) {
    if ($p['id'] == $id) {
      $p['qty'] += $qty;
      $_SESSION['cart']['products'][$i] = $p;
      return true;
    }
  }
  $_SESSION['cart']['products'][] = $newProduct;
  return true;
}
