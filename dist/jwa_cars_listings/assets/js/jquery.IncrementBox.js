(function ($) {
	$.fn.IncrementBox = function (options) {
		var settings = {
			timeout: 50,
			cursor: false
		};
		return this.each(function () {
			if (options) {
				$.extend(settings, options);
			}
			var $this = $(this);
			var dec = $this.find('.dec');
			var inc = $this.find('.inc');
			var counter = $this.find(".radius-num");
			var iteration = 1;
			var timeout = 50;
			var isDown = false;
			updateCursor();
			mousePress(inc, doIncrease);
			mousePress(dec, doDecrease);
			function mousePress(obj, func) {
				focusElement = obj;
				obj.unbind('mousedown');
				obj.unbind('mouseup');
				obj.unbind('mouseleave');
				obj.bind('mousedown', function () {
					isDown = true;
					setTimeout(func, settings.timeout);
				});

				obj.bind('mouseup', function () {
					isDown = false;
					iteration = 1;
//					clearTimeout(mousedownTimeout);
				});

				obj.bind('mouseleave', function () {
					isDown = false;
					iteration = 1;
//					clearTimeout(mousedownTimeout);
				});
			}
			function updateCursor() {
				if (settings.cursor) {
					dec.css('cursor', 'pointer');
					inc.css('cursor', 'pointer');
				}
			}
			function doIncrease() {
				if (isDown) {
					var increement = getIncrement(iteration);
					counter.val(function (i, v) {
						return Number(v) + increement;
					});
					iteration++;
					setTimeout(doIncrease, settings.timeout);
				} else {
//					clearTimeout(mousedownTimeout);
				}
			}
			function doDecrease() {
				if (isDown) {
					var increement = getIncrement(iteration);
					counter.val(function (i, v) {
						var result = Number(v) - increement
						if (result < 0) result = 0;
						return result;
					});
					iteration++;
					setTimeout(doDecrease, settings.timeout);
				} else {
//					clearTimeout(mousedownTimeout);
				}
			}
			function getIncrement(iteration) {
				var increement = 1;
				if (iteration >= 20) {
					increement = 10;
				}
				if (iteration >= 30) {
					increement = 100;
				}
				if (iteration >= 40) {
					increement = 1000;
				}
				return increement;
			}
		});
	};
})(jQuery);
