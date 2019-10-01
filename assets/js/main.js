$(function () {
  $('[data-toggle="tooltip"]').tooltip();
  // cargar carrito
  loadCart();
  // agregar al carrito
  $('.add-to-cart').on('click', function (event) {
    event.preventDefault();
    // get data id
    const id = $(this).data('id'), action = 'post', qty = $(this).data('qty');

    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      dataType: 'JSON',
      cache: false,
      data: {
        action, id, qty
      },
      beforeSend: function () {
        console.log('agregando')
      }
    }).done(function (res) {
      if (res.status == 201) {
        Swal.fire('Bien hecho!', 'Producto agregado con exito!', 'success');
        loadCart();
        return;
      }
      console.log(res);
    })
      .fail(function (err) {
        Swal.fire('Ups', err.msg, 'error');
        console.log(err);
      })
      .always(function () {
      });

  })
  // actualizar la cantidad
});

function loadCart() {
  const wrapper = $('#cart-wrapper'), loadWrapper = $('#load-wrapper'),
    action = 'get';
  // peticion ajax
  $.ajax({
    url: 'ajax.php',
    type: 'POST',
    dataType: 'JSON',
    data: { action },
    beforeSend: function () {
      loadWrapper.waitMe();
    }
  })
    .done(function (res) {
      if (res.status == 200) {
        wrapper.html(res.data);

      } else {
        wrapper.html('Intenta de nuevo, por favor!');
        return true;
      }
      console.log(res);
    })
    .fail(function (err) {
      console.log(err);
    })
    .always(function () {
      setTimeout(() => loadWrapper.waitMe('hide'), 2000);
    });
}
