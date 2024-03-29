jQuery(document).ready(function ($) {
	"use strict";

	$( '#custom_badges_bg' ).not( '[id*="__i__"]' ).wpColorPicker({
		change: function(e, ui) {
			$(e.target).val(ui.color.toString());
			$(e.target).trigger('change');
		},
		clear: function(e, ui) {
			$(e.target).trigger('change');
		}
	});

	$( '#custom_badges_color' ).not( '[id*="__i__"]' ).wpColorPicker({
		change: function(e, ui) {
			$(e.target).val(ui.color.toString());
			$(e.target).trigger('change');
		},
		clear: function(e, ui) {
			$(e.target).trigger('change');
		}
	});
});