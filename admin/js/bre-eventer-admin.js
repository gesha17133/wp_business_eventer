jQuery(document).ready(function($){
	multi_select     = $('#select_related_posts');	
	add_person       = $('#add_person_row');
	remove_person    = $('.remove_person_field');
	person_data_list = $('.data_person_repeateble');
	var table        = $('.persons_list')[0];


	multi_select.select2({
		tags: true,
		closeOnSelect: false,
		theme: "classic",
		width: '80%',
				
		/* ajax: {
			delay: 900,
			url: select_2.ajax_url,
			dataType: 'json',
			data: function(params){
				return{
					q: params.term,
					page: params.page,
				};
			}
			// Additional AJAX parameters go here; see the end of this chapter for the full code of this example
		}, */

		processResults: function (data, params) {
			params.page = params.page || 1;
			return {
				results: data.items,
				pagination: {
					more: (params.page * 2) < data.total_count
				}
			};
		}
	})
	

	$(table).delegate('#add_person_row', 'click', function () {
		var thisRow = $(this).closest('tr')[0];
		$(thisRow).clone().insertAfter(thisRow).find('input:text').val('');
	});
	
	$(table).delegate('#remove_person_row', 'click', function () {
		var thisRow = $(this).parent().parent();
		$(thisRow).remove();
	
	});
	
});
