
   $(document).ready(function() {
   'use strict';
   

   $('.select2').select2({
	 minimumResultsForSearch: Infinity,
	 width: '100%'
   });
   $('#select-Categories1').select2({
	 minimumResultsForSearch: ''
   });
    $('#select-Categories2').select2({
	 minimumResultsForSearch: ''
   });
    $('#select-Categories3').select2({
	 minimumResultsForSearch: ''
   });
    $('#select-Categories4').select2({
	 minimumResultsForSearch: ''
   });
    $('#select-Categories5').select2({
	 minimumResultsForSearch: ''
   });
    $('#select-Categories6').select2({
	 minimumResultsForSearch: ''
   });
    $('#select-Categories7').select2({
	 minimumResultsForSearch: ''
   });
    $('#select-Categories8').select2({
	 minimumResultsForSearch: ''
   });
    $('#select-Categories9').select2({
	 minimumResultsForSearch: ''
   });
    $('#select-Categories10').select2({
	 minimumResultsForSearch: ''
   });
   $('#inputGroupSelect01').select2({
	minimumResultsForSearch: '',
  });
  $('#inputGroupSelect02').select2({
	minimumResultsForSearch: '',
  });
  $('#inputGroupSelect03').select2({
	minimumResultsForSearch: '',
  });

   // Select2 by showing the search
   $('.select2-show-search').select2({
	 minimumResultsForSearch: '',
	 placeholder: "Search"
   });

   $('#job').select2({
	 minimumResultsForSearch: '',
	 placeholder: "Search jobs here "

   });
   $('#employe').select2({
	 minimumResultsForSearch: '',
	 placeholder: "Search profiles here "
   });

	$(".select2-flag-search").select2({
	  templateResult: formatState,
	  templateSelection: formatState,
	   escapeMarkup: function(m) { return m; }
	});
	
	$("select2").select2({
        width: '35%'
    });
	
	$('.select2-no-search').select2({
		minimumResultsForSearch: Infinity,
		placeholder: 'Choose one',
		width: '35%'
   });
   $('.page-select').select2({
		minimumResultsForSearch: Infinity,
		placeholder: 'Choose one',
		width: '35%'
   });
	
   function formatState (state) {
		if (!state.id) { 
			return state.text; 
		}
		var $state = $(
			'<span><img src="../assets/images/flags/' +  state.element.value.toLowerCase() +
			'.svg" class="img-flag" /> ' +
			state.text +  '</span>'
		);
		return $state;
	};

 });