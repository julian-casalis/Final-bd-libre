$(document).ready(function(){
  console.log("✅ clientes.js cargado correctamente");

  $('.btn-estado').on('click', function(e){
    e.preventDefault(); // evita recarga
    let boton = $(this);
    let id = boton.data('id');
    let estado = boton.data('estado');
    let nuevoEstado = estado == 1 ? 0 : 1;

    console.log("🔹 Click detectado en cliente:", id, "Estado actual:", estado);

    $.ajax({
      url: 'index.php?controller=ClienteController&action=cambiarEstadoCliente',
      type: 'POST',
      data: { id: id, estado: estado },
      success: function(response) {
        console.log("🔹 Respuesta AJAX:", response);
        try {
          let res = JSON.parse(response);
          if (res.success) {
            boton.data('estado', nuevoEstado);
            boton.text(nuevoEstado == 1 ? 'Desactivar' : 'Activar');
            boton.toggleClass('btn-success btn-danger');
            alert('✅ Estado actualizado correctamente');
          } else {
            alert('⚠️ Error: ' + res.message);
          }
        } catch (e) {
          console.error("❌ Error al parsear respuesta:", e);
        }
      },
      error: function(xhr, status, error) {
        console.error("❌ Error AJAX:", status, error);
        alert('Error al actualizar el estado.');
      }
    });
  });
});
