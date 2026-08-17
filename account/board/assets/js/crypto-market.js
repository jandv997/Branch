$(function(e){
  'use strict'
	 $('#marketingvalues').DataTable( {
		"order": [[ 0, "asc" ]],
		"language": {
			searchPlaceholder: 'Search...',
			sSearch: '',
		}
	} );
	//______Select2 
	$('.select2').select2({
		minimumResultsForSearch: Infinity
	});
});