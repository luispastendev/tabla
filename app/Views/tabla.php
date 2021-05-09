<!DOCTYPE html>
<html lang="es-pe">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/plugins/bootstrap.min.css">
    <link rel="stylesheet" href="/plugins/dataTables.bootstrap5.min.css">

    <title>Tablas</title>
</head>
<body>

    <div class="container">
        <h1 class="display-6 text-center mt-5">Tabla Server Rendering</h1>
        <div class="table-responsive">
            <table id="datos" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <?php
                            foreach(config('TableConfig')->fields as $v){
                                echo "<th>{$v}</th>";
                            }
                        ?>
                    </tr>
            
                </thead>
                <tfoot>
                    <tr>
                        <?php
                            foreach(config('TableConfig')->fields as $v){
                                echo "<th>{$v}</th>";
                            }
                        ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

<script src="/plugins/jquery-3.6.0.js"></script>
<script src="/plugins/jquery.dataTables.js"></script>
<script src="/plugins/dataTables.buttons.min.js"></script>
<script src="/plugins/dataTables.bootstrap5.min.js"></script>
<script src="/plugins/excel/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {

    let column = 0; 
    let currentFilters = [];
    let defaultColumns = <?= json_encode($columns) ?>;


    $('#datos thead tr').clone(true).appendTo( '#datos thead' );
    $('#datos thead tr:eq(1) th').each(function (i) {
        
        var title   = $(this).text();
        var select  = reRenderFilters(defaultColumns, title,false);

        $(this).html(select);
        
        $( 'select', this ).on( 'change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        });
                
        column++
    });

    $('#datos thead tr:eq(1) th select').change(function (o) {

        currentFilters.push({
            column: parseInt($(this).attr('data-column')), 
            val: $(this).val()
        });

        $.post( "/reRenderColumns", table.ajax.params() ,function( data ) {
            column = 0;
            $('table thead tr:eq(1) th select').each(function(i,v){

                let newValues = reRenderFilters(data,v.id,true);
                $(v).empty().append(newValues);
                column++;
            });
            
        });
    });

    var table = $('#datos').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/getData",
        orderCellsTop: true,
        // fixedHeader: true,
        pageLength: 10,
        // responsive: true,
        scrollX: true,
        responsive: {
            breakpoints: [
            {name: 'bigdesktop', width: Infinity},
            {name: 'meddesktop', width: 1480},
            {name: 'smalldesktop', width: 1280},
            {name: 'medium', width: 1188},
            {name: 'tabletl', width: 1024},
            {name: 'btwtabllandp', width: 848},
            {name: 'tabletp', width: 768},
            {name: 'mobilel', width: 480},
            {name: 'mobilep', width: 320}
            ]
        },
        dom: 'lBfrtip',
        buttons: [
            {
                text: 'Exportar a excel',
                className: 'btn btn-success mt-2',
                action: function ( e, dt, node, config ) {
                    $.post( "/excelGenerate", this.ajax.params() ,function( data ) {
                        JSONToCSVConvertor(data, "Reporte de datos", true);
                    });
                    
                },
            },
            {
                text: 'Borrar Filtros',
                className: 'btn btn-warning mt-2',
                action: function ( e, dt, node, config ) {
                    currentFilters.length = 0;
                    column = 0;

                    $('table thead tr:eq(1) th select').each(function(i,v){
                        let newValues = reRenderFilters(defaultColumns,v.id,true);
                        $(v).empty().append(newValues).change();
                        column++;
                    });

                },
            }
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
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
        },   
    });

    function reRenderFilters(columns, label, onlyOptions){
        let select = ``;

        if(!onlyOptions){
            select = `<select id="${label}" data-column="${column}">`;
        }

        select += `<option value='' selected="selected">Filtro: ${label}</option>`
        
        let data = columns[column];

        if(typeof data === 'object' && data !== null){
            data = Object.values(data);
        }   

        for (let item of data) {

            let hasFilter = currentFilters.filter(f => f.val === item && f.column === column);

            select += `<option value="${item}" ${hasFilter.length > 0 ? 'selected' : ''}>${item} ${hasFilter.length > 0 ? '***' : ''}</option>`
        }

        if(!onlyOptions){
            select += `</select>`;
        }

        return select;
    }

    function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {

        var xlsRows  = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;

        var createXLSLFormatObj = [];
        var xlsHeader  = [];
        if (ShowLabel) {
            for (var index in xlsRows[0]) {
                xlsHeader.push(index);
            }
        }
        createXLSLFormatObj.push(xlsHeader);
        $.each(xlsRows, function(index, value) {
            var innerRowData = [];
            $.each(value, function(ind, val) {
                if(val == null){
                    innerRowData.push("null");
                }else{
                    innerRowData.push(val);
                }
            });
            createXLSLFormatObj.push(innerRowData);
        });

          /* File Name */
        var filename = `${ReportTitle.trim()}.xlsx`;

        /* Sheet Name */
        var ws_name = "Reporte";

        if (typeof console !== 'undefined') console.log(new Date());
        var wb = XLSX.utils.book_new(),
            ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);

        /* Add worksheet to workbook */
        XLSX.utils.book_append_sheet(wb, ws, ws_name);

        /* Write workbook and Download */
        if (typeof console !== 'undefined') console.log(new Date());
        XLSX.writeFile(wb, filename);
        if (typeof console !== 'undefined') console.log(new Date());
    }

});
</script>
</body>
</html>