/* --------------------------------------------------------------------------------
  Init piklist-datetimepicker fields
--------------------------------------------------------------------------------- */

;(function($, window, document, undefined) {
  'use strict';

  $(document).ready(function() {
    $('.piklist-datetimepicker').each(function() {
    	var curr_element = $(this);

    	var config = {
    	};

        // as the control doesn't support data- attributes, we set them programmatically
        var data_vals = curr_element.data();
        for (var key in data_vals) {
            config[key] = data_vals[key]; 
        }

		curr_element.datetimepicker(config);
	});
  });

})(jQuery, window, document);
