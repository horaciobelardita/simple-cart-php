<?php

require_once  'app/config.php';

/*
$products = getProducts();

$response = [
  'status' => 200,
  'mensaje' => 'respuesta',
  'data' => $products
];

echo json_encode($response);
*/
// Que tipo de peticion
// print_r($_SERVER['REQUEST_METHOD']);

if (!isset($_POST['action'])) {
  // HTTP 403 Forbidden
  echo json_encode(['status' => 403]);
  die();
}

$action = $_POST['action'];
switch ($action) {
  case 'get':
    $cart = getCart();
    $output = '';
    if (!empty($cart['products'])) {
      $output .= '
    <div class="table-responsive">
    <table class="table table-sm table-hover table-striped">
      <thead>
        <tr>
          <th class="text-center">Producto</th>
          <th class="text-center">Precio</th>
          <th class="text-center">Cantidad</th>
          <th class="text-center">Total</th>
        </tr>
      </thead>
      <tbody>
        ';
      foreach ($cart['products'] as $product) {
        $output .=
          '<tr>
          <td class="align-middle text-center">' . $product['name'] .  '</td>
          <td class="align-middle text-center">' . formatCurrency($product['price'], '$') .  '</td>
          <td class="align-middle text-center" width="5%">
            <input type="number" min="0" max="50" value="' . $product['qty'] .  '" class="form-control form-control-sm">
          </td>
          <td class="align-middle text-center">' . formatCurrency($product['qty'] * $product['price'], '$') .  '</td>
          <td class="align-middle text-center text-danger"><i class="fa fa-times"></i></td>
        </tr>';
      }
      $output .= '
      </tbody>
    </table>
  </div>
  <button class="btn btn-sm btn-danger delete-cart btn-block">Vaciar Carrito</button>
    ';
    } else {
      $output .= '<div class="text-center">
      <i class="fa fa-4x fa-shopping-cart"></i>
      <p class="text-muted">No hay productos en el carrito</p>
      <div>';
    }
    $output .= '
      <br><br>
      <table class="table mt-2">
          <tr>
            <th class="border-0">Subtotal</th>
            <td class="border-0 text-success">' . formatCurrency($cart['cart_totals']['subtotal'], '$')  . '</td>
          </tr>
          <tr>
            <th>Envio</th>
            <td class="text-success">' . formatCurrency($cart['cart_totals']['shipment'], '$')  . '</td>
          </tr>
          <tr>
            <th>Total</th>
            <td class="text-success">
              <h3>' . formatCurrency($cart['cart_totals']['total'], '$')   . '</h3>
            </td>
          </tr>
        </table>
        <button disabled class="btn btn-lg btn-info btn-block">Pagar Ahora</button>
      ';
    jsonBuild(200, 'OK', $output);
    break;
    // agregar al carrito
  case 'post':
    // verificar si existe por POST id y cantidad
    if (!isset($_POST['id'], $_POST['qty'])) {
      jsonBuild(403);
    }
    // verificar si se puede agregar al carrito
    if (!addToCart($_POST['id'], $_POST['qty'])) {
      jsonBuild(400, 'No se puede agregar al carrito, intenta de nuevo');
    }
    jsonBuild(201);
    break;
  case 'destroy':
    if (!deleteCart()) {
      jsonBuild(400, 'No se pudo vaciar el carrito');
    }
    jsonBuild(200, 'Carrito eliminado con exito');
    break;
}

function jsonBuild($status = 200, $msg = '', $data = [])
{
  $response = [
    'status' => $status,
    'msg' => $msg,
    'data' => $data
  ];
  http_response_code($status);
  echo json_encode($response);
  die();
}
