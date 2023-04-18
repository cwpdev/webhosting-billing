(function ( $ ) { 
	       'use strict';
		    
			
   // ______________ Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
    })
	

    var tooltip = new bootstrap.Tooltip(document.querySelector('[data-bs-toggle="tooltip-primary"]'), {
        template: '<div class="tooltip tooltip-primary" role="tooltip"><div class="tooltip-arrow"><\/div><div class="tooltip-inner"><\/div><\/div>'
    })
	
    var tooltip = new bootstrap.Tooltip(document.querySelector('[data-bs-toggle="tooltip-secondary"]'), {
        template: '<div class="tooltip tooltip-secondary" role="tooltip"><div class="tooltip-arrow"><\/div><div class="tooltip-inner"><\/div><\/div>'
    })
    var tooltip = new bootstrap.Tooltip(document.querySelector('[data-bs-toggle="tooltip-primary1"]'), {
        template: '<div class="tooltip tooltip-primary1" role="tooltip"><div class="tooltip-arrow"><\/div><div class="tooltip-inner"><\/div><\/div>'
    })
    var tooltip = new bootstrap.Tooltip(document.querySelector('[data-bs-toggle="tooltip-secondary1"]'), {
        template: '<div class="tooltip tooltip-secondary1" role="tooltip"><div class="tooltip-arrow"><\/div><div class="tooltip-inner"><\/div><\/div>'
    })
	  
	
		
}( jQuery ));
