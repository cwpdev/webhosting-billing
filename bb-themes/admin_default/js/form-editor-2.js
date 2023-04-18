$(function() {
	'use strict'
	var icons = Quill.import('ui/icons');
	icons['bold'] = '<i class="la la-bold" aria-hidden="true"><\/i>';
	icons['italic'] = '<i class="la la-italic" aria-hidden="true"><\/i>';
	icons['underline'] = '<i class="la la-underline" aria-hidden="true"><\/i>';
	icons['strike'] = '<i class="la la-strikethrough" aria-hidden="true"><\/i>';
	icons['list']['ordered'] = '<i class="la la-list-ol" aria-hidden="true"><\/i>';
	icons['list']['bullet'] = '<i class="la la-list-ul" aria-hidden="true"><\/i>';
	icons['link'] = '<i class="la la-link" aria-hidden="true"><\/i>';
	icons['image'] = '<i class="la la-image" aria-hidden="true"><\/i>';
	icons['video'] = '<i class="la la-film" aria-hidden="true"><\/i>';
	icons['code-block'] = '<i class="la la-code" aria-hidden="true"><\/i>';
	var toolbarOptions = [
		[{
			'header': [1, 2, 3, 4, 5, 6, false]
		}],
		['bold', 'italic', 'underline', 'strike'],
		[{
			'list': 'ordered'
		}, {
			'list': 'bullet'
		}],
		['link', 'image', 'video']
	];
	var quill = new Quill('#quillEditor', {
		modules: {
			toolbar: toolbarOptions
		},
		theme: 'snow'
	});
	
});