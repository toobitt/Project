define(function (require, exports, module) {
	return function (forms) {
		forms.each(function () {
			$(this).submit(function () {
				$(this).find('.need-focusblurtxt').each(function () {
					if ( $(this).data('default') == this.value  ) {
						this.value = '';
					}
				});
			});
		})
		forms.find('.need-focusblurtxt').focus(function () {
			if ( $(this).data('default') == this.value ) {
				this.value = '';
			}
		}).blur(function () {
			if ( !this.value ) {
				this.value = $(this).data('default');
			}
		});
	}
})