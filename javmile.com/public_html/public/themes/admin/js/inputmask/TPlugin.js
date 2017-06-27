/*
	This plugin base-on Jquery Input Mask
	Before using this Plugin make sure that you included 3 script before this script
	1. inputmask.js
	2. jquery.inputmask.js
	3. inputmask.numeric.extensions.js
*/

(function ($) {
    $.fn.extend({
        moneyMask: function (maxlength) {
            return this.inputmask('decimal', {
				radixPoint: ".", digits: 2, integerDigits: maxlength, allowMinus: true,
				autoGroup: true,
				'autoUnmask' : true,
				groupSeparator: " ",
				groupSize: 3,
				skipRadixDance: true
			});
        }
    });
})(jQuery);