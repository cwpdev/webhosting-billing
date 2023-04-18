$(function(e) {
	
	$(document).ready(function() {
        editor = new $.fn.dataTable.Editor( {
            ajax: "../php/staff.php",
            table: "#example",
            fields: [ {
                    label: "First name:",
                    name: "first_name"
                }, {
                    label: "Last name:",
                    name: "last_name"
                }, {
                    label: "Position:",
                    name: "position"
                }, {
                    label: "Office:",
                    name: "office"
                }, {
                    label: "Extension:",
                    name: "extn"
                }, {
                    label: "Start date:",
                    name: "start_date",
                    type: "datetime"
                }, {
                    label: "Salary:",
                    name: "salary"
                }
            ]
        } );
     
        // Activate an inline edit on click of a table cell
        $('#example').on( 'click', 'tbody td:not(:first-child)', function (e) {
            editor.inline( this );
        } );
     
        $('#example').DataTable( {
            dom: "Bfrtip",
            ajax: "../php/staff.php",
            order: [[ 1, 'asc' ]],
            columns: [
                {
                    data: null,
                    defaultContent: '',
                    className: 'select-checkbox',
                    orderable: false
                },
                { data: "first_name" },
                { data: "last_name" },
                { data: "position" },
                { data: "office" },
                { data: "start_date" },
                { data: "salary", render: $.fn.dataTable.render.number( ',', '.', 0, '$' ) }
            ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            buttons: [
                { extend: "create", editor: editor },
                { extend: "edit",   editor: editor },
                { extend: "remove", editor: editor }
            ]
        } );
    } );
	

	//______Select2 
	$('.select2').select2({
		minimumResultsForSearch: Infinity,
		width:"auto",
	});


});