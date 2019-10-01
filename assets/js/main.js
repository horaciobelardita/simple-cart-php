$(function() {
  $('[data-toggle="tooltip"]').tooltip();
  // cargar carrito
  loadCart();
  // agregar al carrito

  // actualizar la cantidad
});

function loadCart() {
  const wrapper = $('#cart-wrapper'),
    action = 'get';
  // peticion ajax
  $.ajax({
    url: 'ajax.php',
    type: 'POST',
    dataType: 'JSON',
    data: { action },
    beforeSend: function() {
      wrapper.waitMe();
    }
  })
    .done(function(res) {
      if (res.status == 200) {
        wrapper.html(res.data);
      }
      console.log(res);
    })
    .fail(function(err) {
      console.log(err);
    })
    .always(function() {
      setTimeout(() => wrapper.waitMe('hide'), 2000);
    });
}
