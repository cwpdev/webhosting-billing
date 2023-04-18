$( function() {
	
	//Counters
	$('.counter').countUp();

	// count up
	$( '#timer-countup' ).countdown( {
		from: 0,
		to: 180	
	} );
	// count in-between
	$( '#timer-countinbetween' ).countdown( {
		from: 60,
		to: 20 
	} );
	// counter callback
	$( '#timer-countercallback' ).countdown( {
		from: 60,
		to: 0,
		timerEnd: function() {
			this.animate( { 'opacity':.5 }, 500 ).css( { 'text-decoration':'line-through' } );
		} 
	} );

	// count up
	$( '#timer-countup1' ).countdown( {
		from: 0,
		to: 180	
	} );
	// count in-between
	$( '#timer-countinbetween1' ).countdown( {
		from: 60,
		to: 20 
	} );
	// counter callback
	$( '#timer-countercallback1' ).countdown( {
		from: 60,
		to: 0,
		timerEnd: function() {
			this.animate( { 'opacity':.5 }, 500 ).css( { 'text-decoration':'line-through' } );
		} 
	} );

	// changed output patterns
	$( '#timer-outputpattern' ).countdown( {
		outputPattern: '$day Days $hour Hours $minute Minute $second Seconds',
		from: 60 * 60 * 24 * 3
	} );

	$('.under-countdown').countdown( {
		endtimeYear: 0,
		endtimeMonth: 0,
		endtimeDate: 35,
		endtimeHours: 18,
		endtimeMinutes: 0,
		endtimeSeconds: 0,
		timeZone: ""
	});
	
});