<?php

require_once 'includes/header.php';
require_once 'includes/navbar.php';

?>

<!-- Content -->
<div class="container-fluid py-5">
  <div class="row">
    <div class="col-md-8 col-sm-12">
      <h2>Productos</h2>
      <div class="row">
        <?php foreach ($data['products'] as $product) : ?>
          <div class="col-md-3 col-sm-12 mb-3">
            <div class="card">
              <img class="card-img-top" src="<?= $product['img'] ?>" alt="<?= $product['name'] ?>">
              <div class="card-body">
                <h6 class="card-title text-truncate"><?= $product['name'] ?></h6>
                <p class="card-text text-danger">
                  <?= formatCurrency($product['price']) ?>
                </p>
                <a href="#" data-toggle="tooltip" data-id="<?= $product['id'] ?>" data-qty="1" title="agregar al carrito" class="btn btn-success add-to-cart"><i class="fa fa-plus "></i> Agregar al Carrito</a>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </div>
    <div class="col-md-4 col-sm-12 bg-light py-2" id="load-wrapper">
      <h2>Carrito</h2>
      <div id="cart-wrapper">
        <!-- Cart content here -->
      </div>
    </div>
  </div>
</div>

<?php
require_once 'includes/footer.php';
?>