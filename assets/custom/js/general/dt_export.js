//print and export buttons for DataTables
var ExportButtons = [
    {
        extend: 'colvis', //column visibility
        className: 'data_export_buttons'
    },
    {
        extend: 'print',
        className: 'data_export_buttons',
        exportOptions: {
            columns: ':visible'
        }
    },
    {
        extend: 'excel',
        className: 'data_export_buttons',
        exportOptions: {
            text: 'Export',
            columns: ':visible'
        }
    },
    {
        extend: 'csv',
        className: 'data_export_buttons',
        exportOptions: {
            columns: ':visible'
        }
    },
    {
        extend: 'pdf',
        className: 'data_export_buttons',
        exportOptions: {
            columns: ':visible'
        }
    }
];