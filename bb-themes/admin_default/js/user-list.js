$(function(e) {

	//______Delete Data Table
	var table = $('#user-datatable').DataTable({
		language: {
			searchPlaceholder: 'Search For User...',
			sSearch: '',
		}
	}); 
    $('#user-datatable tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
    $('#userlist-1').click( function () {
        table.row('.selected').remove().draw( false );
    } );

	
	

	//______Select2 
	$('.select2').select2({
		minimumResultsForSearch: Infinity,
		width:"auto",
	});

});