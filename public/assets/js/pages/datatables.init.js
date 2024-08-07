/******/ (() => { // webpackBootstrap
    var __webpack_exports__ = {};
    /*!***********************************************!*\
      !*** ./resources/js/pages/datatables.init.js ***!
      \***********************************************/
    /*
    Template Name: Minible - Admin & Dashboard Template
    Author: Themesbrand
    Website: https://themesbrand.com/
    Contact: themesbrand@gmail.com
    File: Datatables Js File
    */
    $(document).ready(function () {
      $('#datatable').DataTable(); //Buttons examples

      var table = $('#datatable-buttons').DataTable({
        lengthChange: false,
        buttons: [
          {
            extend: 'copy',
            className: 'btn btn-warning', // Clase Bootstrap para color verde
            exportOptions: {
              columns: ':not(:last-child)' // Excluir la última columna (acciones) de la exportación
            }
          },
          {
            extend: 'excel',
            className: 'btn btn-success', // Clase Bootstrap para color verde
            exportOptions: {
              columns: ':not(:last-child)' // Excluir la última columna (acciones) de la exportación
            }
          },
          {
            extend: 'pdf',
            className: 'btn btn-danger', // Clase Bootstrap para color verde
            exportOptions: {
              columns: ':not(:last-child)' // Excluir la última columna (acciones) de la exportación
            }
          },
          {
            extend: 'colvis',
            className: 'btn btn-primary' // Clase Bootstrap para color verde
          }
        ]
      });

      table.buttons().container().appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
      $(".dataTables_length select").addClass('form-select form-select-sm');
    });
    /******/ })()
    ;
