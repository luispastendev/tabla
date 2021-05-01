<!DOCTYPE html>
<html lang="es-pe">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
  

    <title>Tablas</title>
</head>
<body>
    <table id="datos">
        <thead>
            <tr>
                <th>id</th>
                <th>Nombre</th>
                <th>Pais</th>
            </tr>
        
        </thead>
        <tfoot>
            <tr>
                <th>id</th>
                <th>Nombre</th>
                <th>Pais</th>
            </tr>
        </tfoot>
    </table> 
<script
        src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>

<script>
$(document).ready(function() {

    $('#datos thead tr').clone(true).appendTo( '#datos thead' );
    let column = 0; 
    $('#datos thead tr:eq(1) th').each(function (i) {
        
        var title = $(this).text();

        var columns = <?= json_encode($columns) ?>

        let select = `<select>`;
        select += `<option disabled selected="selected">Filtro: ${title}</option>`
        for (const item of columns[column]) {
            console.log(item)
            select += `<option value=${item}>${item}</option>`
        }
        select += `</select>`;
        
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

    var table = $('#datos').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "/getData",

        "orderCellsTop": true,
        "fixedHeader": true
    });


});
</script>
</body>
</html>