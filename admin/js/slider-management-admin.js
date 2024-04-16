jQuery(document).ready(function($) {

	'use strict';
    $('#add-slider-field').on('click', function() {
        var sliderRow = $('.slider-row').first().clone();
        $('.slider-fields').append(sliderRow);
    });
});
