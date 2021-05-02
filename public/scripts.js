$(document).ready(function() {

    $('#datos thead tr').clone(true).appendTo( '#datos thead' );
    let column = 0; 
    $('#datos thead tr:eq(1) th').each(function (i) {
        
        var title = $(this).text();

        var columns = <?= json_encode($columns) ?>

        let select = `<select>`;
        select += `<option value='' selected="selected">Filtro: ${title}</option>`
        for (const item of columns[column]) {
            select += `<option value="${item}">${item}</option>`
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
        processing: true,
        serverSide: true,
        ajax: "/getData",
        orderCellsTop: true,
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
                        // console.log(data);
                        JSONToCSVConvertor(data, "Reporte de datos", true);
                    });
                    
                },
            }
        ]
    });

    function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
        //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
        var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;

        var CSV = '';
        //Set Report title in first row or line

        CSV += ReportTitle + '\r\n\n';

        //This condition will generate the Label/Header
        if (ShowLabel) {
            var row = "";

            //This loop will extract the label from 1st index of on array
            for (var index in arrData[0]) {

            //Now convert each value to string and comma-seprated
            row += index + ',';
            }

            row = row.slice(0, -1);

            //append Label row with line break
            CSV += row + '\r\n';
        }

        //1st loop is to extract each row
        for (var i = 0; i < arrData.length; i++) {
            var row = "";

            //2nd loop will extract each column and convert it in string comma-seprated
            for (var index in arrData[i]) {
            row += '"' + arrData[i][index] + '",';
            }

            row.slice(0, row.length - 1);

            //add a line break after each row
            CSV += row + '\r\n';
        }

        if (CSV == '') {
            alert("Invalid data");
            return;
        }

        //Generate a file name
        var fileName = "MyReport_";
        //this will remove the blank-spaces from the title and replace it with an underscore
        fileName += ReportTitle.replace(/ /g, "_");

        //Initialize file format you want csv or xls
        var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);

        // Now the little tricky part.
        // you can use either>> window.open(uri);
        // but this will not work in some browsers
        // or you will not get the correct file extension    

        //this trick will generate a temp <a /> tag
        var link = document.createElement("a");
        link.href = uri;

        //set the visibility hidden so it will not effect on your web-layout
        link.style = "visibility:hidden";
        link.download = fileName + ".csv";

        //this part will append the anchor tag and remove it after automatic click
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

});