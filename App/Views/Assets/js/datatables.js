// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
      pageLength : 5,
      lengthMenu: [[5, 20, 50, 100], [5, 20, 50, 100]],
      language: {
              "decimal": "",
              "emptyTable": "No hay información",
              "info": "Mostrando _START_ a _END_ de _TOTAL_ Tareas",
              "infoEmpty": "Mostrando 0 to 0 of 0 Tareas",
              "infoFiltered": "(Filtrado de _MAX_ total tareas)",
              "infoPostFix": "",
              "thousands": ",",
              "lengthMenu": "Mostrar _MENU_ Tareas",
              "loadingRecords": "Cargando...",
              "processing": "Procesando...",
              "search": "Buscar:",
              "zeroRecords": "Sin resultados encontrados",
              "paginate": {
                  "first": "Primero",
                  "last": "Ultimo",
                  "next": "Siguiente",
                  "previous": "Anterior"
              }
      }
  });
});
