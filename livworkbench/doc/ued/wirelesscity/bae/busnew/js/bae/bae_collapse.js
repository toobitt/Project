$(function() {
	$.fn.bae_collapse = function(options) {
		var jqEle = this;
		$.each(jqEle, function(index, element) {
			var collapseP = $(element);
			var collapseHead = collapseP.find("[data-role='bae-collapse-head']");
			collapseP.addClass("ui-bae-collapses");
			collapseHead.addClass("ui-bae-collapse-head");
			$.each(collapseHead, function(index, element) {
				var collapseN = $(element);
				var collapseBody = collapseN.next();
				if (collapseBody.attr("data-role") == "bae-collapse-body") {
					collapseBody.addClass("ui-bae-collapse-body");
					var collapseDisplay = collapseBody.attr("data-display");
					if (collapseDisplay == "true") {
						collapseBody.addClass("collapse in");
						collapseN.addClass("collpsed")
					} else {
						collapseBody.addClass("collapse")
					}
					collapseN.bind("click", function() {
						if (collapseBody.attr("data-display") == "true") {
							collapseBody.bae_collapse_hide();
							collapseBody.attr("data-display", "false");
							return
						} else {
							collapseBody.bae_collapse_show();
							collapseBody.attr("data-display", "true");
							return
						}
					})
				}
			})
		})
	};
	$.fn.bae_collapse_show = function() {
		var divS = this;
		divS.removeClass("collapse").addClass("collapsing")["height"](0);
		var transitioning = 1;
		var complete = function() {
			divS.removeClass("collapsing").addClass("in")["height"]("auto");
			transitioning = 0
		};
		if (!$.support.transition) {
			return complete.call(this)
		}
		divS.one($.support.transition.end,$.proxy(complete,this)).emulateTransitionEnd(350)["height"](divS.get(0).scrollHeight);
		divS.prev().addClass("collpsed")
	};
	$.fn.bae_collapse_hide = function() {
		var divS = this;
		divS.height(divS.height())[0].offsetHeight
		divS.addClass("collapsing").removeClass("collapse").removeClass("in");
		var transitioning = 1;
		var complete = function() {
			divS.removeClass("collapsing").addClass("collapse");
			divS.prev().removeClass("collpsed");
			transitioning = 0
		};
		if (!$.support.transition) {
			return complete.call(this)
		}
		divS.height(0).one($.support.transition.end, $.proxy(complete, this)).emulateTransitionEnd(350)
	};
	function transitionEnd() {
		var el = document.createElement("bootstrap");
		var transEndEventNames = {
			WebkitTransition : "webkitTransitionEnd",
			MozTransition : "transitionend",
			OTransition : "oTransitionEnd otransitionend",
			transition : "transitionend"
		};
		for (var name in transEndEventNames) {
			if (el.style[name] !== undefined) {
				return {
					end : transEndEventNames[name]
				}
			}
		}
	}
	$.fn.emulateTransitionEnd = function(duration) {
		var called = false, $el = this;
		$(this).one($.support.transition.end, function() {
			called = true
		});
		var callback = function() {
			if (!called) {
				$($el).trigger($.support.transition.end)
			}
		};
		setTimeout(callback, duration);
		return this
	};
	$.support.transition = transitionEnd()
});
$(function() {
	$("[data-role='bae-collapse']").bae_collapse()
});
