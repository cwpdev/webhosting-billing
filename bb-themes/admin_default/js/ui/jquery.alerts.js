// jQuery Alert Dialogs Plugin
//
// Version 1.1
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 14 May 2009
//
// Visit http://abeautifulsite.net/notebook/87 for more information
//
// Usage:
//		jAlert( message, [title, callback] )
//		jConfirm( message, [title, callback] )
//		jPrompt( message, [value, title, callback] )
//
// History:
//
//		1.00 - Released (29 December 2008)
//
//		1.01 - Fixed bug where unbinding would destroy all resize events
//
// License:
//
// This plugin is dual-licensed under the GNU General Public License and the MIT License and
// is copyright 2008 A Beautiful Site, LLC.
//
(function($) {

	$.alerts = {

		// These properties can be read/written by accessing $.alerts.propertyName from your scripts at any time

		verticalOffset: -75,                // vertical offset of the dialog from center screen, in pixels
		horizontalOffset: 0,                // horizontal offset of the dialog from center screen, in pixels/
		repositionOnResize: true,           // re-centers the dialog on window resize
		overlayOpacity: .01,                // transparency level of overlay
		overlayColor: '#FFF',               // base color of overlay
		draggable: true,                    // make the dialogs draggable (requires UI Draggables plugin)
		okButton: '&nbsp;OK&nbsp;',         // text for the OK button
		cancelButton: '&nbsp;Cancel&nbsp;', // text for the Cancel button
		dialogClass: null,                  // if specified, this class will be applied to all dialogs

		// Public methods

		alert: function(message, title, callback) {
			if( title == null ) title = 'Alert';
			$.alerts._show(title, message, null, 'alert', function(result) {
				if( callback ) callback(result);
			});
		},

		confirm: function(message, title, callback) {
			if( title == null ) title = 'Confirm';
			$.alerts._show(title, message, null, 'confirm', function(result) {
				if( callback ) callback(result);
			});
		},

		prompt: function(message, value, title, callback) {
			if( title == null ) title = 'Prompt';
			$.alerts._show(title, message, value, 'prompt', function(result) {
				if( callback ) callback(result);
			});
		},

		// Private methods

		_show: function(title, msg, value, type, callback) {

			$.alerts._hide();
			$.alerts._overlay('show');

			$("BODY").append(
			  '<div id="popup_containerd" class="modal"><div class="modal-dialog modal-dialog-scrollable" role="document">' +
				'<div class="modal-content modal-content-demo"><div class="modal-body">' +
			    '<h5 id="popup_title"></h5>' +
			    '<div id="popup_content" style="width:100%">' +
			      '<div id="popup_message" style="width:100%"></div>' +
				'</div>' +
			  '</div></div></div></div>');
			$("#popup_containerd").modal('show');
			if( $.alerts.dialogClass ) $("#popup_containerd").addClass($.alerts.dialogClass);

			// IE6 Fix
			var pos = (navigator.userAgent.toUpperCase().indexOf('MSIE') >=0 ) ? 'absolute' : 'fixed';

			$("#popup_containerd").css({
				/*position: pos,
				zIndex: 99999,
				padding: 5,
				margin: 0$("#popup_containerd").css({
				minWidth: $("#popup_containerd").outerWidth(),
				maxWidth: $("#popup_containerd").outerWidth()
			});
*/
			});

			$("#popup_title").text(title);
			$("#popup_content").addClass(type);
			//$("#popup_message").text(msg);
			$("#popup_message").html(msg);
			$("#popup_message").css('width','100%');
			//$("#popup_message").html( $("#popup_message").text().replace(/\n/g, '<br />') );


			$.alerts._reposition();
			$.alerts._maintainPosition(true);

			switch( type ) {
				case 'alert':
					$("#popup_message").after('<div id="popup_panel"><input type="button" class="btn btn-primary" value="' + $.alerts.okButton + '" id="popup_ok" /></div>');
					$("#popup_ok").click( function() {
						$("#popup_containerd").remove();
						$("#popup_containerd").modal('hide');
						$.alerts._hide();
						$("#popup_overlay").remove();
						$(".modal-backdrop").remove();
						callback(true);
					});
					$("#popup_ok").focus().keypress( function(e) {
						if( e.keyCode == 13 || e.keyCode == 27 ) $("#popup_ok").trigger('click');
					});
				break;
				case 'confirm':
					$("#popup_message").after('<div id="popup_panel"><button type="button" class="btn btn-success" value="" id="popup_ok"><i class="fe fe-check-circle"></i> ' + $.alerts.okButton + '</button> <button type="button" class="btn btn-danger" value="" id="popup_cancel"><i class="fe fe-slash"></i> ' + $.alerts.cancelButton + '</button></div>');
					$("#popup_ok").click( function() {
						$("#popup_containerd").remove();
						$("#popup_containerd").modal('hide');
						$.alerts._hide();
						$("#popup_overlay").remove();
						$(".modal-backdrop").remove();
						if( callback ) callback(true);
					});
					$("#popup_cancel").click( function() {
						$("#popup_containerd").remove();
						$("#popup_containerd").modal('hide');
						$.alerts._hide();
						$("#popup_overlay").remove();
						$(".modal-backdrop").remove();
						if( callback ) callback(false);
					});
					$("#popup_ok").focus();
					$("#popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
				break;
				case 'prompt':
					//$("#popup_message").css('width','100%');
					$("#popup_message").html($("#popup_message").html()+'<br /><input type="text" size="20" id="popup_prompt" />').after('<div id="popup_panel"><button type="button" class="btn btn-primary" value="" id="popup_ok"><i class="fe fe-check-circle"></i> ' + $.alerts.okButton + '</button> <button type="button" class="btn btn-danger" value="" id="popup_cancel"><i class="fe fe-slash"></i> ' + $.alerts.cancelButton + '</button></div>');
					$("#popup_prompt").width( $("#popup_message").width(-10) );
					$("#popup_ok").click( function() {
						var val = $("#popup_prompt").val();
						$("#popup_containerd").remove();
						$("#popup_containerd").modal('hide');
						$.alerts._hide();
						$("#popup_overlay").remove();
						$(".modal-backdrop").remove();
						if( callback ) callback( val );
					});
					$("#popup_cancel").click( function() {
						$("#popup_containerd").remove();
						$("#popup_containerd").modal('hide');
						$.alerts._hide();
						$("#popup_overlay").remove();
						$(".modal-backdrop").remove();
						if( callback ) callback( null );
					});
					$("#popup_prompt, #popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
					if( value ) $("#popup_prompt").val(value);
					$("#popup_prompt").focus().select();
					$("#popup_message").css('width','100%');
				break;
			}

			// Make draggable
			if( $.alerts.draggable ) {
				try {
					$("#popup_containerd").draggable({ handle: $("#popup_title") });
					$("#popup_title").css({ cursor: 'move' });
				} catch(e) { /* requires jQuery UI draggables */ }
			}
		},

		_hide: function() {
			$("#popup_containerd").remove();
			$("#popup_containerd").modal('hide');
			$.alerts._overlay('hide');
			$.alerts._maintainPosition(false);
		},

		_overlay: function(status) {
			switch( status ) {
				case 'show':
					/*$.alerts._overlay('hide');
					$("BODY").append('<div id="popup_overlay"></div>');
					$("#popup_overlay").css({
						position: 'absolute',
						zIndex: 99998,
						top: '0px',
						left: '0px',
						width: '100%',
						height: $(document).height(),
						background: $.alerts.overlayColor,
						opacity: $.alerts.overlayOpacity
					});*/
				break;
				case 'hide':
					$("#popup_overlay").remove();
				break;
			}
		},

		_reposition: function() {
			var top = (($(window).height() / 2) - ($("#popup_containerd").outerHeight() / 2)) + $.alerts.verticalOffset;
			var left = (($(window).width() / 2) - ($("#popup_containerd").outerWidth() / 2)) + $.alerts.horizontalOffset;
			if( top < 0 ) top = 0;
			if( left < 0 ) left = 0;

			// IE6 fix
			if( navigator.userAgent.toUpperCase().indexOf('MSIE') >=0) top = top + $(window).scrollTop();

			$("#popup_containerd").css({
				top: top + 'px',
				left: left + 'px'
			});
			$("#popup_overlay").height( $(document).height() );
		},

		_maintainPosition: function(status) {
			if( $.alerts.repositionOnResize ) {
				switch(status) {
					case true:
						$(window).bind('resize', $.alerts._reposition);
					break;
					case false:
						$(window).unbind('resize', $.alerts._reposition);
					break;
				}
			}
		}

	}

	// Shortuct functions
	jAlert = function(message, title, callback) {
		$.alerts.alert(message, title, callback);
	}

	jConfirm = function(message, title, callback) {
		$.alerts.confirm(message, title, callback);
	};

	jPrompt = function(message, value, title, callback) {
		$.alerts.prompt(message, value, title, callback);
	};

})(jQuery);
