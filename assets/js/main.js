$(function() {
  $('[data-toggle="tooltip"]').tooltip();
  // cargar carrito
  loadCart();
  // agregar al carrito
  $('.add-to-cart').on('click', function(event) {
    event.preventDefault();
    // get data id
    const id = $(this).data('id'),
      action = 'post',
      qty = $(this).data('qty');

    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'JSON',
      cache: false,
      data: {
        action,
        id,
        qty
      },
      beforeSend: function() {
        console.log('agregando');
      }
    })
      .done(function(res) {
        if (res.status == 201) {
          Swal.fire('Bien hecho!', 'Producto agregado con exito!', 'success');
          loadCart();
          return;
        }
        console.log(res);
      })
      .fail(function(err) {
        Swal.fire('Ups', err.msg, 'error');
        console.log(err);
      })
      .always(function() {});
  });
  // vaciar carrito
  $('body').on('click', '.delete-cart', deleteCart);
  // eliminar producto del carrito
  $('body').on('click', '.delete-from-cart', deleteFromCart);

  // actualizar cantidad de un producto
  $('body').on('blur', '.update-from-cart', updateFromCart);
});

function updateFromCart(event) {
  const input = $(this),
    id = input.data('id'),
    action = 'put',
    oldQty = input.data('qty');
  let qty = Number(input.val());

  // validar si es numero entero
  if (!Math.floor(qty) === qty || isNaN(qty)) qty = 1;
  // validar que sea una nueva cantidad
  if (qty == oldQty) return false;
  // validar cantidad > 0 & cantidad < 99
  if (qty == 0) {
    qty = 1;
  } else if (qty < 99) {
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'JSON',
      data: {
        action,
        id,
        qty
      },
      beforeSend: function() {
        console.log('actualizando..');
      }
    })
      .done(function(res) {
        if (res.status == 200) {
          Swal.fire(
            'Bien hecho!',
            'Producto actualizado con exito!',
            'success'
          );
          loadCart();
          return;
        }
        console.log(res);
      })
      .fail(function(err) {
        Swal.fire('Ups', err.msg, 'error');
        console.log(err);
      })
      .always(function() {});
  }
}

function deleteFromCart(event) {
  event.preventDefault();
  const id = $(this).data('id');
  Swal.fire({
    title: '¿Esta seguró de eliminar el producto?',
    text: 'Este cambio no se podra revertir!',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, eliminar'
  }).then(result => {
    if (result.value) {
      const action = 'delete';
      $.ajax({
        url: 'ajax.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
          action,
          id
        }
      })
        .done(function(res) {
          if (res.status == 200) {
            Swal.fire('Eliminado!', res.msg, 'success');
            loadCart();
            return;
          } else {
            Swal.fire('Upps', res.msg, 'error');
            return;
          }
        })
        .fail(function() {
          Swal.fire('Upps', 'Hubo un error, intentelo de nuevo', 'error');
        })
        .always(function() {});
    }
  });
}

function deleteCart(event) {
  event.preventDefault();
  Swal.fire({
    title: '¿Esta seguró de eliminar el carrito?',
    text: 'Este cambio no se podra revertir!',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, eliminar'
  }).then(result => {
    if (result.value) {
      const action = 'destroy';
      $.ajax({
        url: 'ajax.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
          action
        }
      })
        .done(function(res) {
          if (res.status == 200) {
            Swal.fire('Eliminado!', res.msg, 'success');
            loadCart();
            return;
          } else {
            Swal.fire('Upps', res.msg, 'error');
            return;
          }
        })
        .fail(function() {
          Swal.fire('Upps', 'Hubo un error, intentelo de nuevo', 'error');
        })
        .always(function() {});
    }
  });
}

function loadCart() {
  const wrapper = $('#cart-wrapper'),
    loadWrapper = $('#load-wrapper'),
    action = 'get';
  // peticion ajax
  $.ajax({
    url: 'ajax.php',
    type: 'POST',
    dataType: 'JSON',
    data: { action },
    beforeSend: function() {
      loadWrapper.waitMe();
    }
  })
    .done(function(res) {
      if (res.status == 200) {
        wrapper.html(res.data);
      } else {
        wrapper.html('Intenta de nuevo, por favor!');
        return true;
      }
      console.log(res);
    })
    .fail(function(err) {
      console.log(err);
    })
    .always(function() {
      setTimeout(() => loadWrapper.waitMe('hide'), 2000);
    });
}
